<?php

namespace App\Helpers;

class ValidationHelper
{
    /** validate json keys */
    public function validateJsonKeys($data, $keys): bool
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $keys)) {
                return false;
            }
        }
        return true;
    }

    /** validate json values */
    public function validateJsonValues(array $data): bool
    {
        if (gettype($data['sale_id']) !== 'string' || empty($data['sale_id'])) {
            return false;
        }
        if (gettype($data['customer_name']) !== 'string' || empty($data['customer_name'])) {
            return false;
        }
        if (gettype($data['customer_mail']) !== 'string' || empty($data['customer_mail']) || !filter_var($data['customer_mail'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if (gettype($data['product_id']) !== 'integer' || empty($data['product_id'])) {
            return false;
        }
        if (gettype($data['product_name']) !== 'string' || empty($data['product_name'])) {
            return false;
        }
        if (gettype($data['product_price']) !== 'string' || empty($data['product_price']) || !is_numeric($data['product_price'])) {
            return false;
        }
        if (gettype($data['sale_date']) !== 'string' || empty($data['sale_date']) || !$this->validateDate($data['sale_date'])) {
            return false;
        }
        if (gettype($data['version']) !== 'string' || empty($data['version'])) {
            return false;
        }
        return true;
    }

    function validateDate(string $date): bool
    {
        return (strtotime($date) !== false);
    }
}