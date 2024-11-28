<?php

require_once 'RequestService.php';

class RoleService
{
    private $requestService;
    private $config;
    private static $roles = null;

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    private function fetchRoles()
    {
        try {
            if (self::$roles === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['roles']);
                if (!isset($response['roles'])) {
                    throw new Exception('Roles not found in API response');
                }
                self::$roles = [];
                foreach ($response['roles'] as $role) {
                    self::$roles[$role['id']] = $role;
                }
            }

            return self::$roles;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getRoleById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['role']);
            $response = $this->requestService->get($endpoint);
            if (!isset($response['role'])) {
                throw new Exception('Role not found in API response');
            }
            return $response['role'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }

    // Get all cached roles
    public function getRoles()
    {
        return $this->fetchRoles();
    }


    public function findRoleById($roleId)
    {
        $roles = $this->fetchRoles();

        if (isset($roles[$roleId])) {
            return $roles[$roleId];
        }

        throw new Exception("Role with ID $roleId not found.");
    }
}