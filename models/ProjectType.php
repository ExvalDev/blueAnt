<?php
/**
 * Model for ProjectType
 */
class ProjectType
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
    public function setId($id): ProjectType
    {
        $this->id = $id;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName($name): ProjectType
    {
        $this->name = $name;
        return $this;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->name;
    }
}