<?php

require_once 'RequestService.php';

class ProjectService
{
    private $requestService;
    private $config;
    private static $projects = null;
    private static $activeProjects = [];

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    private function fetchProjects()
    {
        if (self::$projects === null) {
            $response = $this->requestService->get($this->config['apiEndpoints']['projects']);
            self::$projects = $response["projects"];

        }

        return self::$projects;
    }

    public function getProjectsFiltered(array $filters = []): array
    {
        $projects = $this->fetchProjects();

        // Apply filters dynamically
        $filteredProjects = array_filter($projects, function ($project) use ($filters) {
            foreach ($filters as $key => $filterValue) {
                // If the project doesn't have the key or doesn't match the filter, exclude it
                if (!isset($project[$key])) {
                    return false;
                }

                if (is_array($filterValue)) {
                    // Check if the project's value is in the filter array
                    if (!in_array($project[$key], $filterValue, true)) {
                        return false;
                    }
                } else {
                    // Check for direct equality
                    if ($project[$key] !== $filterValue) {
                        return false;
                    }
                }
            }
            return true; // Include the project if all filters match
        });

        return $filteredProjects;
    }

    public function getProjectById($id)
    {
        $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['project']);

        try {
            $response = $this->requestService->get($endpoint);

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

    // Get all cached statuses
    public function getProjects()
    {
        return $this->fetchProjects();
    }


    public function findProjectById($projectId)
    {
        $statuses = $this->fetchProjects();

        if (isset($statuses[$projectId])) {
            return $statuses[$projectId];
        }

        throw new Exception("Project with ID $projectId not found.");
    }
}