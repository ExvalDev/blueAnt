<?php

function renderCustomField(CustomField $customField): string
{
    $trafficLightFields = ['Sicherheitsgrad', 'Strategiebeitrag', 'Sicherheits-Level', 'Investitionssicherheit', 'Vertraulichkeit'];
    if (in_array($customField->getName(), $trafficLightFields)) {
        return renderTrafficLightField($customField);
    }

    $name = $customField->getName();
    $value = $customField->getValue();

    // Start rendering based on field type
    $html = "<div class='custom-field mb-3'>";
    $html .= "<label class='form-label'>" . htmlspecialchars($name) . "</label>";

    switch ($customField->getType()) {
        case 'ListBox':
            $html .= "<input type='text' class='form-control' value='{$value}' disabled />";
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
                        <input class='form-check-input' type='checkbox' id='field-{$name}' {$checked}>
                        <label class='form-check-label' for='field-{$name}'>Ja</label>
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
            $html .= "<p class='text-warning'>Unsupported field type: {$value}</p>";
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
function renderTrafficLightField(CustomField $customField): string
{
    $reversedFields = ['Sicherheits-Level'];
    // Define traffic light colors based on position
    $colorMap = ['#28A745', '#FFC107', '#ED1842', '#BD1234']; // Colors for the traffic light

    $options = in_array($customField->getName(), $reversedFields) ? array_reverse($customField->getOptions()) : $customField->getOptions();

    // Generate traffic light UI
    $html = "<div class='traffic-light-field d-flex align-items-center gap-2'>";
    $html .= "<label class='form-label me-3'>" . htmlspecialchars($customField->getName()) . "</label>";

    foreach ($options as $index => $option) {
        $color = $colorMap[$index % count($colorMap)]; // Cycle through the colors
        $isActive = ($option->getIsSelected()) ? 'active' : ''; // Mark the selected value

        // Only display the value if it's the active option
        $displayValue = ($isActive === 'active') ? htmlspecialchars($option->getValue()) : '';
        $value = $option->getValue();
        $html .= "<span class='badge-pill traffic-light {$isActive}' style='background-color: {$color};' data-bs-toggle='tooltip' data-bs-placement='top'
                    title='{$value}'>
                    {$displayValue}
                  </span>";
    }

    $html .= "</div>";

    return $html;
}

function getListboxValue(CustomField $customField){

    $options = $customField->getOptions();


    foreach ($options as $index => $option) {
        if($option->getIsSelected()){
            return $option->getValue();
        }
    }
    return "";


}

function renderTrafficLightsOnly(CustomField $customField): string
{
    $reversedFields = ['Sicherheits-Level'];
    // Define traffic light colors based on position
    $colorMap = ['#28A745', '#FFC107', '#ED1842', '#BD1234']; // Colors for the traffic light

    $options = in_array($customField->getName(), $reversedFields) ? array_reverse($customField->getOptions()) : $customField->getOptions();

    // Generate traffic light UI (without the label)
    $html = "<div class='traffic-light-field d-flex align-items-center gap-2'>";

    foreach ($options as $index => $option) {
        $color = $colorMap[$index % count($colorMap)]; // Cycle through the colors
        $isActive = ($option->getIsSelected()) ? 'active' : ''; // Mark the selected value

        // Only display the value if it's the active option
        $displayValue = ($isActive === 'active') ? htmlspecialchars($option->getValue()) : '';
        $value = $option->getValue();
        $html .= "<span class='badge-pill traffic-light {$isActive}' style='background-color: {$color};' data-bs-toggle='tooltip' data-bs-placement='top'
                    title='{$value}'>
                    {$displayValue}
                  </span>";
    }

    $html .= "</div>";

    return $html;
}
