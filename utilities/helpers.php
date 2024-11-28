<?php

if (!function_exists('transformDate')) {
    /**
     * Transforms a date from YYYY-MM-DD format to DD.MM.YYYY format.
     *
     * @param string $date Date in YYYY-MM-DD format.
     * @return string Transformed date in DD.MM.YYYY format.
     * @throws Exception If the input date format is invalid.
     */
    function transformDate($date)
    {
        // Validate and convert the date
        if (DateTime::createFromFormat('Y-m-d', $date) === false) {
            throw new Exception("Invalid date format. Expected YYYY-MM-DD.");
        }

        return DateTime::createFromFormat('Y-m-d', $date)->format('d.m.Y');
    }
}

if (!function_exists('extractIds')) {
    function extractIds($array)
    {
        $ids = array_map(function ($object) {
            return $object['id'];
        }, $array);
        return $ids;
    }
}

if (!function_exists('buildFilters')) {
    function buildFilters(array $possibleFilters): array
    {
        return array_filter(
            $possibleFilters,
            fn($value) => !empty ($value) && array_filter($value, fn($v) => $v !== 0) // Exclude arrays with only 0
        );
    }
}

if (!function_exists('getQueryParam')) {
    function getQueryParam(string $key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
}