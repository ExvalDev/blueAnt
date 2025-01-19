<?php

/**
 * Model for Customer
 */
class Customer
{
    private int $id;
    private string $name;

    private CustomerType $type;

    public function __construct(int $id, string $name, CustomerType $type)
    {

        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): Customer
    {
        $this->id = $id;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): Customer
    {
        $this->name = $name;
        return $this;
    }
    public function getType(): CustomerType
    {
        return $this->type;
    }
    public function setType(CustomerType $type): Customer
    {
        $this->type = $type;
        return $this;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->name . ' | ' . $this->type;
    }

}