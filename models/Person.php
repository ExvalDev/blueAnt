<?php

class Person
{
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;

    public function __construct(int $id, string $firstname, string $lastname, string $email)
    {

        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): Person
    {
        $this->id = $id;
        return $this;
    }
    public function getFirstname(): string
    {
        return $this->firstname;
    }
    public function setFirstname(string $firstname): Person
    {
        $this->firstname = $firstname;
        return $this;
    }
    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): Person
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): Person
    {
        $this->email = $email;
        return $this;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->firstname . ' | ' . $this->lastname;
    }

}