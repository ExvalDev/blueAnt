<?php

class CustomerType
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): CustomerType
    {
        $this->id = $id;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): CustomerType
    {
        $this->name = $name;
        return $this;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->name;
    }

}