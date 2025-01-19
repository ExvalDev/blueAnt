<?php

/**
 * Controller to handle API Requests for Department
 */

require_once 'RequestController.php';

class DepartmentController
{
    private $requestController;
    private $config;
    private static $departments = null;

    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    public function getDepartments()
    {
        try {
            if (self::$departments === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['departments']);
                if (!isset($response['departments'])) {
                    throw new Exception('Departments not found in API response');
                }
                self::$departments = $response['departments'];
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
            $response = $this->requestController->get($endpoint);
            if (!isset($response['department'])) {
                throw new Exception("Department data not found in API response for ID: {$id}");
            }
            return $response['department'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}