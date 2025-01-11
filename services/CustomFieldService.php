<?php

require 'models/CustomField.php';
require 'models/CustomFieldOption.php';
require 'controllers/CustomFieldController.php';

class CustomFieldService //implements serviceInterface
{
    private $customFieldController;
    private static $customFields = null;

    public function __construct()
    {
        $this->customFieldController = new CustomFieldController();
    }

    public function getCustomFields(): array
    {
        self::$customFields = [];
        $response = $this->customFieldController->getCustomFields();
        foreach ($response as $customField) {
            if (isset($customField['options'])) {
                $options = [];
                foreach ($customField['options'] as $option) {
                    $options[] = new CustomFieldOption($option['key'], $option['value']);
                }
                self::$customFields[$customField['id']] = new CustomField($customField['id'], $customField['name'], $customField['type'], $options);
            } else {
                self::$customFields[$customField['id']] = new CustomField($customField['id'], $customField['name'], $customField['type']);
            }

        }
        return self::$customFields;
    }

    public function findCustomFieldById($customFieldId)
    {
        $customFields = $this->getCustomFields();

        if (isset($customFields[$customFieldId])) {
            return $customFields[$customFieldId];
        }

        throw new Exception("CustomField with ID $customFieldId not found.");
    }

    public function getCustomFieldsOfProject(array $customFields)
    {
        $customFieldsOfProject = [];
        foreach ($customFields as $key => $value) {
            try {
                // Fetch client details
                $customField = $this->findCustomFieldById($key);
                if (!$customField) {
                    throw new Exception("Customer data not found for key: " . $key);
                }

                if($customField->getName() == "Strategiebeitrag" ||  $customField->getName() == "Score" ){
                    switch ($customField->getType()) {
                        case "ListBox":
                            $options = $customField->getOptions();
                            foreach ($options as $option) {
                                if ($option->getKey() == $value) {
                                    $customField->setValue($option->getValue());
                                    $option->setIsSelected(true);
                                }
                            }
                        default:
                            $customField->setValue($value);
                    }
    
                    $customFieldsOfProject[$customField->getId()] = $customField;
                }


            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
        return $customFieldsOfProject;
    }
}