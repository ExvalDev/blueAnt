<?php
require_once 'utilities/Phases.php';
function renderListElement($projectId, $name, $startDate, $endDate, $priority, $status, int $phase)
{
    $phaseEnum = Phase::tryFrom($phase);
    $phaseColor = $phaseEnum->getColor();

    return "
    <a class='card d-flex flex-row align-items-center justify-content-between p-3 text-decoration-none' href='/project.php?projectId=$projectId'>
        <div class='d-flex align-items-center w-75'>
            <div class='row w-100 align-items-center'>
                <div class='col-3'>
                    <span class='badge rounded-pill fw-bold badge-primary'>$projectId</span>
                </div>
                <div class='col-9'>
                    <h5 class='mb-0'>$name</h5>
                    <small class='text-muted'>$startDate - $endDate</small>
                </div>
            </div>
        </div>
        <div class='d-flex align-items-center gap-3'>
            <span class='badge rounded-pill bg-secondary' data-bs-toggle='tooltip' data-bs-placement='top' title='Priorität'>$priority</span>
            <span class='badge rounded-pill' style='background-color: $phaseColor;' data-bs-toggle='tooltip' data-bs-placement='top' title='Status - Phase $phase'>$status</span>
            <i class='bi bi-arrow-down-right-square fs-5 icon'></i>
        </div>
    </a>";
}
?>