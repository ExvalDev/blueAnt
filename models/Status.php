<?php
/**
 * Model for Status
 */
class Status
{
    private int $id;
    private string $name;
    private int $phase;
    public function __construct($id, $name, int $phase)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phase = $phase;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getPhase()
    {
        return $this->phase;
    }
    public function setPhaseId($phase)
    {
        $this->phaseId = $phase;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->name . ' | ' . $this->phase;
    }
}