<?php

function renderList($projects)
{
    $html = "<div class='list'>";

    foreach ($projects as $project) {
        $html .= renderListElement($project);
    }

    $html .= "</div>";
    return $html;
}