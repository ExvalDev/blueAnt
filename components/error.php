<?php

/**
 * Render an error message with an optional footer.
 *
 * @param string $message The error message to display.
 * @param string $type The type of alert (e.g., 'danger', 'info', 'warning', 'success').
 * @param bool $includeFooter Whether to include the footer below the error message.
 */
function renderError(string $message, string $type = 'danger', bool $includeFooter = true): string
{
    $footer = $includeFooter ? includeFooter() : '';

    return "
    <div class='error d-flex gap-4'>
        <h1 class='fs-1 fw-bold'>Ups...</h1>
        <div class='alert alert-{$type} text-center' role='alert'>
            " . htmlspecialchars($message) . "
        </div>
    </div>
    {$footer}";
}

/**
 * Include the footer.
 *
 * @return string The rendered footer HTML.
 */
function includeFooter(): string
{
    ob_start();
    include 'components/footer.php';
    return ob_get_clean();
}