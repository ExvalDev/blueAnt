<?php

require 'models/Project.php';
require 'controllers/ProjectController.php';

require 'services/StatusService.php';
require 'services/PersonService.php';
require 'services/RoleService.php';
require 'services/DepartmentService.php';
require 'services/PriorityService.php';
require 'services/ProjectTypeService.php';
require 'services/CustomFieldService.php';
require 'services/CustomerService.php';
require 'services/PlanningEntryService.php';



class ProjectService //implements serviceInterface
{
    private $projectController;
    private $statusService;
    private $personService;
    private $roleService;
    private $departmentService;
    private $priorityService;
    private $projectTypeService;
    private $customerService;
    private $customFieldService;

    private $planningEntryService;
    private static $projects = null;

    public function __construct()
    {
        $this->projectController = new ProjectController();
        $this->statusService = new StatusService();
        $this->personService = new PersonService();
        $this->roleService = new RoleService();
        $this->departmentService = new DepartmentService();
        $this->priorityService = new PriorityService();
        $this->projectTypeService = new ProjectTypeService();
        $this->customFieldService = new CustomFieldService();
        $this->customerService = new CustomerService();
        $this->planningEntryService = new PlanningEntryService();
    }

    public function getProjects(bool $refresh = false): array
    {
        if (self::$projects === null || $refresh) {
            self::$projects = [];
            $response = $this->projectController->getProjects();

        
            foreach ($response as $project) {
                self::$projects[$project['id']] = new Project(
                    $project['id'],
                    $project['name'],
                    $project['start'],
                    $project['end'],
                    $this->statusService->findStatusById($project['statusId']),
                    $this->personService->findPersonById($project['projectLeaderId']),
                    $this->roleService->findRoleById($project['projectLeaderRoleId']),
                    $this->departmentService->findDepartmentById($project['departmentId']),
                    $this->priorityService->findPriorityById($project['priorityId']),
                    $this->projectTypeService->findProjectTypeById($project['typeId']),
                    $project['subjectMemo'] ?? "",
                    $project['objectiveMemo'] ?? "",
                    $this->customerService->getCustomersFromProjectClients(projectClients: $project['clients']),
                    $this->customFieldService->getCustomFieldsOfProject($project['customFields'] ?? []),


                );
            }
        }
        return self::$projects;
    }

    private function filterProjects(array $projects, array $filter): array
    {
        $projects = array_filter($projects, function ($project) use ($filter) {
            foreach ($filter as $key => $filterValue) {
                // Skip filter if value is null
                if ($filterValue === null || $filterValue === [null]) {
                    continue;
                }

                // Split nested keys (e.g., 'status.id' -> ['status', 'id'])
                $keyParts = explode('.', $key);
                $value = $project;

                // Dynamically resolve nested getters
                foreach ($keyParts as $part) {
                    $getter = 'get' . ucfirst($part);

                    if (!method_exists($value, $getter)) {
                        return false; // Exclude if getter doesn't exist
                    }

                    $value = $value->$getter(); // Call the getter
                }

                if (is_array($filterValue)) {
                    if (!in_array($value, $filterValue, true)) {
                        return false; // Exclude if value is not in the filter array
                    }
                } else {
                    if ($value !== $filterValue) {
                        return false; // Exclude if value doesn't match
                    }
                }
            }
            return true; // Include the project if all filters match
        });

        

        return $projects;

    }

    public function getProjectsFiltered($selectedStatusId, $selectedDepartmentId, $selectedTypeId): array
    {
        try {
            $projects = $this->getProjects();

            $statuses = $this->statusService->getActiveStatuses();
            $activeStatusIds = extractIds($statuses);

            $filter = buildFilters([
                'status.id' => $selectedStatusId === null ? $activeStatusIds : [$selectedStatusId],
                'department.id' => [$selectedDepartmentId],
                'type.id' => [$selectedTypeId]
            ]);

            return $this->filterProjects($projects, $filter);
        } catch (Exception $e) {
            error_log("Error filtering projects: " . $e->getMessage());
            return []; // Return an empty array on failure
        }
    }

    public function getProjectById($id)
    {
        $project = $this->projectController->getProjectById($id);

        return new Project(
            $project['id'],
            $project['name'],
            $project['start'],
            $project['end'],
            $this->statusService->getStatusById($project['statusId']),
            $this->personService->getPersonById($project['projectLeaderId']),
            $this->roleService->getRoleById($project['projectLeaderRoleId']),
            $this->departmentService->getDepartmentById($project['departmentId']),
            $this->priorityService->getPriorityById($project['priorityId']),
            $this->projectTypeService->getProjectTypeById($project['typeId']),
            $project['subjectMemo'] ?? "",
            $project['objectiveMemo'] ?? "",
            $this->customerService->getCustomersFromProjectClients($project['clients']),
            $this->customFieldService->getCustomFieldsOfProject($project['customFields'] ?? []),
            $this->planningEntryService->getPlanningEntriesByProjectId($project['id'])

        );
    }

    // Available Array Keys
    // id,currencyId,departmentId,typeId,statusId,clients,priorityId,projectLeaderId,projectLeaderRoleId,name,number,costCentreNumber,
    // isTemplate,isArchived,customFields,planningType,billingType,revenueEvaluation,start,end


    public function findProjectById($projectId): ?Project
    {
        $projects = $this->getProjects();

        if (isset($projects[$projectId])) {
            return $projects[$projectId];
        }

        error_log("Project with ID $projectId not found.");
        return null; // Return null instead of throwing an exception
    }
}