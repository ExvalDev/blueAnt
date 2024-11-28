<?php

/**
 * Render a back button.
 *
 * @param string|null $fallbackUrl The URL to navigate to if JavaScript is disabled or no history exists.
 * @param string $buttonText The text to display on the button.
 * @param string $buttonClass Additional classes for styling the button.
 * @return string The rendered back button HTML.
 */
function renderBackButton(?string $fallbackUrl = null, string $buttonText = 'Zurück', string $buttonClass = 'btn btn-back'): string
{
    $fallbackUrl = $fallbackUrl ?? '/'; // Default fallback URL
    return "
    <button class='{$buttonClass}' onclick='history.back()'>
        <i class='bi bi-arrow-left fs-4'></i> {$buttonText}
    </button>
    <noscript>
        <a href='{$fallbackUrl}' class='{$buttonClass}'>
            <i class='bi bi-arrow-left fs-4 -mt-2'></i> {$buttonText}
        </a>
    </noscript>
    ";
}