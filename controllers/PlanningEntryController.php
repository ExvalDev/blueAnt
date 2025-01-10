<?php

require_once 'RequestController.php';

class PlanningEntryController
{
    private $requestController;
    private $config;
    private static $priorities = null; // Static property to cache statuses

    // Constructor to load configuration
    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

 

    public function getPlanningEntriesByCityId($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['planningEntries']);
            $response = $this->requestController->get($endpoint);
            if (!isset($response['entries'])) {
                throw new Exception('planningEntries not found in API response');
            }
            return $response['entries'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }
}