<?php

/**
 * Service class to handle CustomType actions
 */

require 'models/CustomerType.php';
require 'controllers/CustomerTypeController.php';

class CustomerTypeService
{
    private $customerTypeController;
    private static $customerTypes = null;

    public function __construct()
    {
        $this->customerTypeController = new CustomerTypeController();
    }

    public function getCustomerTypes(): array
    {
        if (self::$customerTypes === null) {
            self::$customerTypes = [];
            $response = $this->customerTypeController->getCustomerTypes();
            foreach ($response as $customerType) {
                self::$customerTypes[$customerType['id']] = new CustomerType(
                    $customerType['id'],
                    $customerType['text'],
                );
            }
        }
        return self::$customerTypes;
    }

    public function findCustomerTypeById(int $customerTypeId): CustomerType|null
    {
        $customerTypes = $this->getCustomerTypes();

        if (isset($customerTypes[$customerTypeId])) {
            return $customerTypes[$customerTypeId];
        }

        error_log("[CustomerTypeService] CustomerType with ID $customerTypeId not found.");
        return null;
    }
}