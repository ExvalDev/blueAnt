<?php
/**
 * Render all projects in overview
 * @param mixed $projects
 * @return string
 */
function renderList($projects)
{
    $html = "<div class='list'>";

    foreach ($projects as $project) {
        $html .= renderListElement($project);
    }

    $html .= "</div>";
    return $html;
}