<?php
error_reporting(E_ERROR);
include ("wordpress/wp-config.php");

// Collect request data and sanitize expected parameters
$request_raw = $_POST ? wp_unslash($_POST) : wp_unslash($_GET);
$request = [];
foreach (['action','post_type','name','url','limit','rest','debug'] as $key) {
    if (isset($request_raw[$key])) {
        $request[$key] = sanitize_text_field($request_raw[$key]);
    }
}

$allowed_post_types = ['event','gallery','dj','model','page'];

function containsAnySubstring($string, $substrings) {
    foreach ($substrings as $substring) {
        if (strpos($string, $substring) !== false) {
            return true;
        }
    }
    return false;
}
function isUrlValid($url) {
    $response = wp_safe_remote_head($url);
    return !is_wp_error($response) && wp_remote_retrieve_response_code($response) < 400;
}

if (isset($request["debug"])) {
    echo "<pre>"; print_r($request); echo "</pre>";
}
if (isset($request["action"])) {
    $args = [];
    $args['post_status'] = "publish";
    $args['nopaging'] = true;

    switch ($request["action"]) {
        case "soundcloud":
            $url = isset($request["url"]) ? esc_url_raw($request["url"]) : '';
            if ($url && isUrlValid($url)) {
                $oembed = "http://soundcloud.com/oembed?format=js&url=$url&iframe=true";
                if (isUrlValid($oembed)) {
                    $response = wp_safe_remote_get($oembed);
                    if (!is_wp_error($response)) {
                        $getValues = wp_remote_retrieve_body($response);
                        $decodeiFrame = substr($getValues, 1, -2);
                        $jsonObj = json_decode($decodeiFrame);
                        echo str_replace('height="450"', 'height="150"', $jsonObj->html);
                    }
                }
            } else {
                echo -1;
            }
            exit();
        case "list":
            @$args['post_type'] = $request["post_type"] ?? '';
            if (!in_array($args['post_type'], $allowed_post_types, true)) {
                $result = ['error' => 'Invalid post type'];
                break;
            }
            switch ($args['post_type']) {
                case "event": 
                    $args['orderby'] = "meta_value_num";
                    $args['meta_query'] = array(
                        'relation' => 'AND',
                        array('key' => 'date_of_event', 'compare' => '>=', 'value' => date('Ymd'))
                    );
                break;
                case "gallery":
                    $args['post_type'] = "event";
                    $args['orderby'] = "meta_value_num";
                    $args['meta_query'] = array(
                        'relation' => 'AND',
                        array('key' => 'date_of_event', 'compare' => '<',  'value' => date('Ymd')),
                        array('key' => 'gallery_link',  'compare' => '!=', 'value' => '')
                    );
                break;
            }
            $query = new WP_Query($args);
            $posts = $query->get_posts();
            foreach($posts as &$post):
                $postData = [];
                $postData["id"] = $post->ID;
                $postData["post_name"] = $post->post_name;
                $postData["post_title"] = $post->post_title;
                $postData["image"] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];
                switch ($args['post_type']) {
                    case "event": 
                        $post->fields = get_fields($post->ID);
                        $postData["date_of_event"] = $post->fields["date_of_event"];
                        $primary_dj = new WP_Query(array(
                            'connected_type' => 'primary_dj',
                            'connected_items' => $post->ID,
                            'nopaging' => true,
                        ));
                        $postData["dj"] = $primary_dj->posts[0]->post_title;
                    break;
                }
                $post = $postData;
            endforeach;
            if (isset($request["limit"]) && $request["limit"] >= 0) {
                $post_count = count($posts);
                if (isset($request["rest"]) && $request["rest"] === "true") {
                    $posts = array_slice($posts, $request["limit"]);
                } else {
                    $posts = array_slice($posts, 0, $request["limit"]);
                }
                $limit = count($posts);
            }
            $result["posts"] = $posts;
            if (isset($request["limit"]) && $request["limit"] >= 0) {
                $result["post_count"] = $post_count;
                $result["post_limit"] = $limit;
            }
        break;
        case "get":
            $args['name'] = $request["name"] ?? '';
            $args['post_type'] = $request["post_type"] ?? '';
            if (!in_array($args['post_type'], $allowed_post_types, true)) {
                $result = ['error' => 'Invalid post type'];
                break;
            }
            if ($args['name'] === '') {
                $result = ['error' => 'Missing name'];
                break;
            }
            if ($args['post_type'] === 'gallery') {
                $args['post_type'] = "event";
            }
            $query = new WP_Query($args);
            $posts = $query->get_posts();
            foreach($posts as &$post):
                $postData = [];
                $postData["id"] = $post->ID;
                $postData["post_name"] = $post->post_name;
                $postData["post_title"] = $post->post_title;
                $postData["post_content"] = $post->post_content;
                $postData["image"] = get_the_post_thumbnail_url($post->ID);

                $post->fields = get_fields($post->ID);
                switch ($args['post_type']) {
                    case "event": 
                        $postData["date_of_event"] = $post->fields["date_of_event"];
                        $postData["background_image"] = $post->fields["background_image"]["url"]; // get a smaller image
                        $postData["start_time"] = $post->fields["start_time"];
                        $postData["end_time"] = $post->fields["end_time"];
                        $postData["venue_name"] = $post->fields["venue_name"];
                        $postData["venue_address"] = $post->fields["venue_address"];
                        $postData["ticket_link"] = $post->fields["ticket_link"];
                        $postData["soundcloud_link"] = $post->fields["soundcloud_link"];

                        if ($args['post_type'] === "event") {
                            $primary_dj = new WP_Query(array(
                                'connected_type' => 'primary_dj',
                                'connected_items' => $post->ID,
                                'nopaging' => true,
                            ));
                            $primary_dj = $primary_dj->posts;
                            if ($primary_dj) {
                                $primary_dj_data = [
                                    "ID" => $primary_dj[0]->ID,
                                    "post_name" => $primary_dj[0]->post_name,
                                    "post_title" => $primary_dj[0]->post_title,
                                    "post_content" => $primary_dj[0]->post_content,
                                    "post_image" => get_the_post_thumbnail_url($primary_dj[0]->ID)
                                ];
                            }
                            $postData["primary_dj"] = $primary_dj_data;

                            $secondary_djs = new WP_Query(array(
                                'connected_type' => 'secondary_dj',
                                'connected_items' => $post->ID,
                                'nopaging' => true,
                            ));
                            $secondary_djs = $secondary_djs->posts;
                            foreach($secondary_djs as $secondary_dj):
                                $secondary_djs_data[] = [
                                    "ID" => $secondary_dj->ID,
                                    "post_name" => $secondary_dj->post_name,
                                    "post_title" => $secondary_dj->post_title,
                                    "post_content" => $secondary_dj->post_content,
                                    "post_image" => get_the_post_thumbnail_url($secondary_dj->ID)
                                ];
                            endforeach;
                            $postData["secondary_djs"] = $secondary_djs_data;
                        }
                    break;
                    case "gallery":
                        $postData["gallery_link"] = $post->fields["gallery_link"];
                        if (isset($post->fields["gallery_link"])) {
                            $gallery = "http://".$post->fields["gallery_link"];
                            if ($gallery) {
                                $response = wp_safe_remote_get($gallery);
                                if (!is_wp_error($response)) {
                                    $images = wp_remote_retrieve_body($response);
                                    $dom = new DomDocument();
                                    $dom->loadHTML($images);
                                    foreach ($dom->getElementsByTagName('a') as $item) {
                                        if (strpos($item->getAttribute('href'), ".jpg") and !containsAnySubstring($item->getAttribute('href'), ["_small", "_medium", "_large"])) {
                                            $imageList[] = $item->getAttribute('href');
                                        }
                                    }
                                    $imageList = array_map(function($image) use ($gallery) {
                                        $pathinfo = pathinfo("$gallery/$image");
                                        return [
                                            "small" => $pathinfo["dirname"]."/".$pathinfo["filename"]."_small.".$pathinfo["extension"],
                                            "medium" => $pathinfo["dirname"]."/".$pathinfo["filename"]."_medium.".$pathinfo["extension"],
                                            "large" => $pathinfo["dirname"]."/".$pathinfo["filename"]."_large.".$pathinfo["extension"],
                                            "original" => "$gallery/$image"
                                        ];
                                    }, $imageList);
                                    $postData["images"] = $imageList;
                                }
                            }
                        }
                    break;
                    case "model":
                        $postData["instagram_link"] = $post->fields["instagram_link"];
                        $postData["height"] = $post->fields["height"];
                        $postData["weight"] = $post->fields["weight"];
                        $postData["birthday"] = $post->fields["birthday"];
                        $postData["hometown"] = $post->fields["hometown"];
                        $postData["active"] = $post->fields["active"];
                        $postData["photos"] = $post->fields["photos"];
                    break;
                    case "dj":
                        $postData["soundcloud_link"] = $post->fields["soundcloud_link"];
                        $postData["instagram_link"] = $post->fields["instagram_link"];
                        $postData["hometown"] = $post->fields["hometown"];
                        $postData["active"] = $post->fields["active"];
                        $postData["logo"] = $post->fields["logo"];
                        $postData["photos"] = $post->fields["photos"];
                    break;
                }
                $postData["styles"] = get_page_styles($post->ID);
                $post = $postData;
            endforeach;
            $result["posts"] = $posts;
        break;
        default:
            $result = ['error' => 'Invalid action'];
        break;
    }
        if ($_POST) {
            ob_start('ob_gzhandler');
            echo json_encode($result);
        }
    else if ($_GET)  {echo "<pre>"; print_r($result); echo "</pre>";}
}

function get_page_styles($page_id) {
    // Get the fully-rendered page as HTML
    $page_url = get_permalink($page_id);
    $response = wp_remote_get($page_url);

    if (is_wp_error($response)) {
        return [];
    }

    $html = wp_remote_retrieve_body($response);

    // Extract styles using regex
    preg_match_all('/<link.*?rel=["\']stylesheet["\'].*?href=["\'](.*?)["\'].*?>/i', $html, $matches);

    return !empty($matches[1]) ? array_unique($matches[1]) : [];
}