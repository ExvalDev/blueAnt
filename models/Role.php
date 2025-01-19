<?php
/**
 * Model for Role
 */
class Role
{
    private int $id;
    private string $title;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): Role
    {
        $this->id = $id;
        return $this;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): Role
    {
        $this->title = $title;
        return $this;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->title;
    }
}