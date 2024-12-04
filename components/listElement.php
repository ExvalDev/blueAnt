<?php
require_once 'utilities/Phases.php';
function renderListElement(Project $project)
{
    $phaseEnum = Phase::tryFrom($project->getStatus()->getPhase());
    $phaseColor = $phaseEnum->getColor();

    $projectId = $project->getId();
    $name = $project->getName();
    $startDate = transformDate($project->getStartDate());
    $endDate = transformDate($project->getEndDate());
    $priority = $project->getPriority()->getName();
    $phase = $project->getStatus()->getPhase();
    $status = $project->getStatus()->getName();


    return "
    <a class='card d-flex flex-col text-decoration-none' href='/project.php?projectId=$projectId'>
        <div class='d-flex justify-content-between'>
            <div>
                <h5 class='mb-0'>$name</h5>
                <small class='text-muted'>$startDate - $endDate</small>
            </div>
            <div>
                <span class='badge rounded-pill fw-bold badge-primary'>$projectId</span>
            </div>
        </div>
        <div class='d-flex'>
        </div>
        <div class='d-flex align-items-center gap-3'>
            <span class='badge rounded-pill bg-secondary' data-bs-toggle='tooltip' data-bs-placement='top' title='Priorität'>$priority</span>
            <span class='badge rounded-pill' style='background-color: $phaseColor;' data-bs-toggle='tooltip' data-bs-placement='top' title='Status - Phase $phase'>$status</span>
            <i class='bi bi-arrow-down-right-square fs-5 icon'></i>
        </div>
    </a>";
}