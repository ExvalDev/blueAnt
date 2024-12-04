<?php

require_once 'RequestController.php';

class CustomerController
{
    private $requestController;
    private $config;
    private static $customers = null; // Static property to cache statuses

    // Constructor to load configuration
    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    // Fetch all statuses and cache them
    public function getCustomers()
    {
        try {
            if (self::$customers === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['customers']);
                if (!isset($response['customers'])) {
                    throw new Exception('Customers not found in API response');
                }
                self::$customers = $response['customers'];
            }

            return self::$customers;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getcustomerById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['customer']);
            $response = $this->requestController->get($endpoint);
            if (!isset($response['customer'])) {
                throw new Exception("Customer data not found in API response for ID: {$id}");
            }
            return $response["customer"];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}