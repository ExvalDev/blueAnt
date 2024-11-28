<?php
try {
    include 'components/header.php';
    include 'components/list.php';
    include 'components/listElement.php';
    require 'services/ProjectService.php';
    require 'services/StatusService.php';
    require 'services/PriorityService.php';
    require 'services/DepartmentService.php';
    require 'services/TypeService.php';
    require 'utilities/helpers.php';

    // Initialize services
    $projectService = new ProjectService();
    $statusService = new StatusService();
    $priorityService = new PriorityService();
    $departmentService = new DepartmentService();
    $typeService = new TypeService();

    // Fetch all necessary data
    $statuses = $statusService->getStatuses();
    $activeStatuses = $statusService->getActiveStatuses();
    $priorities = $priorityService->getPriorities();
    $departments = $departmentService->getDepartments();
    $types = $typeService->getTypes();
    $activeStatusIds = extractIds($activeStatuses);

    // Get the selected filters
    $selectedStatusId = isset($_GET['status']) ? intval($_GET['status']) : null;
    $selectedDepartmentId = isset($_GET['department']) ? intval($_GET['department']) : null;
    $selectedTypeId = isset($_GET['type']) ? intval($_GET['type']) : null;

    // Filter projects based on the selected filters
    if ($selectedStatusId || $selectedDepartmentId || $selectedTypeId) {
        $filter = buildFilters([
            'statusId' => $selectedStatusId == null ? $activeStatusIds : [$selectedStatusId],
            'departmentId' => [$selectedDepartmentId],
            'typeId' => [$selectedTypeId]
        ]);
        $projects = $projectService->getProjectsFiltered($filter);
    } else {
        $projects = $projectService->getProjectsFiltered(['statusId' => $activeStatusIds]);
    }
    ?>
<main>
    <div class="content-index">
        <header class="topbar d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2 align-items-center">
                <?php
                    // Get the current URL with all query parameters
                    $currentUrl = $_SERVER['REQUEST_URI'];
                    ?>
                <a href="<?= htmlspecialchars($currentUrl); ?>" class="icon" title="Aktualisieren"
                    data-bs-toggle='tooltip' data-bs-placement='top'>
                    <i class="bi bi-arrow-repeat"></i>
                </a>
                <h1>Projektübersicht</h1>
                <h1>(<?= count($projects) ?>)</h1>
            </div>
            <div class="d-flex gap-2 align-items-center jusifiy-content-end">
                <form method="GET" action="" class="d-flex align-items-center jusifiy-content-end">
                    <div class="row d-flex justify-content-end">
                        <div class="col-4">
                            <select class="form-select" name="status" aria-label="Filter Status"
                                onchange="this.form.submit()">
                                <option value="" <?= empty($_GET['status']) ? 'selected' : '' ?>>Status</option>
                                <?php
                                    foreach ($activeStatuses as $status) {
                                        $selected = (isset($_GET['status']) && $_GET['status'] == $status['id']) ? 'selected' : '';
                                        echo "<option value='{$status['id']}' $selected>{$status['text']}</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col-3"><select class="form-select" name="department" aria-label="filter Department"
                                onchange="this.form.submit()">
                                <option value="" <?= empty($_GET['department']) ? 'selected' : '' ?>>Abteilung</option>
                                <?php
                                    foreach ($departments as $department) {
                                        $selected = (isset($_GET['department']) && $_GET['department'] == $department['id']) ? 'selected' : '';
                                        echo "<option value='{$department['id']}' $selected>{$department['text']}</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <select class="form-select" name="type" aria-label="filter Type"
                                onchange="this.form.submit()">
                                <option value="" <?= empty($_GET['type']) ? 'selected' : '' ?>>Type</option>
                                <?php
                                    foreach ($types as $type) {
                                        $selected = (isset($_GET['type']) && $_GET['type'] == $type['id']) ? 'selected' : '';
                                        echo "<option value='{$type['id']}' $selected>{$type['description']}</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                </form>
                <a href="/" class="btn-icon" title="Filter zurücksetzen" data-bs-toggle='tooltip'
                    data-bs-placement='top'>
                    <i class="bi bi-x-square"></i>
                </a>
            </div>

        </header>
        <?php include 'components/listHeader.php'; ?>
        <?= renderList($projects, $statusService, $priorityService); ?>
    </div>
</main>

<?php
    include 'components/footer.php';
} catch (Exception $e) {
    echo '<p class="text-danger text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>