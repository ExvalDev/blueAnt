<?php

require_once 'RequestService.php';

class PersonService
{
    private $requestService;
    private $config;
    private static $persons = null;

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    private function fetchPersons()
    {
        try {
            if (self::$persons === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['persons']);
                if (!isset($response['persons'])) {
                    throw new Exception('Persons not found in API response');
                }
                self::$persons = [];
                foreach ($response['persons'] as $person) {
                    self::$persons[$person['id']] = $person;
                }
            }
            return self::$persons;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

    }

    public function getPersonById($id)
    {
        try {
            $endpoint = str_replace('{id}', $id, $this->config['apiEndpoints']['person']);
            $response = $this->requestService->get($endpoint);
            if (!isset($response['person'])) {
                throw new Exception('Person not found in API response');
            }
            return $response['person'];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }


    }

    // Get all cached persons
    public function getPersons()
    {
        return $this->fetchPersons();
    }


    public function findPersonById($personId)
    {
        $persons = $this->fetchPersons();

        if (isset($persons[$personId])) {
            return $persons[$personId];
        }

        throw new Exception("Person with ID $personId not found.");
    }
}