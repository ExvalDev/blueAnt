<?php

require 'models/Customer.php';
require 'controllers/CustomerController.php';
require 'services/CustomerTypeService.php';

class CustomerService
{
    private $customerController;
    private $customerTypeService;
    private static $customers = null;

    public function __construct()
    {
        $this->customerController = new CustomerController();
        $this->customerTypeService = new CustomerTypeService();
    }

    public function getCustomers(): array
    {
        if (self::$customers === null) {
            self::$customers = [];
            $response = $this->customerController->getCustomers();
            foreach ($response as $customer) {
                self::$customers[$customer['id']] = new Customer(
                    $customer['id'],
                    $customer['text'],
                    $this->customerTypeService->findCustomerTypeById($customer['typeId']),
                );
            }
        }
        return self::$customers;
    }

    public function getCustomerById(string $id): Customer
    {
        $customer = $this->customerController->getCustomerById($id);
        return new Customer(
            $customer['id'],
            $customer['text'],
            $this->customerTypeService->findCustomerTypeById($customer['typeId']),
        );
    }

    public function findCustomerById(int $customerId): Customer|null
    {
        $customers = $this->getCustomers();

        if (isset($customers[$customerId])) {
            return $customers[$customerId];
        }

        error_log("[CustomerService] Customer with ID $customerId not found.");
        return null;
    }

    public function getCustomersFromProjectClients(array $projectClients): array
    {
        $customersFromProject = [];
        foreach ($projectClients as $client) {
            try {
                // Fetch client details
                $customer = $this->findCustomerById($client['clientId']);

                if (!$customer) {
                    throw new Exception("Customer data not found for clientId: " . $client['clientId']);
                }
                $customersFromProject[] = $customer;
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
        return $customersFromProject;
    }
}