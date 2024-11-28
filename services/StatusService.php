<?php

require_once 'RequestService.php';

class StatusService
{
    private $requestService;
    private $config;
    private static $statuses = null;
    private static $activePhases = [1, 2, 3];
    private $activeStatuses = [];

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    private function fetchStatuses()
    {
        try {
            if (self::$statuses === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['statuses']);
                if (!isset($response['projectStatus'])) {
                    throw new Exception('Statuses not found in API response');
                }
                self::$statuses = [];
                foreach ($response['projectStatus'] as $status) {
                    self::$statuses[$status['id']] = $status;
                }
            }

            return self::$statuses;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    private function filterActiveStatuses()
    {
        if (empty($this->activeStatuses)) {
            // Filter statuses and keep the whole object
            $this->activeStatuses = array_filter(self::$statuses, function ($status) {
                return isset($status['phase']) && in_array($status['phase'], self::$activePhases, true);
            });
        }

        return $this->activeStatuses;
    }

    public function getStatusById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['status']);
            $response = $this->requestService->get($endpoint);
            if (!isset($response['projectStatus'])) {
                throw new Exception('Status not found in API response');
            }
            return $response['projectStatus'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }

    // Get all cached statuses
    public function getStatuses()
    {
        return $this->fetchStatuses();
    }


    public function findStatusById($statusId)
    {
        $statuses = $this->fetchStatuses();

        if (isset($statuses[$statusId])) {
            return $statuses[$statusId];
        }

        throw new Exception("Status with ID $statusId not found.");
    }

    public function getActiveStatuses()
    {
        $this->filterActiveStatuses();
        return $this->activeStatuses;
    }
}