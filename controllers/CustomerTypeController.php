<?php

require_once 'RequestController.php';

class CustomerTypeController
{
    private $requestController;
    private $config;
    private static $customerTypes = null; // Static property to cache statuses

    // Constructor to load configuration
    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    // Fetch all statuses and cache them
    public function getCustomerTypes()
    {

        try {
            if (self::$customerTypes === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['customerTypes']);
                if (!isset($response['types'])) {
                    throw new Exception('Customer Types not found in API response');
                }
                self::$customerTypes = $response['types'];
            }
            return self::$customerTypes;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }


    }
}