<?php
try {
    include 'components/header.php';
    include 'components/list.php';
    include 'components/listElement.php';

    require 'services/ProjectService.php';
    require_once 'services/StatusService.php';
    require_once 'services/DepartmentService.php';
    require_once 'services/ProjectTypeService.php';

    require 'utilities/helpers.php';

    // Get the selected filters
    $selectedStatusId = isset($_GET['status']) ? intval($_GET['status']) : null;
    $selectedDepartmentId = isset($_GET['department']) ? intval($_GET['department']) : null;
    $selectedTypeId = isset($_GET['type']) ? intval($_GET['type']) : null;

    // Initialize services
    $projectService = new ProjectService();
    $statusService = new StatusService();
    $departmentService = new DepartmentService();
    $projectTypeService = new ProjectTypeService();

    $projects = $projectService->getProjectsFiltered($selectedStatusId, $selectedDepartmentId, $selectedTypeId);
    $activeStatuses = $statusService->getActiveStatuses();

    // Now $activeStatuses is sorted alphabetically by getName()

    $departments = $departmentService->getDepartments();

    // Sort the array alphabetically based on the getName() method
    usort($departments, function($departmentA, $departmentB) {
        // Get the name from both status objects
        $nameA = $departmentA->getName();
        $nameB = $departmentB->getName();
        
        // Compare the names alphabetically
        return strcmp($nameA, $nameB);
    });

    $projectTypes = $projectTypeService->getProjectTypes();

    // Sort the array alphabetically based on the getName() method
    usort($projectTypes, function($projectTypesA, $projectTypesB) {
        // Get the name from both status objects
        $nameA = $projectTypesA->getName();
        $nameB = $projectTypesB->getName();
        
        // Compare the names alphabetically
        return strcmp($nameA, $nameB);
    });

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
                <div class="d-flex gap-2 align-items-center jusifiy-content-end filterMobileContainer">
                    <form method="GET" action="" class="d-flex align-items-center jusifiy-content-end">
                        <div class="row d-flex justify-content-end filterMobile">
                            <div class="col-12 col-sm-4 ">
                                <select class="form-select" name="status" aria-label="Filter Status"
                                    onchange="this.form.submit()">
                                    <option value="" <?= empty($_GET['status']) ? 'selected' : '' ?>>Status</option>
                                    <?php
                                    foreach ($activeStatuses as $status) {
                                        $selected = (isset($_GET['status']) && $_GET['status'] == $status->getId()) ? 'selected' : '';
                                        echo "<option value='{$status->getId()}' $selected>{$status->getName()}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-3 "><select class="form-select" name="department" aria-label="filter Department"
                                    onchange="this.form.submit()">
                                    <option value="" <?= empty($_GET['department']) ? 'selected' : '' ?>>Abteilung</option>
                                    <?php
                                    foreach ($departments as $department) {
                                        $selected = (isset($_GET['department']) && $_GET['department'] == $department->getId()) ? 'selected' : '';
                                        echo "<option value='{$department->getId()}' $selected>{$department->getName()}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-3">
                                <select class="form-select" name="type" aria-label="filter Type"
                                    onchange="this.form.submit()">
                                    <option value="" <?= empty($_GET['type']) ? 'selected' : '' ?>>Type</option>
                                    <?php
                                    foreach ($projectTypes as $type) {
                                        $selected = (isset($_GET['type']) && $_GET['type'] == $type->getId()) ? 'selected' : '';
                                        echo "<option value='{$type->getId()}' $selected>{$type->getName()}</option>";
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
            <?php echo renderList($projects) ?>
        </div>
    </main>

    <?php
    include 'components/footer.php';
} catch (Exception $e) {
    echo '<p class="text-danger text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>