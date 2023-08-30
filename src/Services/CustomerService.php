<?php
namespace App\Services;

use App\Repository\Repository;

class CustomerService
{

    /** @var Repository repo */
    private $repo;

    public function __construct()
    {
        $this->repo = new Repository();
    }

    public function createCustomer(array $data): array
    {
        $result = ['error' => true, 'message' => ""];
        try {
            $lastInsertId = $this->repo->createCustomer($data);
            $result = ['error' => false, 'data' => $lastInsertId];
        } catch (\Exception $exception) {
            $result['message'] = sprintf("Error occurred: %s", $exception->getMessage());
        }
        return $result;
    }
}