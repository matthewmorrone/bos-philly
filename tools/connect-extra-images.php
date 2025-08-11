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
function filter_array_by_regex($array, $pattern) {
    return array_filter($array, function($value) use ($pattern) {
        return preg_match($pattern, $value);
    });
}

function load_images($posts, $photos) {
    foreach($posts as &$post):
        $id = $post;
        $post_name = get_post_field("post_name", $id);
        $images = filter_array_by_string($photos, "$post_name");
        asort($images);
        $logo = filter_array_by_string($images, "logo");
        $logo = @array_keys($logo)[0];
        $more = filter_array_by_regex($images, "/\-\d+\./");
        $more = implode(",", array_keys($more));
        $post = [
            "id" => $id, 
            "post_name" => $post_name,
            "logo" => $logo,
            "more" => $more
        ];
    endforeach;
    return $posts;
}


$args = array(
    'posts_per_page' => -1,
    'fields'         => 'ids',
);
$args['post_type'] = 'dj';
$query = new WP_Query($args);
$djs = $query->get_posts();

$args['post_type'] = 'model';
$query = new WP_Query($args);
$models = $query->get_posts();

echo "<pre>";

$djPhotos = filter_array_by_string($photos, "/dj-");
$djPhotoData = load_images($djs, $djPhotos);

$modelPhotos = filter_array_by_string($photos, "/model-");
$modelPhotoData = load_images($models, $modelPhotos);

foreach($djPhotoData as $djPhotoDatum):
    if (function_exists('update_field')) {
        $key = "logo";
        $value = $djPhotoDatum["logo"];
        $post_id = $djPhotoDatum["id"];
        if ($value) {
            if (update_field($key, $value, $post_id)) echo "$key\t$value\t$post_id logo succeeded\n";
            else                                      echo "$key\t$value\t$post_id logo failed\n";
        }

        $key = "photos";
        $value = $djPhotoDatum["more"];
        $post_id = $djPhotoDatum["id"];
        if ($value) {
            if (update_field($key, $value, $post_id)) echo "$key\t$value\t$post_id more succeeded\n";
            else                                      echo "$key\t$value\t$post_id more failed\n";
        }
    }
endforeach;

foreach($modelPhotoData as $modelPhotoDatum):
    if (function_exists('update_field')) {
        $key = "photos";
        $value = $modelPhotoDatum["more"];
        $post_id = $modelPhotoDatum["id"];
        if ($value) {
            if (update_field($key, $value, $post_id)) echo "$key\t$value\t$post_id more succeeded\n";
            else                                      echo "$key\t$value\t$post_id more failed\n";
        }
    }
endforeach;

echo "</pre>";