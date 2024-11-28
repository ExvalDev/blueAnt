<?php

function renderList($projects, $statusService, $priorityService)
{
    $html = "<div class='list'>";

    foreach ($projects as $project) {
        $status = $statusService->findStatusById($project['statusId']);
        $html .= renderListElement(
            $project['id'],
            $project['name'],
            transformDate($project['start']),
            transformDate($project['end']),
            $priorityService->findPriorityById($project['priorityId'])['text'],
            $status['text'],
            $status['phase']
        );
    }

    $html .= "</div>";
    return $html;
}