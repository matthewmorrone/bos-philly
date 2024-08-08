<?php
error_reporting(E_ERROR);
include ("../wordpress/wp-load.php");

function get_images() {
    $query_images_args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => - 1,
    );
    $query_images = new WP_Query($query_images_args);
    
    $images = array();
    foreach ($query_images->posts as $image) {
        $images[$image->ID] = wp_get_attachment_url($image->ID);
    }
    return $images;    
}
$photos = get_images();

function filter_array_by_string($array, $search_string) {
    return array_filter($array, function($value) use ($search_string) {
        return strpos($value, $search_string) !== false;
    });
}

function load_images($posts, $photos) {
    foreach($posts as &$post):
        $id = $post;
        $post_name = get_post_field("post_name", $id);
        $images = filter_array_by_string($photos, "$post_name.");
        $post = [
            "id" => $id, 
            "post_name" => $post_name,
            "image_id" => @array_keys($images)[0],
            "image_path" => @array_values($images)[0]
        ];
    endforeach;
    return $posts;
}

$args = array(
    'posts_per_page' => -1,
    'fields'         => 'ids',
);

$args['post_type'] = 'event';
$query = new WP_Query($args);
$events = $query->get_posts();
$eventImages = load_images($events, $photos);

$args['post_type'] = 'dj';
$query = new WP_Query($args);
$djs = $query->get_posts();
$djImages = load_images($djs, $photos);

$args['post_type'] = 'model';
$query = new WP_Query($args);
$models = $query->get_posts();
$modelImages = load_images($models, $photos);



echo "<pre>";


// print_r($djImages);
// print_r($modelImages);

foreach($eventImages as $eventImage):
    if (!$eventImage["image_id"]) continue;
    $id = $eventImage["id"];
    $post_id = $eventImage["image_id"];
    $result = set_post_thumbnail($id, $post_id);
    if ($result) echo "set_post_thumbnail event $post_id $id succeeded\n";
    else         echo "set_post_thumbnail event $post_id $id failed\n";
endforeach;

foreach($djImages as $djImage):
    if (!$djImage["image_id"]) continue;
    $id = $djImage["id"];
    $post_id = $djImage["image_id"];
    $result = set_post_thumbnail($id, $post_id);
    if ($result) echo "set_post_thumbnail dj $post_id $id succeeded\n";
    else         echo "set_post_thumbnail dj $post_id $id failed\n";
endforeach;

foreach($modelImages as $modelImage):
    if (!$modelImage["image_id"]) continue;
    $id = $modelImage["id"];
    $post_id = $modelImage["image_id"];
    $result = set_post_thumbnail($id, $post_id);
    if ($result) echo "set_post_thumbnail model $post_id $id succeeded\n";
    else         echo "set_post_thumbnail model $post_id $id failed\n";
endforeach;



echo "</pre>";