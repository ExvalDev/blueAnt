<?php
include 'components/header.php';
require 'components/error.php';
require 'services/ProjectService.php';
require 'utilities/helpers.php';
require_once 'components/backButton.php';
require_once 'components/customField.php';
require_once 'utilities/Phases.php';

// Initialize services
$projectService = new ProjectService();

try {
    $projectId = intval(getQueryParam('projectId'));
    $project = $projectService->getProjectById($projectId);
} catch (Exception $e) {
    echo renderError($e->getMessage());
    exit;
}

$phaseEnum = Phase::tryFrom($project->getStatus()->getPhase());
$phaseColor = $phaseEnum->getColor();
?>

<main>
    <div class="content-project">
        <header class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2 align-items-center">
                <?php echo renderBackButton(); ?>
            </div>
        </header>
        <div class="details-header container d-flex align-items-start justify-content-between p-3">
            <h5 class="mb-0"><?php echo $project->getName(); ?></h5>
            <div class="d-flex flex-row-reverse gap-2 justify-content-center align-items-center">
                <span class="badge rounded-pill badge-primary fw-bold" data-bs-toggle='tooltip' data-bs-placement='top'
                    title='Projekt-ID'><?php echo $projectId; ?></span>
                <p class="text-truncate" data-bs-toggle='tooltip' data-bs-placement='top' title='Projektart'>
                    <?php echo $project->getType()->getName(); ?>
                </p>
            </div>
        </div>
        <div class="grid">
            <div class="tile p-3">
                <h2>Details</h2>
                <div>
                    <h3>Projektzeitraum</h3>
                    <p class="text-truncate"><?php echo transformDate($project->getStartDate()); ?> -
                        <?php echo transformDate($project->getEndDate()); ?>
                    </p>
                </div>
            </div>
            <div class="tile p-3">
                <div>
                    <h3>Status</h3>
                    <span class='badge rounded-pill text-truncate' style='background-color: <?php echo $phaseColor; ?>;'
                        data-bs-toggle='tooltip' data-bs-placement='top'
                        title='Status - Phase <?php echo $project->getStatus()->getPhase(); ?>'>
                        <?php echo $project->getStatus()->getName(); ?>
                    </span>
                </div>
                <div>
                    <h3>Priorität</h3>
                    <span class='badge rounded-pill bg-secondary' data-bs-toggle='tooltip' data-bs-placement='top'
                        title='Priorität'><?php echo $project->getPriority()->getName(); ?></span>
                </div>
                <div>
                    <h3>Abteilung</h3>
                    <span class='badge rounded-pill bg-secondary' data-bs-toggle='tooltip' data-bs-placement='top'
                        title='Abteilung'><?php echo $project->getDepartment()->getName(); ?></span>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="tile p-3 h-50">
                    <h3><?= $project->getProjectLeaderRole()->getTitle() ?></h3>
                    <div class="row">
                        <div class="col-3">
                            <input class="form-control" type="text"
                                value="<?= $project->getProjectLeader()->getFirstname() ?>" disabled>
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text"
                                value="<?= $project->getProjectLeader()->getLastname() ?>" disabled>
                        </div>
                        <div class="col-6">
                            <input class="form-control" type="text"
                                value="<?= $project->getProjectLeader()->getEmail() ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="tile p-3 h-50">
                    <h3>Kunde</h3>
                    <div class="d-flex flex-row gap-2">
                        <?php
                        if (count($project->getCustomers()) > 0) {
                            foreach ($project->getCustomers() as $customer) {
                                $customerType = $customer->getType()->getName();
                                $customerName = $customer->getName();
                                echo "
                                        <div>
                                            <h4 class='mb-2'>{$customerType}</h4>
                                            <input class='form-control' type='text' value='{$customerName}' disabled>
                                        </div>
                                    ";
                            }
                        } else {
                            echo "<p>Keine Kunden gefunden</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-fields p-3 d-flex flex-column gap-3">
            <h2>Benutzerdefinierte Felder</h2>
            <div class="d-flex flex-column gap-2">
                <?php foreach ($project->getCustomFields() as $customField) {
                    echo renderCustomField($customField);
                } ?>
            </div>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>