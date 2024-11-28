<?php

/**
 * Render a custom field based on its type.
 *
 * @param array $customFields The list of all custom fields (from the API response).
 * @param string $key The ID of the custom field to render.
 * @param mixed $value The value of the custom field to display.
 * @return string Rendered HTML for the custom field.
 */
function renderCustomField(string $key, $value): string
{
    $customFieldService = new CustomFieldService();
    // Find the custom field by key
    $field = $customFieldService->findCustomFieldById($key);

    if (!$field) {
        return "<p class='text-danger'>Field with ID {$key} not found.</p>";
    }

    $trafficLightFields = ['Sicherheitsgrad', 'Strategiebeitrag', 'Sicherheits-Level', 'Investitionssicherheit', 'Vertraulichkeit'];
    if (in_array($field['name'], $trafficLightFields)) {
        return renderTrafficLightField($field, $value);
    }

    // Start rendering based on field type
    $html = "<div class='custom-field mb-3'>";
    $html .= "<label class='form-label'>" . htmlspecialchars($field['name']) . "</label>";

    switch ($field['type']) {
        case 'ListBox':
            $options = $field['options'] ?? [];
            $selectedValue = '';

            // Find the value of the selected option
            foreach ($options as $option) {
                if ($option['key'] == $value) {
                    $selectedValue = htmlspecialchars($option['value']);
                    break;
                }
            }

            // Render a readonly input field with the selected value
            $html .= "<input type='text' class='form-control' value='{$selectedValue}' disabled />";
            break;
        case 'StructuredMemo':
        case 'Memo':
            // Handle memo fields as textareas
            $html .= "<textarea class='form-control' rows='4' disabled >" . htmlspecialchars($value) . "</textarea>";
            break;

        case 'Double':
        case 'Long':
            // Handle numerical fields
            $html .= "<input type='number' class='form-control' value='" . htmlspecialchars($value) . "' disabled />";
            break;

        case 'Date':
            // Handle date fields
            $html .= "<input type='date' class='form-control' value='" . htmlspecialchars($value) . "' disabled />";
            break;

        case 'Boolean':
            // Handle boolean fields as a checkbox
            $checked = $value ? 'checked' : '';
            $html .= "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' id='field-{$key}' {$checked}>
                        <label class='form-check-label' for='field-{$key}'>Ja</label>
                      </div>";
            break;

        case 'String':
        case 'StaticText':
            // Handle string fields as simple text
            $html .= "<div class='form-control-plaintext'>" . $value . "</div>";
            break;

        case 'Calculated':
            // Handle calculated fields as read-only
            $html .= "<p class='form-control-plaintext'><strong>" . htmlspecialchars($value) . "</strong></p>";
            break;

        default:
            // Fallback for unsupported types
            $html .= "<p class='text-warning'>Unsupported field type: {$field['type']}</p>";
            break;
    }

    $html .= "</div>";
    return $html;
}

/**
 * Render a traffic light field.
 *
 * @param array $field The custom field definition.
 * @param mixed $value The selected value for the field.
 * @return string Rendered HTML for the traffic light UI.
 */
function renderTrafficLightField(array $field, $value): string
{
    $reversedFields = ['Sicherheits-Level'];
    // Define traffic light colors based on position
    $colorMap = ['#28A745', '#FFC107', '#ED1842', '#BD1234']; // Colors for the traffic light

    $options = in_array($field['name'], $reversedFields) ? array_reverse($field['options']) : $field['options'];

    // Generate traffic light UI
    $html = "<div class='traffic-light-field d-flex align-items-center gap-2'>";
    $html .= "<label class='form-label me-3'>" . htmlspecialchars($field['name']) . "</label>";

    foreach ($options as $index => $option) {
        $color = $colorMap[$index % count($colorMap)]; // Cycle through the colors
        $isActive = ($option['key'] == $value) ? 'active' : ''; // Mark the selected value

        // Only display the value if it's the active option
        $displayValue = ($isActive === 'active') ? htmlspecialchars($option['value']) : '';

        $html .= "<span class='badge-pill traffic-light {$isActive}' style='background-color: {$color};' data-bs-toggle='tooltip' data-bs-placement='top'
                    title='{$option['value']}'>
                    {$displayValue}
                  </span>";
    }

    $html .= "</div>";

    return $html;
}