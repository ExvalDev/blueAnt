<?php

require_once 'RequestController.php';

class CustomFieldController
{
    private $requestController;
    private $config;
    private static $customFields = null;

    public function __construct()
    {
        $this->requestController = new RequestController();
        $this->config = require __DIR__ . '/../config.php';
    }

    public function getCustomFields(): array
    {
        try {
            if (self::$customFields === null) {
                $response = $this->requestController->get($this->config['apiEndpoints']['customFields']);
                if (!isset($response['customFields'])) {
                    throw new Exception('Custom Fields not found in API response');

                }
                self::$customFields = $response['customFields'];
            }
            return self::$customFields;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e; // Re-throw the exception for upstream handling
        }
    }


    public function findCustomFieldById($customFieldId)
    {
        $customFields = $this->getCustomFields();

        if (isset($customFields[$customFieldId])) {
            return $customFields[$customFieldId];
        }

        throw new Exception("CustomField with ID $customFieldId not found.");
    }
}