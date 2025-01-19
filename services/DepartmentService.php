<?php

/**
 * Service class to handle Department actions
 */

require 'models/Department.php';
require 'controllers/DepartmentController.php';


class DepartmentService //implements serviceInterface
{
    private $departmentController;
    private static $departments = null;

    public function __construct()
    {
        $this->departmentController = new DepartmentController();
    }

    public function getDepartments(): array
    {
        self::$departments = [];
        $response = $this->departmentController->getDepartments();
        foreach ($response as $department) {
            self::$departments[$department['id']] = new Department($department['id'], $department['text']);
        }
        return self::$departments;
    }

    public function getDepartmentById(string $id): Department
    {
        $department = $this->departmentController->getDepartmentById($id);
        return new Department($department['id'], $department['text']);
    }

    public function findDepartmentById($departmentId)
    {
        $departments = $this->getDepartments();

        if (isset($departments[$departmentId])) {
            return $departments[$departmentId];
        }

        throw new Exception("Department with ID $departmentId not found.");
    }
}