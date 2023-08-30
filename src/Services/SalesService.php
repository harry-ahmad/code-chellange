<?php

namespace App\Services;

require_once __DIR__ . "/../Constants/Constants.php";

use App\Database\Database;
use App\Helpers\ValidationHelper;
use App\Helpers\VersionComparisonHelper;
use App\Repository\Repository;
use App\Services\CustomerService;
use App\Services\ProductService;
use Exception;

class SalesService
{

    /** @var Database db */
    private $db;

    /** @var Repository repo */
    private $repo;

    /** @var CustomerService customerService */
    private $customerService;

    /** @var ProductService productService */
    private $productService;

    /** @var ValidationHelper validations */
    private $validator;

    /** @var VersionComparisonHelper versionComparison */
    private $versionComparison;


    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->repo = new Repository();
        $this->customerService = new CustomerService();
        $this->productService = new ProductService();
        $this->validator = new ValidationHelper();
        $this->versionComparison = new VersionComparisonHelper();
    }

    public function getSales(?array $filterData = []): array
    {
        try {
            $response =  $this->repo->salesListByFilter($filterData);
            foreach ($response as $key => $value){
                $response[$key]['timezone'] = $this->versionComparison->compareVersion($value['version']);
            }
            return $response;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function upload($request): string
    {
        try {
            $fileData = $request->getUploadedFileContent('importfile');
            if (isset($fileData)) {

                $this->db->getConnection()->beginTransaction();
                foreach ($fileData as $key => $value) {
                    if (!$this->validator->validateJsonKeys($value, SALES_KEYS) || !$this->validator->validateJsonValues($value)) {
                        throw new Exception('Invalid Json Data!!');
                    }
                    $customerResponse = $this->customerService->createCustomer($value);
                    $productResponse = $this->productService->createProduct($value);
                    if (!$customerResponse['error'] && !$productResponse['error']) {
                        $this->createSales(
                            ['customer_id' => $customerResponse['data'], 'product_id' => $productResponse['data'], 'sale_date' => $value['sale_date'], 'version' => $value['version']]
                        );
                    }
                }
                $this->db->getConnection()->commit();

                $response = json_encode(['status' => 'success', 'msg' => 'Sales uploaded Successfully!']);
            }
        } catch (\Exception $e) {
            $this->db->getConnection()->rollback();
            $response = json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
        }

        return $response;

    }

    public function createSales(array $data): array
    {
        $result = ['error' => true, 'message' => ""];
        try {
            $lastInsertId = $this->repo->createSales($data);
            $result = ['error' => false, 'data' => $lastInsertId];
        } catch (\Exception $exception) {
            $result['message'] = sprintf("Error occurred: %s", $exception->getMessage());
        }
        return $result;
    }
}