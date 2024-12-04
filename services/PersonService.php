<?php

require 'models/Person.php';
require 'controllers/PersonController.php';

class PersonService
{
    private $personController;
    private static $persons = null;

    public function __construct()
    {
        $this->personController = new PersonController();
    }

    public function getPersons(): array
    {
        if (self::$persons === null) {
            self::$persons = [];
            $response = $this->personController->getPersons();
            foreach ($response as $person) {
                self::$persons[$person['id']] = new Person(
                    $person['id'],
                    $person['firstname'],
                    $person['lastname'],
                    $person['email']
                );
            }
        }
        return self::$persons;
    }

    public function getPersonById(string $id): Person
    {
        $personData = $this->personController->getPersonById($id);
        return new Person(
            $personData['id'],
            $personData['firstname'],
            $personData['lastname'],
            $personData['email']
        );
    }

    public function findPersonById(int $personId): Person|null
    {
        $persons = $this->getPersons();

        if (isset($persons[$personId])) {
            return $persons[$personId];
        }

        error_log("[PersonService] Person with ID $personId not found.");
        return null;
    }
}