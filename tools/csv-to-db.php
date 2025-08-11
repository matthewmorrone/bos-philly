<?php
error_reporting(E_ERROR);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include ("../wordpress/wp-load.php");

function csvStringToAssociativeArray(string $csvString): array {
    $lines = explode("\n", trim($csvString));
    if (empty($lines)) return [];
    
    $headers = str_getcsv(array_shift($lines));
    $result = [];
    foreach ($lines as $line) {
        $data = str_getcsv($line);
        if (count($headers) === count($data)) {
            $result[] = array_combine($headers, $data);
        }
    }
    return $result;
}

function assignValues($posts) {
    foreach ($posts as $post):
        $post_id = $post["id"];
        foreach ($post as $key=>$value):
            if (strcmp($key, "id") === 0) continue;
            if (update_field($key, $value, $post_id)) {
                echo $key."\t".$value."\t".$post_id." succeeded\n";
            }
            else {
                echo $key."\t".$value."\t".$post_id." failed\n";
            }
        endforeach;
    endforeach;
}


echo "<pre>";

if (function_exists('update_field')) {
    $events = csvStringToAssociativeArray(file_get_contents("csv/events.csv"));
    assignValues($events);
    $models = csvStringToAssociativeArray(file_get_contents("csv/models.csv"));
    assignValues($models);
    $djs = csvStringToAssociativeArray(file_get_contents("csv/djs.csv"));
    assignValues($djs);
} 
else {
    echo 'ACF is not active.';
}
echo "</pre>";