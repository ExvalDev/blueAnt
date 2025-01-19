<?php
/**
 * Controller to handle API Requests for ProjectType
 */
require_once 'RequestController.php';

class ProjectTypeController
{
    private $requestController;
    private $config;
    private static $types = null;

    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    public function getProjectTypes()
    {
        try {
            if (self::$types === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['types']);
                if (!isset($response['types'])) {
                    throw new Exception('Types not found in API response');
                }
                self::$types = $response['types'];
            }

            return self::$types;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }

    public function getProjectTypeById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['type']);
            $response = $this->requestController->get($endpoint);
            if (!isset($response['type'])) {
                throw new Exception('Type not found in API response');
            }
            return $response['type'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}