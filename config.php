<?php

return [
    'baseUrl' => 'https://dashboard-examples.blueant.cloud/rest',
    'bearerToken' => 'eyJhbGciOiJIUzI1NiJ9.eyJqdGkiOiI3OTkzMjYyMjkiLCJpYXQiOjE3MzE1MDA5MzQsImlzcyI6IkJsdWUgQW50wqkiLCJleHAiOjE4ODkxODA5MzR9.ycX7nHJQH2nJSvURAmHOGebzElw9BzIgwyafRfDcJUY',   // Replace with your Bearer token
    'apiEndpoints' => [
        'projects' => '/v1/projects?includeMemoFields=true',
        'project' => '/v1/projects/{id}',
        'statuses' => '/v1/masterdata/projects/statuses',
        'status' => '/v1/masterdata/projects/statuses/{id}',
        'priorities' => '/v1/masterdata/projects/priorities',
        'priority' => '/v1/masterdata/projects/priorities/{id}',
        'departments' => '/v1/masterdata/departments',
        'department' => '/v1/masterdata/departments/{id}',
        'types' => '/v1/masterdata/projects/types',
        'type' => '/v1/masterdata/projects/types/{id}',
        'persons' => '/v1/human/persons',
        'person' => '/v1/human/persons/{id}',
        "roles" => "/v1/masterdata/projects/chargeratesroles",
        "role" => "/v1/masterdata/projects/chargeratesroles/{id}",
        "customers" => "/v1/masterdata/customers",
        "customer" => "/v1/masterdata/customers/{id}",
        "customerTypes" => "/v1/masterdata/customers/types",
        "customerType" => "/v1/masterdata/customers/types/{id}",
        "customFields" => "/v1/masterdata/customfield/definitions/Project"
    ],
    'customListBox' => "Strategiebeitrag",
    'customCalculated' => "Score"
];