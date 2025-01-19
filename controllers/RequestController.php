<?php
/**
 * Generic Controller to make a request to the api
 */
class RequestController
{
    private $baseUrl;
    private $bearerToken;

    public function __construct()
    {
        $config = require __DIR__ . '/../config.php';
        $this->baseUrl = rtrim($config['baseUrl'], '/');
        $this->bearerToken = $config['bearerToken'];
    }
    public function get($endpoint, $params = [])
    {
        $url = $this->baseUrl . $endpoint;

        // Append query parameters if any
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $this->sendRequest($url, 'GET');
    }

    private function sendRequest($url, $method, $body = [])
    {
        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->bearerToken}",
                "Content-Type: application/json"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FAILONERROR => true
        ];

        // Add method-specific options
        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($body);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        // Handle cURL errors
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new Exception("cURL Error: $error");
        }

        curl_close($curl);

        $data = json_decode($response, true);

        // Handle JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Error: " . json_last_error_msg());
        }

        return $data;
    }
}