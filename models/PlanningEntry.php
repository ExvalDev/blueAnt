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
}

?>