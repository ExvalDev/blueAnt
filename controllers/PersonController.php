<?php
/**
 * Controller to handle API Requests for Person
 */
require_once 'RequestController.php';

class PersonController
{
    private $requestController;
    private $config;
    private static $persons = null;

    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    public function getPersons()
    {
        try {
            if (self::$persons === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['persons']);
                if (!isset($response['persons']) || !is_array($response['persons'])) {
                    throw new Exception('Invalid API response: Persons not found');
                }
                self::$persons = $response['persons'];
            }
            return self::$persons;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e; // Re-throw the exception for upstream handling
        }
    }

    public function getPersonById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['person']);
            $response = $this->requestController->get($endpoint);
            if (!isset($response['person'])) {
                throw new Exception("Person with ID $id not found in API response");
            }
            return $response['person'];
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e; // Re-throw the exception for upstream handling
        }
    }
}