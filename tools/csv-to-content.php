<?php

error_reporting(E_ERROR);
include ("../wordpress/wp-load.php");

$descriptions = file_get_contents("csv/content.csv");

$descriptions = explode("~~~", $descriptions);

$descriptions = array_map(function($description) {
    $description = trim($description);
    $lines = explode("\n", $description);

    return [
        "id" => $lines[0],
        "post_name" => $lines[1],
        "post_content" => implode("\n", array_slice($lines, 2))
    ];
}, $descriptions);

print_r($descriptions);