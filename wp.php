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
            $getValues = file_get_contents("http://soundcloud.com/oembed?format=js&url=$url&iframe=true");
            $decodeiFrame = substr($getValues, 1, -2);
            $jsonObj = json_decode($decodeiFrame);
            echo str_replace('height="450"', 'height="150"', $jsonObj->html);
            exit();
        case "list":
            $args['post_type'] = $_["post_type"];
            if (strcmp($_["post_type"], 'event') === 0) {
                $args['orderby'] = "meta_value_num";
                $args['meta_query'] = array(
                    'relation' => 'AND',
                    array('key' => 'date_of_event', 'compare' => '>=', 'value' => date('Ymd'))
                );
            }
            if (strcmp($_["post_type"], 'gallery') === 0) {
                $args['post_type'] = "event";
                $args['orderby'] = "meta_value_num";
                $args['meta_query'] = array(
                    'relation' => 'AND',
                    array('key' => 'date_of_event', 'compare' => '<',  'value' => date('Ymd')),
                    array('key' => 'gallery_link',  'compare' => '!=', 'value' => '')
                );
            }
            $query = new WP_Query($args);
            $posts = $query->get_posts();
            foreach($posts as &$post):
                $post->fields = get_fields($post->ID);
                $post->image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium')[0];
            endforeach;
            if ($_["limit"] >= 0) {
                $post_count = count($posts);
                if ($_["rest"] === "true")  $posts = array_slice($posts, $_["limit"]);
                else                        $posts = array_slice($posts, 0, $_["limit"]);
                $limit = count($posts);
            }
            $result["posts"] = $posts;
            if ($_["limit"] >= 0) {
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
                $post->fields = get_fields($post->ID);
                $post->image = get_the_post_thumbnail_url($post->ID);

                if (isset($post->fields["gallery_link"])) {
                    $gallery = "http://".$post->fields["gallery_link"];
                    if ($gallery) {
                        $images = file_get_contents($gallery);

                        $dom = new DomDocument();
                        $dom->loadHTML($images);
                        foreach ($dom->getElementsByTagName('a') as $item) {
                            if (strpos($item->getAttribute('href'), ".jpg") and !containsAnySubstring($item->getAttribute('href'), ["_small", "_medium", "_large"])
                            ) {
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
                        $post->images = $imageList;
                    }
                }

                if (strcmp($_["post_type"], "event") === 0) {
                    $result = new WP_Query(array(
                        'connected_type' => 'primary_dj',
                        'connected_items' => $post->ID,
                        'nopaging' => true,
                    ));
                    $dj = $result->posts;
                    if ($dj) {
                        $dj_main = [
                            "ID" => $dj[0]->ID,
                            "post_name" => $dj[0]->post_name,
                            "post_title" => $dj[0]->post_title,
                            "post_content" => $dj[0]->post_content,
                            "post_image" => get_the_post_thumbnail_url($dj[0]->ID)
                        ];
                    }
                    $post->dj = $dj_main;

                    $result = new WP_Query(array(
                        'connected_type' => 'secondary_dj',
                        'connected_items' => $post->ID,
                        'nopaging' => true,
                    ));
                    $result = $result->posts;
                    foreach($result as $dj):
                        $djs[] = [
                            "ID" => $dj->ID,
                            "post_name" => $dj->post_name,
                            "post_title" => $dj->post_title,
                            "post_content" => $dj->post_content,
                            "post_image" => get_the_post_thumbnail_url($dj->ID)
                        ];
                    endforeach;
                    $post->djs = $djs;
                }

            endforeach;
            $result["posts"] = $posts;
        break;
    }
         if ($_POST) {echo json_encode($result); }
    else if ($_GET)  {echo "<pre>"; print_r($result); echo "</pre>";}
}

