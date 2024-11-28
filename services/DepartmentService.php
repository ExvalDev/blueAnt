<?php

require_once 'RequestService.php';

class DepartmentService
{
    private $requestService;
    private $config;
    private static $departments = null;

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    private function fetchDepartments()
    {
        try {
            if (self::$departments === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['departments']);
                if (!isset($response['departments'])) {
                    throw new Exception('Departments not found in API response');
                }
                self::$departments = [];

                foreach ($response['departments'] as $department) {
                    self::$departments[$department['id']] = $department;
                }
            }
            return self::$departments;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getDepartmentById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['department']);
            $response = $this->requestService->get($endpoint);
            if (!isset($response['department'])) {
                throw new Exception("Department data not found in API response for ID: {$id}");
            }
            return $response['department'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    // Get all cached statuses
    public function getDepartments()
    {
        return $this->fetchDepartments();
    }


    public function findDepartmentById($departmentId)
    {
        $statuses = $this->fetchDepartments();

        if (isset($statuses[$departmentId])) {
            return $statuses[$departmentId];
        }

        throw new Exception("Department with ID $departmentId not found.");
    }
}