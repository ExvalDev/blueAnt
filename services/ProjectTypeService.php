<?php

require 'models/ProjectType.php';
require 'controllers/ProjectTypeController.php';

class ProjectTypeService
{
    private $projectTypeController;
    private static $projectTypes = null;

    public function __construct()
    {
        $this->projectTypeController = new ProjectTypeController();
    }

    public function getProjectTypes(): array
    {
        self::$projectTypes = [];
        $response = $this->projectTypeController->getProjectTypes();
        foreach ($response as $projectType) {
            self::$projectTypes[$projectType['id']] = new ProjectType($projectType['id'], $projectType['description']);
        }
        return self::$projectTypes;
    }

    public function getProjectTypeById(string $id): ProjectType
    {
        $projectType = $this->projectTypeController->getProjectTypeById($id);
        return new ProjectType($projectType['id'], $projectType['description']);
    }

    public function findProjectTypeById($projectTypeId)
    {
        $projectTypes = $this->getProjectTypes();

        if (isset($projectTypes[$projectTypeId])) {
            return $projectTypes[$projectTypeId];
        }

        throw new Exception("ProjectType with ID $projectTypeId not found.");
    }
}