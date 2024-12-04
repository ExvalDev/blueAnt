<?php

require_once 'RequestController.php';

class PriorityController
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

    // Fetch all statuses and cache them
    public function getPriorities()
    {
        try {
            if (self::$priorities === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['priorities']);
                if (!isset($response['priorities'])) {
                    throw new Exception('Priorities not found in API response');
                }
                self::$priorities = $response['priorities'];
            }
            return self::$priorities;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function getPriorityById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['priority']);
            $response = $this->requestController->get($endpoint);
            if (!isset($response['priority'])) {
                throw new Exception('Priority not found in API response');
            }
            return $response['priority'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }
}