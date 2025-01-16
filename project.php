<?php
include 'components/header.php';
require 'components/error.php';
require 'services/ProjectService.php';
require 'utilities/helpers.php';
require_once 'components/backButton.php';
require_once 'components/customField.php';
require_once 'utilities/Phases.php';
require_once 'utilities/Priorities.php';

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

$priorityEnum = Priorities::tryFrom($project->getPriority()->getId());
$priorityColor = $priorityEnum->getColor();

?>

<main>
        <div class="content-project">
            <header class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2 align-items-center">
                    <?php echo renderBackButton(); ?>
                </div>
            </header>
            <div class="details-header d-flex align-items-start justify-content-between p-3">
                <h1 class="mb-0"><?php echo $project->getName(); ?></h1>
                <div class="d-flex flex-row-reverse gap-2 justify-content-center align-items-center">
                <span class="badge rounded-pill badge-primary fw-bold" data-bs-toggle='tooltip' data-bs-placement='top'
                      title='Projekt-ID'><?php echo $projectId; ?></span>
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
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Abteilung</h3>
                            <span class="badge rounded-pill badge-primary fw-bold" data-bs-toggle='tooltip'
                                  data-bs-placement='top'
                                  title='Abteilung'><?php echo $project->getDepartment()->getName(); ?></span>
                        </div>
                        <div class="col-md-6">
                            <h3>Type</h3>
                            <span class='badge rounded-pill bg-secondary' data-bs-toggle='tooltip'
                                  data-bs-placement='top'
                                  title='Type'><?php echo $project->getType()->getName(); ?></span>
                        </div>
                    </div>
                </div>
                <div class="tile p-3">
                    <div>
                        <h2>Status</h2>
                        <span class='badge rounded-pill text-truncate'
                              style='background-color: <?php echo $phaseColor; ?>;'
                              data-bs-toggle='tooltip' data-bs-placement='top'
                              title='Status - Phase <?php echo $project->getStatus()->getPhase(); ?>'>
                        <?php echo $project->getStatus()->getName(); ?>
                    </span>
                    </div>
                    <div>
                        <h2>Priorität</h2>
                        <span class='badge rounded-pill' style='background-color: <?php echo $priorityColor; ?>;' data-bs-toggle='tooltip' data-bs-placement='top'
                              title='Priorität'><?php echo $project->getPriority()->getName(); ?></span>
                    </div>

                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="tile p-3">
                        <h2><?= $project->getProjectLeaderRole()->getTitle() ?></h2>
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
                    <div class="tile p-3">
                        <h2>Kunde</h2>
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
            <!-- Projekziel -->
            <div class="custom-fields p-3 d-flex flex-column gap-3">
                <h2>Ziel</h2>
                <div class="d-flex flex-column gap-2">
                    <?=strip_tags(string: $project->getObjectivememo());?>
                </div>
            </div>

            <!-- Projektgegenstand -->
            <div class="custom-fields p-3 d-flex flex-column gap-3">
                <h2>Gegenstand</h2>
                <div class="d-flex flex-column gap-2">
                    <?=strip_tags($project->getSubjectmemo());?>
                </div>
            </div>

        
        <!--Meilensteine-->
        <div class="custom-fields p-3 d-flex flex-column gap-3">
        <h2 class="mb-4">Meilensteine</h2>
        <div class="milestone-container">

        <?php foreach($project->getPlanningEntries() as $planningEntry) {
            
            ?>
            <div class="dashed-line"></div>
            <div class="milestone">
                <div class="diamond"></div>
                <span class="text-muted"><?=date('d.m.Y',strtotime($planningEntry->getEndDate()));?></span>
                <strong><?=$planningEntry->getDescription();?></strong>
            </div>
            <?php
        }?>
         
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