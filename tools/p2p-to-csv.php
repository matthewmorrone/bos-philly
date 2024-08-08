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
foreach($events as &$event):
    $id = $event;
    $post_name = get_post_field("post_name", $id);
    $primary_dj = get_posts(array(
        'connected_type' => 'primary_dj',
        'connected_items' => get_post($id),
        'nopaging' => true,
        'suppress_filters' => false,
        'fields' => 'ids'
    ));
    foreach($primary_dj as &$pdj):
        $pdj_id = $pdj;
        $pdj_post_name = get_post_field("post_name", $pdj_id);
        $pdj = [
            "id" => $pdj_id, 
            "post_name" => $pdj_post_name
        ];
    endforeach;
    $secondary_dj = get_posts(array(
        'connected_type' => 'secondary_dj',
        'connected_items' => get_post($id),
        'nopaging' => true,
        'suppress_filters' => false,
        'fields' => 'ids'
    ));
    foreach($secondary_dj as &$sdj):
        $sdj_id = $sdj;
        $sdj_post_name = get_post_field("post_name", $sdj_id);
        $sdj = [
            "id" => $sdj_id, 
            "post_name" => $sdj_post_name
        ];
    endforeach;
    $event = [
        "id" => $id, 
        "post_name" => $post_name,
        "primary" => $primary_dj,
        "secondary" => $secondary_dj,
    ];
endforeach;

$result = "event_id,event_name,dj_id,dj_name,type\n";
foreach($events as $event):
    
    if (isset($event["primary"][0]["id"])) {
        $result .= $event["id"].",";
        $result .= $event["post_name"].",";
        $result .= @$event["primary"][0]["id"].",";
        $result .= @$event["primary"][0]["post_name"].",";
        $result .= "primary";
        $result .= "\n";
    }

    foreach($event["secondary"] as $secondary):
        if (isset($secondary["id"])) {
            $result .= $event["id"].",";
            $result .= $event["post_name"].",";
            $result .= @$secondary["id"].",";
            $result .= @$secondary["post_name"].",";
            $result .= "secondary";
            $result .= "\n";        
        }
    endforeach;
endforeach;

saveToFile("csv/djs-to-events.csv", $result);


