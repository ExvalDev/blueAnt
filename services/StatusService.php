<?php

require 'models/Status.php';
require 'controllers/StatusController.php';

class StatusService
{
    private $statusController;
    private static $activePhases = [2, 3];
    private static $statuses = null;
    private static $activeStatuses = null;

    public function __construct()
    {
        $this->statusController = new StatusController();
    }

    public function getStatuses(): array
    {
        self::$statuses = [];
        $response = $this->statusController->getStatuses();
        foreach ($response as $status) {
            self::$statuses[$status['id']] = new Status($status['id'], $status['text'], $status['phase']);
        }
        return self::$statuses;
    }

    public function getStatusById(string $id): Status
    {
        $status = $this->statusController->getStatusById($id);

        return new Status($status['id'], $status['text'], $status['phase']);
    }

    private function filterActiveStatuses(): array
    {
        if (empty(self::$activeStatuses)) {
            // Filter statuses and keep the whole object
            self::$activeStatuses = array_filter(self::$statuses, function ($status) {
                // Use a getter method or a public property to access 'phase'
                return $status->getPhase() !== null && in_array($status->getPhase(), self::$activePhases, true);
            });
        }

        return self::$activeStatuses;
    }

    public function getActiveStatuses(): array
    {
        return $this->filterActiveStatuses();
    }

    public function findStatusById($statusId): Status
    {
        $statuses = $this->getStatuses();

        if (isset($statuses[$statusId])) {
            return $statuses[$statusId];
        }

        throw new Exception("Status with ID $statusId not found.");
    }
}