<?php
require_once 'utilities/Phases.php';
require_once 'utilities/Priorities.php';
require_once 'components/customField.php';
function renderListElement(Project $project)
{

    $phaseEnum = Phase::tryFrom($project->getStatus()->getPhase());
    $phaseColor = $phaseEnum->getColor();

    $priorityEnum = Priorities::tryFrom($project->getPriority()->getId());
    $priorityColor = $priorityEnum->getColor();


    $projectId = $project->getId();
    $name = $project->getName();
    $startDate = transformDate($project->getStartDate());
    $endDate = transformDate($project->getEndDate());
    $priority = $project->getPriority()->getName();
    $phase = $project->getStatus()->getPhase();

    $status = $project->getStatus()->getName();
    $projectLeader = $project->getProjectLeader();
    $projectLeaderString = $projectLeader->getFirstname() . " " . $projectLeader->getLastname();
    $subjectMemo = $project->getSubjectmemo();

    $score = "-";
    $classification = "";
    $strategy = "";

    foreach ($project->getCustomFields() as $customField) {
        switch ($customField->getName()) {
            case 'Strategiebeitrag':
                $strategy = renderTrafficLightsOnly($customField);
                break;
            case 'Score':
                $score = $customField->getValue();
                break;
            case 'Erläuterung Cluster':
                $classification = $customField->getValue();
                break;
        }
    }

    return "
    <a class='card d-flex flex-column projectCard justify-content-between text-decoration-none' href='/project.php?projectId=$projectId'>
        <div class='d-flex justify-content-between'>
            <div>
                <h5 class='mb-0'>$name</h5>
                <small class='text-muted'>$startDate - $endDate</small>
            </div>
            <div class='d-flex flex-row align-items-start gap-1'>
                <span class='badge rounded-pill fw-bold badge-primary flex-shrink-0'>$projectId</span>
                <span class='badge rounded-pill' style='background-color: $priorityColor;' data-bs-toggle='tooltip' data-bs-placement='top' title='Priorität'>$priority</span>
            </div>
        </div>
        <div class='d-flex flex-row justify-content-between'>
            <div class='d-flex flex-column'>
                <small class='text-muted'>Projektleiter</small>
                <p>$projectLeaderString</p>
            </div>
            <div class='d-flex flex-row align-items-start gap-3'>
                " . (!empty($classification) ? "<small style='background-color: #636363;' class='badge rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='{$classification}'>Erläuterung Cluster</small>" : "") . "
                <div class='d-flex flex-column gap-1'>
                    " . (!empty($strategy) ? "<small class='text-muted text-center'>Strategiebeitrag</small><p>{$strategy}</p>" : "") . "
                </div>
                <div class='d-flex flex-column gap-1'>
                    <small class='text-muted score'>Score</small>
                    <p>$score</p>
                </div>
            </div>
        </div>
        <div class='d-flex flex-row justify-content-between'>
            <p class='truncate subjectMemoField'>" . strip_tags($subjectMemo) . "</p>
        </div>
        <div class='d-flex align-items-center justify-content-between'>
            <span class='badge rounded-pill flex-shrink-0' style='background-color: $phaseColor;' data-bs-toggle='tooltip' data-bs-placement='top' title='Status - Phase $phase'>$status</span>
            <i class='bi bi-arrow-down-right-square fs-5 icon'></i>
        </div>
    </a>";
}