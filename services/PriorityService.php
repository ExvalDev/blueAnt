<?php

require_once 'RequestService.php';

class PriorityService
{
    private $requestService;
    private $config;
    private static $priorities = null; // Static property to cache statuses

    // Constructor to load configuration
    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    // Fetch all statuses and cache them
    private function fetchPriorities()
    {
        try {
            if (self::$priorities === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['priorities']);
                if (!isset($response['priorities'])) {
                    throw new Exception('Priorities not found in API response');
                }
                self::$priorities = [];
                foreach ($response['priorities'] as $priority) {
                    self::$priorities[$priority['id']] = $priority;
                }
            }
            return self::$priorities;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function getPriorities()
    {
        return $this->fetchPriorities();
    }

    public function getPriorityById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['priority']);
            $response = $this->requestService->get($endpoint);
            if (!isset($response['priority'])) {
                throw new Exception('Priority not found in API response');
            }
            return $response['priority'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }

    public function findPriorityById($priorityId)
    {
        $priorities = $this->fetchPriorities();

        if (isset($priorities[$priorityId])) {
            return $priorities[$priorityId];
        }

        throw new Exception("Priority with ID $priorityId not found.");
    }
}