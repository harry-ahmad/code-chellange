<?php

namespace App\Repository;

use App\Database\Database;
use App\Helpers\ValidationHelper;
use PDO;

class Repository
{
    /** @var Database db */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function salesListByFilter(?array $filter = []): array
    {
        $sql = 'SELECT p.product_name AS productName, c.customer_name as customerName, c.customer_mail as customerMail, p.product_price as productPrice, s.sale_date as saleDate, s.version FROM sales s 
    INNER JOIN products p ON p.product_id = s.product_id INNER JOIN customers c ON c.customer_id = s.customer_id';
        if (!empty($filter)) {
            $where = ' where';
            $execute = [];
            if (!empty($filter['customer_name'])) {
                $where .= " c.customer_name = ?";
                $execute[] = trim($filter['customer_name']);
            }

            if (!empty($filter['product_name'])) {
                if(sizeof($execute) > 0) $where .= " OR";
                $where .= " p.product_name = ?";
                $execute[] = trim($filter['product_name']);
            }

            if (!empty($filter['product_price'])) {
                if(sizeof($execute) > 0) $where .= " OR";
                $where .= " p.product_price = ?";
                $execute[] = trim($filter['product_price']);
            }
            if ($execute) {
                $sql .= $where;
                $statement = $this->db->getConnection()->prepare($sql);
                $statement->execute($execute);
            } else {
                $statement = $this->db->getConnection()->query($sql);
            }
        } else {
            $statement = $this->db->getConnection()->query($sql);
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCustomer(array $data): string
    {
        $sql = "INSERT INTO customers (customer_name, customer_mail) VALUES (?,?)";
        $this->db->getConnection()->prepare($sql)->execute([$data['customer_name'], $data['customer_mail']]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function createProduct(array $data): string
    {
        $sql = "INSERT INTO products (product_name, product_price) VALUES (?,?)";
        $this->db->getConnection()->prepare($sql)->execute([addslashes($data['product_name']), $data['product_price']]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function createSales(array $data): string
    {
        $sql = "INSERT INTO sales (customer_id, product_id, sale_date, version) VALUES (?,?,?,?)";
        $this->db->getConnection()->prepare($sql)->execute([$data['customer_id'], $data['product_id'], $data['sale_date'], $data['version']]);
        return $this->db->getConnection()->lastInsertId();
    }
}
