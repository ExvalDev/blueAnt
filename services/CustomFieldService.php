<?php

require_once 'RequestService.php';

class CustomFieldService
{
    private $requestService;
    private $config;
    private static $customFields = null;

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->config = require __DIR__ . '/../config.php';
    }

    private function fetchCustomFields()
    {
        try {
            if (self::$customFields === null) {
                $response = $this->requestService->get($this->config['apiEndpoints']['customFields']);
                if (!isset($response['customFields'])) {
                    throw new Exception('Custom Fields not found in API response');

                }
                self::$customFields = [];
                foreach ($response['customFields'] as $customField) {
                    self::$customFields[$customField['id']] = $customField;
                }
            }
            return self::$customFields;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }


    }

    // Get all cached customFields
    public function getCustomFields()
    {
        return $this->fetchCustomFields();
    }


    public function findCustomFieldById($customFieldId)
    {
        $customFields = $this->fetchCustomFields();

        if (isset($customFields[$customFieldId])) {
            return $customFields[$customFieldId];
        }

        throw new Exception("CustomField with ID $customFieldId not found.");
    }
}