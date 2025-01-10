<?php
class PlanningEntry{



    private int $id;
    private string $description;
    private string $startDate;
    private string $endDate;
    public function __construct(int $id, string $description, string $startDate, string $endDate)
    {
        $this->id = $id;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): PlanningEntry
    {
        $this->id = $id;
        return $this;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): PlanningEntry
    {
        $this->description = $description;
        return $this;
    }

    public function getStartDate(string $description)
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): PlanningEntry
    {
        $this->startDate = $startDate;
        return $this;
    }

   
    public function getEndDate()
    {
        return $this->endDate;
    }
    public function setEndDate(string $endDate): PlanningEntry
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->name;
    }
}

?>