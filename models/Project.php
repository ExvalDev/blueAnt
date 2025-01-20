<?php
/**
 * Model for Project
 */
class Project
{
    private int $id;

    private string $projectNumber;

    private string $name;
    private string $startDate;
    private string $endDate;

    private Status $status;
    private Person|null $projectLeader;
    private Role $projectLeaderRole;
    private Department $department;
    private Priority $priority;
    private ProjectType $type;
    private array $customers;

    private $subjectMemo;

    private $objectiveMemo;


    private array $planningEntries;


    private array $customFields;



    public function __construct(
        int $id,
        string $projectNumber,
        string $name,
        string $startDate,
        string $endDate,
        Status $status,
        Person|null $person,
        Role $role,
        Department $department,
        Priority $priority,
        ProjectType $type,

        string $subjectMemo = "",
        string $objectiveMemo = "",
        array $customers = [],
        array $customFields = [],
        array $planningEntries =[], 

    ) {
        $this->id = $id;
        $this->projectNumber = $projectNumber;
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->projectLeader = $person;
        $this->projectLeaderRole = $role;
        $this->department = $department;
        $this->priority = $priority;
        $this->type = $type;
        $this->subjectMemo = $subjectMemo;
        $this->customers = $customers;
        $this->objectiveMemo = $objectiveMemo;
        $this->customers = $customers;
        $this->customFields = $customFields;
        $this->planningEntries = $planningEntries;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getProjectNumber()
    {
        return $this->projectNumber;
    }

    public function setProjectNumber($projectNumber)
    {
        $this->projectNumber = $projectNumber;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }
    public function getEndDate()
    {
        return $this->endDate;
    }
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    public function getProjectLeader()
    {
        return $this->projectLeader;
    }
    public function setProjectLeader($projectLeader)
    {
        $this->projectLeader = $projectLeader;
        return $this;
    }
    public function getProjectLeaderRole()
    {
        return $this->projectLeaderRole;
    }
    public function setProjectLeaderRole($projectLeaderRole)
    {
        $this->projectLeaderRole = $projectLeaderRole;
        return $this;
    }
    public function getDepartment()
    {
        return $this->department;
    }
    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }
    public function getPriority()
    {
        return $this->priority;
    }
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    public function getCustomers()
    {
        return $this->customers;
    }
    public function setCustomers($customers)
    {
        $this->customers = $customers;
    }
    public function getCustomFields()
    {
        return $this->customFields;
    }
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;
        return $this;
    }

    public function getSubjectmemo()
    {
        return $this->subjectMemo;
    }

    public function setSubjectmemo($subjectMemo)
    {
        $this->subjectMemo = $subjectMemo;
        return $this;
    }


    public function getObjectivememo()
    {
        return $this->objectiveMemo;
    }
    public function setObjectivememo($objectiveMemo)
    {
        $this->objectiveMemo = $objectiveMemo;
        return $this;
    }

    public function getPlanningEntries()
    {
        return $this->planningEntries;
    }
    public function setPlanningEntries($planningEntries){
        $this->planningEntries = $planningEntries;
        return $this;
    }

    public function __toString(): string
    {
        return strval($this->id) . '  | ' . $this->name . ' | ' . $this->startDate . ' | ' . $this->endDate . ' | ' . $this->status . ' | ' . $this->projectLeader . ' | ' . $this->projectLeaderRole . ' | ' . $this->department . ' | ' . $this->priority . ' | ' . $this->type;
    }

}