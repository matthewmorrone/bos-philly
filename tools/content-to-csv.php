<?php

error_reporting(E_ERROR);
include ("../wordpress/wp-load.php");

function saveToFile($filePath, $data) {
    $directory = dirname($filePath);
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0755, true)) {
            throw new Exception("Failed to create directory: $directory");
        }
    }
    file_put_contents($filePath, $data);
}

$events = get_posts(array(
    "post_type" => "event",
    'nopaging' => true,
    'fields' => 'ids'
));
$models = get_posts(array(
    "post_type" => "model",
    'nopaging' => true,
    'fields' => 'ids'
));
$djs = get_posts(array(
    "post_type" => "dj",
    'nopaging' => true,
    'fields' => 'ids'
));
$posts = array_merge($events, $models, $djs);


$result = "";
foreach($posts as &$post):
    $id = $post;
    $post_name = get_post_field("post_name", $id);
    $post_content = get_post_field("post_content", $id);
    $result .= $id."\n";
    $result .= $post_name."\n";
    $result .= $post_content."\n";
    $result .= "~~~\n";
endforeach;

saveToFile("csv/content.csv", $result);


