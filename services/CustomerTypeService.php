<?php

require_once 'RequestService.php';

class CustomerTypeService
{
    private $requestService;
    private $config;
    private static $customerTypes = null; // Static property to cache statuses

    // Constructor to load configuration
    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    // Fetch all statuses and cache them
    private function fetchCustomerTypes()
    {
        if (self::$customerTypes === null) {
            try {


                $response = $this->requestService->get($this->config['apiEndpoints']['customerTypes']);
                self::$customerTypes = [];
                if (!isset($response['types'])) {
                    throw new Exception('Customer Types not found in API response');
                }
                foreach ($response['types'] as $customerType) {
                    self::$customerTypes[$customerType['id']] = $customerType;
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }


        return self::$customerTypes;
    }

    public function getCustomerTypes()
    {
        return $this->fetchCustomerTypes();
    }

    public function findCustomerTypeById($typeId)
    {
        $customerTypes = $this->fetchCustomerTypes();

        if (isset($customerTypes[$typeId])) {
            return $customerTypes[$typeId];
        }

        throw new Exception("Customer Type with ID $typeId not found.");
    }
}