<?php

require_once 'RequestService.php';

class ClientService
{
    private $requestService;
    private $config;
    private static $clients = null; // Static property to cache statuses

    // Constructor to load configuration
    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    // Fetch all statuses and cache them
    private function fetchClients()
    {
        try {
            if (self::$clients === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['customers']);
                if (!isset($response['customers'])) {
                    throw new Exception('Customers not found in API response');
                }
                self::$clients = [];
                foreach ($response['customers'] as $client) {
                    self::$clients[$client['id']] = $client;
                }
            }

            return self::$clients;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getClients()
    {
        return $this->fetchClients();
    }

    public function getClientById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['customer']);
            $response = $this->requestService->get($endpoint);
            if (!isset($response['customer'])) {
                throw new Exception("Customer data not found in API response for ID: {$id}");
            }
            return $response["customer"];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function findClientById($clientId)
    {
        $clients = $this->fetchClients();

        if (isset($clients[$clientId])) {
            return $clients[$clientId];
        }

        throw new Exception("Customer with ID $clientId not found.");
    }
}