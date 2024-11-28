<?php
include 'components/header.php';
require 'components/error.php';
require 'services/ProjectService.php';
require 'services/StatusService.php';
require 'services/PriorityService.php';
require 'services/DepartmentService.php';
require 'services/TypeService.php';
require 'services/PersonService.php';
require 'services/RoleService.php';
require 'services/ClientService.php';
require 'services/CustomerTypeService.php';
require 'services/CustomFieldService.php';
require 'utilities/helpers.php';
require_once 'components/backButton.php';
require_once 'components/customField.php';
require_once 'utilities/Phases.php';

// Initialize services
$projectService = new ProjectService();
$statusService = new StatusService();
$priorityService = new PriorityService();
$departmentService = new DepartmentService();
$typeService = new TypeService();
$personService = new PersonService();
$roleService = new RoleService();
$clientService = new ClientService();
$customerTypeService = new CustomerTypeService();
$customFieldService = new CustomFieldService();


try {
    $projectId = intval(getQueryParam('projectId'));
    $project = $projectService->getProjectById($projectId);
} catch (Exception $e) {
    echo renderError($e->getMessage());
    exit;
}

$status = $statusService->getStatusById($project['statusId']);
$priority = $priorityService->getPriorityById($project['priorityId']);
$department = $departmentService->getDepartmentById($project['departmentId']);
$type = $typeService->getTypeById($project['typeId']);
$phase = $status['phase'];
$phaseEnum = Phase::tryFrom($phase);
$phaseColor = $phaseEnum->getColor();
$person = $personService->getPersonById($project['projectLeaderId']);
$role = $roleService->getRoleById($project['projectLeaderRoleId']);
$customerTypes = $customerTypeService->getCustomerTypes();
$clients = [];
foreach ($project['clients'] as $client) {
    try {
        // Fetch client details
        $clientData = $clientService->getClientById($client['clientId']);

        if (!$clientData) {
            throw new Exception("Client data not found for clientId: " . $client['clientId']);
        }

        // Fetch customer type details
        $customerType = $customerTypeService->findCustomerTypeById($clientData['typeId']);

        if (!$customerType) {
            throw new Exception("Customer type not found for typeId: " . $clientData['typeId']);
        }

        // Add object with 'name' and 'type' to the $clients array
        $clients[] = [
            'name' => $clientData['text'],
            'type' => $customerType['text']
        ];
    } catch (Exception $e) {
        // Log the error message and continue with the next client
        error_log($e->getMessage());
    }
}
$customFields = $customFieldService->getCustomFields();
?>

<main>
    <div class="content-project">
        <header class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2 align-items-center">
                <?php echo renderBackButton(); ?>
            </div>
        </header>
        <div class="details-header container d-flex align-items-start justify-content-between p-3">
            <h5 class="mb-0"><?php echo $project['name']; ?></h5>
            <div class="d-flex flex-row-reverse gap-2 justify-content-center align-items-center">
                <span class="badge rounded-pill badge-primary fw-bold" data-bs-toggle='tooltip' data-bs-placement='top'
                    title='Projekt-ID'><?php echo $projectId; ?></span>
                <p class="text-truncate" data-bs-toggle='tooltip' data-bs-placement='top' title='Projektart'>
                    <?php echo $type['description']; ?>
                </p>
            </div>
        </div>
        <div class="grid">
            <div class="tile p-3">
                <h2>Details</h2>
                <div>
                    <h3>Projektzeitraum</h3>
                    <p class="text-truncate"><?php echo transformDate($project['start']); ?> -
                        <?php echo transformDate($project['end']); ?>
                    </p>
                </div>
            </div>
            <div class="tile p-3">
                <div>
                    <h3>Status</h3>
                    <span class='badge rounded-pill text-truncate' style='background-color: <?php echo $phaseColor; ?>;'
                        data-bs-toggle='tooltip' data-bs-placement='top' title='Status - Phase <?php echo $phase; ?>'>
                        <?php echo $status['text']; ?>
                    </span>
                </div>
                <div>
                    <h3>Priorität</h3>
                    <span class='badge rounded-pill bg-secondary' data-bs-toggle='tooltip' data-bs-placement='top'
                        title='Priorität'><?php echo $priority['text']; ?></span>
                </div>
                <div>
                    <h3>Abteilung</h3>
                    <span class='badge rounded-pill bg-secondary' data-bs-toggle='tooltip' data-bs-placement='top'
                        title='Abteilung'><?php echo $department['text']; ?></span>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="tile p-3 h-50">
                    <h3><?= $role['text'] ?></h3>
                    <div class="row">
                        <div class="col-3">
                            <input class="form-control" type="text" placeholder="<?= $person['firstname'] ?>" disabled>
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text" placeholder="<?= $person['lastname'] ?>" disabled>
                        </div>
                        <div class="col-6">
                            <input class="form-control" type="text" placeholder="<?= $person['email'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="tile p-3 h-50">
                    <h3>Kunde</h3>
                    <div class="d-flex flex-row gap-2">
                        <?php
                        if (count($clients) > 0) {
                            foreach ($clients as $client) {
                                echo "
                                        <div>
                                            <h4 class='mb-2'>{$client['type']}</h4>
                                            <input class='form-control' type='text' placeholder='{$client['name']}' disabled>
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
        <div class="custom-fields p-3">
            <h2>Benutzerdefinierte Felder</h2>
            <div class="d-flex flex-column gap-2">
                <?php foreach ($project['customFields'] as $key => $value) {
                    echo renderCustomField($key, $value);
                } ?>
            </div>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>