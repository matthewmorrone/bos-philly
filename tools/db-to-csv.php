<?php
error_reporting(E_ERROR);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "bosphill_dev";

$mysqli = new mysqli($host, $user, $pass, $db);

function ensureConsistentKeys(array &$array): void {
    if (empty($array)) return;

    $referenceKeys = array_keys($array[0]);
    foreach ($array as &$item) {
        foreach ($referenceKeys as $key) {
            if (!array_key_exists($key, $item)) {
                $item[$key] = null;
            }
        }
    }
    foreach ($array as &$item) {
        $item = array_intersect_key($item, array_flip($referenceKeys));
    }
}
function arrayToCsvString(array $array): string {
    if (empty($array)) return '';

    $csvString = '';

    $headers = array_keys($array[0]);
    $csvString .= implode(',', $headers) . "\n";

    $quoteField = function($field) {
        if (preg_match('/[,"\n]/', $field)) {
            $field = str_replace('"', '""', $field);
            return '"' . $field . '"';
        }
        if (strpos($field, ' ') !== false) {
            return '"' . $field . '"';
        }
        return $field;
    };

    foreach ($array as $row) {
        $quotedRow = array_map($quoteField, $row);
        $csvString .= implode(',', $quotedRow) . "\n";
    }

    return $csvString;
}

function saveToFile($filePath, $data) {
    $directory = dirname($filePath);
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0755, true)) {
            throw new Exception("Failed to create directory: $directory");
        }
    }
    file_put_contents($filePath, $data);
}

function get_query($query) {
    GLOBAL $mysqli, $debug;
    if ($debug) echo "$query<br>";
    $result = $mysqli->query($query) or die($mysqli->error);
    while($row = $result->fetch_assoc()) $results[] = $row;
    return $results;
}

function groupBy($array, $key) {
    $result = [];
    foreach ($array as $item) {
        if (isset($item[$key])) {
            $result[$item[$key]][] = $item;
        }
    }
    return $result;
}

$query = "select 
    wp_posts.id
  , wp_posts.post_type
  , wp_posts.post_name
  , wp_posts.post_title
from wp_posts
where wp_posts.post_type in ('event', 'model', 'dj')
and wp_posts.post_name NOT LIKE '%__trashed%'
and wp_posts.post_name != '';";

$posts = get_query($query);
foreach($posts as &$post):
    $query = "select distinct
      wp_postmeta.post_id
    , wp_postmeta.meta_key
    , wp_postmeta.meta_value 
    from wp_postmeta 
    where post_id='".$post["id"]."'
    and wp_postmeta.meta_key NOT LIKE '\_%'
    and wp_postmeta.meta_key NOT IN ('photos', 'gallery', 'ekit_post_views_count', 'om_disable_all_campaigns', 'ticket_price', 'logo', 'image_gallery', 'image_1', 'image_2', 'image_3', 'soundcloud-link')
    ";
    $metadata = get_query($query);
    foreach($metadata as $row) {
        $post[$row["meta_key"]] = $row["meta_value"];
    }
endforeach;

$posts = groupBy($posts, "post_type");

saveToFile("csv/djs.csv", arrayToCsvString($posts["dj"]));
saveToFile("csv/events.csv", arrayToCsvString($posts["event"]));
saveToFile("csv/models.csv", arrayToCsvString($posts["model"]));