<?php
/**
 * Model for Department
 */
class Department
{
    private int $id;
    private string $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): Department
    {
        $this->id = $id;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName($name): Department
    {
        $this->name = $name;
        return $this;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->name;
    }
}