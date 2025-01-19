<?php
/**
 * Controller to handle API Requests for Status
 */
require_once 'RequestController.php';

class StatusController
{
    private $requestController;
    private $config;
    private static $statuses = null;
    private $activeStatuses = [];

    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    public function getStatuses()
    {
        try {
            if (self::$statuses === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['statuses']);
                if (!isset($response['projectStatus'])) {
                    throw new Exception('Statuses not found in API response');
                }
                self::$statuses = $response['projectStatus'];
            }

            return self::$statuses;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getStatusById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['status']);
            $response = $this->requestController->get($endpoint);
            if (!isset($response['projectStatus'])) {
                throw new Exception('Status not found in API response');
            }
            return $response['projectStatus'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }
}