<?php

require_once 'RequestController.php';

class ProjectController
{
    private $requestController;
    private $config;
    private static $projects = null;
    private static $activeProjects = [];

    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    public function getProjects()
    {
        $config = $this->config['apiEndpoints']['projects'];
        $params = array(
            'includeMemoFields' => 'true'
        );
        if (self::$projects === null) {

            $response = $this->requestController->get($config, $params);
            self::$projects = $response["projects"];

        }

        return self::$projects;
    }

    public function getProjectById($id)
    {
        $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['project']);

        try {
            $response = $this->requestController->get($endpoint);

            // Handle successful response
            if ($response['status']['code'] === 200) {
                return $response['project'];
            }
            if ($response['status']['code'] === 404) {
                throw new Exception("Projekt $id wurde nicht gefunden.");
            }

            // Handle other unexpected errors
            throw new Exception("Unexpected error: " . ($response['status']['message'] ?? 'Unknown error'));

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }
}