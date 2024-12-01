<?php
error_reporting(E_ERROR);
include ("wordpress/wp-config.php");
$_ = $_POST ?: $_GET; // extract($_);

function containsAnySubstring($string, $substrings) {
    foreach ($substrings as $substring) {
        if (strpos($string, $substring) !== false) {
            return true;
        }
    }
    return false;
}
function isUrlValid($url) {
    // Disable error reporting for file_get_contents
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);
    // Fetch the URL content
    $content = file_get_contents($url, false, $context);
    // Get the response headers
    $headers = $http_response_header;
    // Check if the response code contains "404"
    foreach ($headers as $header) {
        if (stripos($header, 'HTTP/1.1 404') !== false) {
            return false; // URL is invalid or returns a 404 error
        }
    }
    return true; // URL is valid
}

if (isset($_["debug"])) {
    echo "<pre>"; print_r($_); echo "</pre>";
}
if (isset($_["action"])) {
    $args = [];
    $args['post_status'] = "publish";
    $args['nopaging'] = true;

    switch ($_["action"]) {
        case "soundcloud":
            $url = $_["url"];
            if (isUrlValid($url)) {
                $url = "http://soundcloud.com/oembed?format=js&url=$url&iframe=true";
                if (isUrlValid($url)) {
                    $getValues = file_get_contents($url);
                    $decodeiFrame = substr($getValues, 1, -2);
                    $jsonObj = json_decode($decodeiFrame);
                    echo str_replace('height="450"', 'height="150"', $jsonObj->html);
                }
            }
            else {
                echo -1;
            }
            exit();
        case "list":
            @$args['post_type'] = $_["post_type"];
            switch ($_["post_type"]) {
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
                switch ($_["post_type"]) {
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
            if (@$_["limit"] >= 0) {
                $post_count = count($posts);
                if (@$_["rest"] === "true") $posts = array_slice($posts, @$_["limit"]);
                else                        $posts = array_slice($posts, 0, @$_["limit"]);
                $limit = count($posts);
            }
            $result["posts"] = $posts;
            if (@$_["limit"] >= 0) {
                $result["post_count"] = $post_count;
                $result["post_limit"] = $limit;
            }
        break;
        case "get":
            $args['name'] = $_["name"];
            $args['post_type'] = $_["post_type"];
            if (strcmp($_["post_type"], 'gallery') === 0) {
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
                switch ($_["post_type"]) {
                    case "event": 
                        $postData["date_of_event"] = $post->fields["date_of_event"];
                        $postData["background_image"] = $post->fields["background_image"]["url"]; // get a smaller image
                        $postData["start_time"] = $post->fields["start_time"];
                        $postData["end_time"] = $post->fields["end_time"];
                        $postData["venue_name"] = $post->fields["venue_name"];
                        $postData["venue_address"] = $post->fields["venue_address"];
                        $postData["ticket_link"] = $post->fields["ticket_link"];
                        $postData["soundcloud_link"] = $post->fields["soundcloud_link"];

                        if (strcmp($_["post_type"], "event") === 0) {
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
                                $images = file_get_contents($gallery);
                                if ($images) {
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
                $post = $postData;
            endforeach;
            $result["posts"] = $posts;
        break;
    }
        if ($_POST) {
            ob_start('ob_gzhandler');
            echo json_encode($result); 
        }
    else if ($_GET)  {echo "<pre>"; print_r($result); echo "</pre>";}
}

