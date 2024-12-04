<?php

require 'models/Role.php';
require 'controllers/RoleController.php';

class RoleService
{
    private $roleController;
    private static $roles = null;

    public function __construct()
    {
        $this->roleController = new RoleController();
    }

    public function getRoles(): array
    {
        self::$roles = [];
        $response = $this->roleController->getRoles();
        foreach ($response as $role) {
            self::$roles[$role['id']] = new Role($role['id'], $role['text']);
        }
        return self::$roles;
    }

    public function getRoleById(string $id): Role
    {
        $role = $this->roleController->getRoleById($id);
        return new Role($role['id'], $role['text']);
    }

    public function findRoleById($roleId)
    {
        $roles = $this->getRoles();

        if (isset($roles[$roleId])) {
            return $roles[$roleId];
        }

        throw new Exception("Role with ID $roleId not found.");
    }
}