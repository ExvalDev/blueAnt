<?php

require_once 'RequestService.php';

class TypeService
{
    private $requestService;
    private $config;
    private static $types = null;

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    private function fetchTypes()
    {
        try {
            if (self::$types === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['types']);
                if (!isset($response['types'])) {
                    throw new Exception('Types not found in API response');
                }
                self::$types = [];
                foreach ($response['types'] as $type) {
                    self::$types[$type['id']] = $type;
                }
            }

            return self::$types;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }

    public function getTypeById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['type']);
            $response = $this->requestService->get($endpoint);
            if (!isset($response['type'])) {
                throw new Exception('Type not found in API response');
            }
            return $response['type'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    // Get all cached types
    public function getTypes()
    {
        return $this->fetchTypes();
    }


    public function findTypeById($typeId)
    {
        $types = $this->fetchTypes();

        if (isset($types[$typeId])) {
            return $types[$typeId];
        }

        throw new Exception("Type with ID $typeId not found.");
    }
}