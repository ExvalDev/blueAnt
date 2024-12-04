<?php

require 'models/Priority.php';
require 'controllers/PriorityController.php';

class PriorityService
{
    private $priorityController;
    private static $priorities = null;

    public function __construct()
    {
        $this->priorityController = new PriorityController();
    }

    private function getPriorities(): array
    {
        self::$priorities = [];
        $response = $this->priorityController->getPriorities();
        foreach ($response as $priority) {
            self::$priorities[$priority['id']] = new Priority($priority['id'], $priority['text']);
        }
        return self::$priorities;
    }

    public function getPriorityById(string $id): Priority
    {
        $priority = $this->priorityController->getPriorityById($id);
        return new Priority($priority['id'], $priority['text']);
    }

    public function findPriorityById($priorityId)
    {
        $priorities = $this->getPriorities();

        if (isset($priorities[$priorityId])) {
            return $priorities[$priorityId];
        }

        throw new Exception("[PriorityService] Priority with ID $priorityId not found.");
    }
}