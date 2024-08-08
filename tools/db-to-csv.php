<?php
error_reporting(E_ERROR);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "bosphill_dev";

$mysqli = new mysqli($host, $user, $pass, $db);
// $mysqli = mysqli_connect($host, $user, $pass, $db);
function ensureConsistentKeys(array &$array): void {
    if (empty($array)) {
        return; // No action needed for an empty array
    }

    // Get the keys of the first sub-array
    $referenceKeys = array_keys($array[0]);

    foreach ($array as &$item) {
        // Add missing keys with null values
        foreach ($referenceKeys as $key) {
            if (!array_key_exists($key, $item)) {
                $item[$key] = null;
            }
        }
    }
    
    // Optionally, you might want to ensure no extra keys are present
    foreach ($array as &$item) {
        $item = array_intersect_key($item, array_flip($referenceKeys));
    }
}
function arrayToCsvString(array $array): string {
    if (empty($array)) {
        return ''; // Return an empty string if the array is empty
    }

    // Initialize an empty CSV string
    $csvString = '';

    // Get the headers from the first sub-array
    $headers = array_keys($array[0]);
    $csvString .= implode(',', $headers) . "\n";

    // Function to properly quote and escape fields
    $quoteField = function($field) {
        // Check if the field needs quoting
        if (preg_match('/[,"\n]/', $field)) {
            // Escape double quotes by doubling them
            $field = str_replace('"', '""', $field);
            // Enclose the field in double quotes
            return '"' . $field . '"';
        }
        // If the field contains spaces, also quote it
        if (strpos($field, ' ') !== false) {
            return '"' . $field . '"';
        }
        return $field;
    };

    // Iterate through each row and convert it to CSV format
    foreach ($array as $row) {
        // Apply quoting function to each field
        $quotedRow = array_map($quoteField, $row);
        // Join the row fields with commas and add it to the CSV string
        $csvString .= implode(',', $quotedRow) . "\n";
    }

    return $csvString;
}

function saveToFile($filePath, $data) {
    // Get the directory path from the file path
    $directory = dirname($filePath);

    // Check if the directory exists
    if (!is_dir($directory)) {
        // Create the directory recursively if it does not exist
        if (!mkdir($directory, 0755, true)) {
            throw new Exception("Failed to create directory: $directory");
        }
    }

    // Write data to the file
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
  , wp_posts.post_content
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