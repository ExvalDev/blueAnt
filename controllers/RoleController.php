<?php

require_once 'RequestController.php';

class RoleController
{
    private $requestController;
    private $config;
    private static $roles = null;

    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    public function getRoles()
    {
        try {
            if (self::$roles === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['roles']);
                if (!isset($response['roles'])) {
                    throw new Exception('Roles not found in API response');
                }
                self::$roles = $response['roles'];
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
            $response = $this->requestController->get($endpoint);
            if (!isset($response['role'])) {
                throw new Exception('Role not found in API response');
            }
            return $response['role'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }
}