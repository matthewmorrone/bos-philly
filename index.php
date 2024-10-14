<?php
error_reporting(0);
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ("wordpress/wp-config.php");
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
function containsAnySubstring($string, $substrings) {
    foreach ($substrings as $substring) {
        if (strpos($string, $substring) !== false) {
            return true;
        }
    }
    return false;
}
function query() {
    $qs = []; 
    [$root, $page, $name] = explode("/", $_SERVER['REQUEST_URI']); 
    $qs["page"] = $page; 
    $qs["name"] = $name;
    return $qs;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=.9, viewport-fit=cover">
<base href="/" />
<title>BOS Philly - Bringing Circuit Back to Philly</title>
<link rel="stylesheet" href="css/index.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="https://bosphilly.com/wp-content/uploads/2022/08/android-chrome-512x512-1-75x75.png" sizes="32x32">
<link rel="icon" href="https://bosphilly.com/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png" sizes="192x192">
<link rel="apple-touch-icon" href="https://bosphilly.com/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E5VXE7X7M6"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-E5VXE7X7M6');
</script>
</head>
<body>
<header class="fixed">
    <a href="/" id="home">
        <img id="logo" src="wordpress/content-bos-logo" alt="BOS Logo">
    </a>
    <nav>
        <ul id="navbar">
            <li><a class='nav' href="events">Events</a></li>
            <li><a class='nav' href="galleries">Galleries</a></li>
            <li><a class='nav' href="models">Models</a></li>
            <li><a class='nav' href="djs">DJs</a></li>
            <li><a class='nav' href="board">Board</a></li>
        </ul>
        <span>
            <a class='social' href="http://facebook.com/bosphilly" target="_blank"><i class="fab fa-facebook"></i></a>
            <a class='social' href="http://instagram.com/bosphilly" target="_blank"><i class="fab fa-instagram"></i></a>
            <a class='social' href="mailto:info@bosphilly.com" target="_blank"><i class="fas fa-envelope"></i></a>
            <a class='social' href="https://soundcloud.com/bos-philly" target="_blank"><i class="fab fa-soundcloud"></i></a>
            <!-- <a class='social' id="mobileToggle"><i class="fa-solid fa-bars"></i></a> -->
        </span>
    </nav>
</header>
<?php if(query()["name"]): ?>
<section id="content">
<?php
switch(query()["page"]) {
    case "pages":
        $args['name'] = query()["name"];
        $args['post_type'] = "page";
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        $page = $posts[0];
        ?><pre><?php
        // print_r($page);
        ?></pre><?php
        ?><div style="
display: flex;
align-items: center;
justify-content: center;
"><div style="display: block;"><?php

apply_filters('the_content', get_post_field('post_content', $page->id));
        print_r($page->post_content);
        ?></div></div><?php
    break;
    case "events": 
        $args['name'] = query()["name"];
        $args['post_type'] = "event";
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        $event = $posts[0];
        $event->fields = get_fields($event->ID);
        $event->image = get_the_post_thumbnail_url($event->ID);

        $primary_dj = new WP_Query(array(
            'connected_type' => 'primary_dj',
            'connected_items' => $event->ID,
            'nopaging' => true,
        ));

        $primary_dj = $primary_dj->posts[0];
        $primary_dj->post_image = get_the_post_thumbnail_url($primary_dj->ID);
        $event->primary_dj = (array)$primary_dj;

        $secondary_djs = new WP_Query(array(
            'connected_type' => 'secondary_dj',
            'connected_items' => $event->ID,
            'nopaging' => true,
        ));
        $secondary_djs = $secondary_djs->posts;
        foreach($secondary_djs as &$secondary_dj):
            $secondary_dj = (array)$secondary_dj;
        endforeach;
        $event->secondary_djs = $secondary_djs;
        $event = (array)$event;
    ?>
       <div class='event-template'>
            <link rel="stylesheet" href="css/event.css" />
            <div class='shade'>
                <div class='title'>
                    <h1><?=$event["post_title"]?></h1>
                </div>
                <div class='images'>
                    <div>
                        <?php if ($event["primary_dj"]): ?>
                            <a href="djs/<?=$event["primary_dj"]["post_name"]?>">
                                <img src='<?=$event["primary_dj"]["post_image"]?>' class='feature' />
                                <h2><?=$event["primary_dj"]["post_title"]?> »</h2>
                            </a>
                        <?php else: ?>
                            <h2>DJ To Be Announced...</h2>
                        <?php endif; ?>
                    </div>
                    <div>
                        <img src='<?= $event["image"] ?>' class='feature' />
                        <div class='description'>
                            <?= $event["post_content"] ?>
                        </div>
                    </div>
                </div>
                <div class='info'>
                    <div class='ticket-panel'>
                        <div class='marker'><i class='fas fa-ticket'></i></div>
                        <div class='panel'>
                            <h3>Tickets</h3>
                        </div>
                        <div class='ticket-button'>
                        <?php if ($event["fields"]["ticket_link"]): ?>
                            <a href='<?=$event["fields"]["ticket_link"]?>'><button>Tickets</button></a>
                        <?php else: ?>
                            <button>Coming Soon</button>
                        <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class='marker'><i class='far fa-clock'></i></div>
                        <div class='panel'>
                            <h3>Time</h3>
                            <p><?= $event["fields"]["date_of_event"] ?></p>
                            <p><?= $event["fields"]["start_time"] ?> - <?= $event["fields"]["end_time"] ?></p>
                        </div>
                    </div>
                    <div>
                        <div class='marker'><i class="fas fa-map-marker-alt"></i></div>
                        <div class='panel'>
                            <h3>Location</h3>
                            <h4><?= $event["fields"]["venue_name"] ?></h4>
                            <p><a href='http://maps.google.com/?q="<?= $event["fields"]["venue_address"] ?>"'><?= $event["fields"]["venue_address"] ?></a></p>
                        </div>
                    </div>
                </div>
                <div class='map-embed'>
                    <iframe
                    title="<?= $event["fields"]["venue_address"] ?>"
                    class='map'
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCFs6ozWaQYHbXaQdd7EaRlJQDrioFxhdg
                        &q=<?= $event["fields"]["venue_address"] ?>"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="button-container">
                    <button class="more"><a href="events">More Events</a></button>
                </div>
            </div>
            <script>
            $(async () => {
                $("#content .event-template").css("background-image", `url('<?=$event["fields"]["background_image"]["url"]?>')`);
                $("title").text(`<?=$event["post_title"]?> - BOS Philly`);
            });
            </script>
        </div>
    <?php
    break;
    case "galleries": 
        $args['name'] = query()["name"];
        $args['post_type'] = "event";
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        $gallery = $posts[0];
        $gallery->fields = get_fields($gallery->ID);
        $gallery = (array)$gallery;
        if (isset($gallery["fields"]["gallery_link"])) {
            $gallery_url = "https://".$gallery["fields"]["gallery_link"];
            $images = file_get_contents($gallery_url);
            if ($images) {
                $dom = new DomDocument();
                $dom->loadHTML($images);
                foreach ($dom->getElementsByTagName('a') as $item) {
                    if (!strpos($item->getAttribute('href'), ".jpg") or containsAnySubstring($item->getAttribute('href'), ["_small", "_medium", "_large"])) {
                        continue;
                    }
                    $imageList[] = $item->getAttribute('href');
                }
                $imageList = array_map(function($image) use ($gallery_url) {
                    $pathinfo = pathinfo("$gallery_url/$image");
                    return [
                        "small" => $pathinfo["dirname"]."/".$pathinfo["filename"]."_small.".$pathinfo["extension"],
                        "medium" => $pathinfo["dirname"]."/".$pathinfo["filename"]."_medium.".$pathinfo["extension"],
                        "large" => $pathinfo["dirname"]."/".$pathinfo["filename"]."_large.".$pathinfo["extension"],
                        "original" => "$gallery_url/$image"
                    ];
                }, $imageList);
                $gallery["images"] = $imageList;
            }
            // echo "<pre>"; print_r($gallery); echo "</pre>";
        }
    ?>
        <div class='gallery-template'>
            <link rel="stylesheet" href="css/gallery.css" />
            <div class='gallery-content'>
                <h1><?= $gallery["post_title"] ?></h1>
            </div>
            <div class='photo-gallery'>
                <?php foreach($gallery["images"] as $photo): ?>
                    <div class='photo'>
                        <a href='<?= $photo["large"] ?>' data-lightbox='<?= $gallery["post_name"] ?>'>
                            <img src='<?= $photo["medium"] ?>' loading="lazy" />
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="button-container">
                <button class="more"><a href="galleries">More Galleries</a></button>
            </div>
        </div>
        <script>
        $(async () => {
            $("title").text(`<?= $gallery["post_title"]?> - BOS Philly`);
        });
        </script>
    <?php
    break;
    case "models": 
        $args['name'] = query()["name"];
        $args['post_type'] = "model";
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        $model = $posts[0];
        $model->fields = get_fields(post_id: $model->ID);
        $model->image = get_the_post_thumbnail_url($model->ID);
        $model = (array)$model;
    ?>
        <div class='model-template'>
            <link rel="stylesheet" href="css/model.css" />
            <div id="particle-background"></div>
            <div class='model-content'>
                <div class='model-image'>
                    <img src='<?= $model["image"] ?>' class='featured' />
                </div>
                <div class='model-description'>
                    <h1><?= $model["post_title"] ?></h1>
                    <h3>Height: <?= $model["fields"]["height"] ?></h3>
                    <h3>Weight: <?= $model["fields"]["weight"] ?></h3>
                    <p><?= $model["post_content"] ?></p>
                    <?php if ($model["fields"]["instagram_link"]): ?>
                        <button class='instagram'>
                            <a href="https://www.instagram.com/<?=$model["fields"]["instagram_link"]?>/" target="_blank"><i class="fab fa-instagram"></i> 
                                <?= array_slice(explode("/", $model["fields"]["instagram_link"]), -1)[0] ?>
                            </a>
                        </button>
                    <?php endif; ?>
                </div>
                <?php if ($model["fields"]["photos"]): ?>
                    <?php 
                        $model["fields"]["photos"] = array_map(function($photo) {
                            return [
                                "small" => $photo["media_details"]["sizes"]["thumbnail"]["source_url"],
                                "medium" => $photo["media_details"]["sizes"]["medium"]["source_url"],
                                "large" => $photo["media_details"]["sizes"]["large"]["source_url"],
                                "full" => $photo["full_image_url"]
                            ];
                        }, $model["fields"]["photos"]);
                    ?>
                    <div class='gallery'>
                    <?php foreach($model["fields"]["photos"] as $photo): ?>
                        <a href='<?= $photo["large"] ?>' data-lightbox='<?= $model["post_name"] ?>'>
                            <img src='<?= $photo["small"] ?>' data-lightbox='<?= $model["post_name"] ?>' loading="lazy" />
                        </a>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="button-container">
                <button class="more"><a href="models">More Models</a></button>
            </div>
            <script>
            $(async () => {
                $("title").text(`<?=$model["post_title"]?> - BOS Philly`);
                particlesJS.load("particle-background", "css/model-particles.json", function() {
                    $("#particle-background canvas").height($(".model-content").height() + $(".button-container").height() + 100);
                    $("#particle-background").css("display", "inline");
                });
            });
            </script>
        </div>
    <?php
    break;
    case "djs": 
        $args['name'] = query()["name"];
        $args['post_type'] = "dj";
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        $dj = $posts[0];
        $dj->fields = get_fields(post_id: $dj->ID);
        $dj->image = get_the_post_thumbnail_url($dj->ID);
        $dj = (array)$dj;
    ?>
        <div class='dj-template'>
            <link rel="stylesheet" href="css/dj.css" />
            <?php if ($dj["fields"]["logo"]): ?>
                <div class='banner'>
                    <img src='<?= $dj["fields"]["logo"]["url"] ?>' class='logo' />
                </div>
            <?php endif; ?>
            <div id="particle-background"></div>
            <div class='dj-content'>
                <div class='dj-header'>
                    <h2><?= $dj["post_title"] ?></h2>
                    <h3><?= $dj["fields"]["hometown"] ?></h3>
                </div>
                <div class='dj-left'>
                    <img src='<?= $dj["image"] ?>' class='featured' />
                    <?php if ($dj["fields"]["photos"]): ?>
                    <?php 
                        $dj["fields"]["photos"] = array_map(function($photo) {
                            return [
                                "small" => $photo["media_details"]["sizes"]["thumbnail"]["source_url"],
                                "medium" => $photo["media_details"]["sizes"]["medium"]["source_url"],
                                "large" => $photo["media_details"]["sizes"]["large"]["source_url"],
                                "full" => $photo["full_image_url"]
                            ];
                        }, $dj["fields"]["photos"]);
                    ?>
                    <div class='gallery'>
                    <?php foreach($dj["fields"]["photos"] as $photo): ?>
                        <a href='<?= $photo["large"] ?>' data-lightbox='<?= $dj["post_name"] ?>'>
                            <img src='<?= $photo["small"] ?>' data-lightbox='<?= $dj["post_name"] ?>' loading="lazy" />
                        </a>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
                <div class='dj-right'>
                    <div class='description'><?= $dj["post_content"] ?></div>
                    <?php if ($dj["fields"]["instagram_link"]): ?>
                        <button class='instagram'>
                            <a href="https://www.instagram.com/<?=$dj["fields"]["instagram_link"]?>/" target="_blank"><i class="fab fa-instagram"></i> 
                                <?= array_slice(explode("/", $dj["fields"]["instagram_link"]), -2)[0] ?>
                            </a>
                        </button>
                    <?php endif; ?>
                    <?php if ($dj["fields"]["soundcloud_link"]): ?>
                        <button class='soundcloud'>
                            <a href="<?= $dj["fields"]["soundcloud_link"] ?>"><i class="fa-brands fa-soundcloud"></i> <?= $dj["post_title"] ?></a>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="button-container">
                <button class="more"><a href="djs">More DJs</a></button>
            </div>
            <script>
            $(async () => {
                let soundcloud = await $.ajax({
                    url: 'wp.php',
                    type: 'POST',
                    dataType: "text",
                    data: {
                        action: "soundcloud",
                        url: $(".soundcloud a").attr("href")
                    }
                });
                if (debug) console.log(soundcloud);
                if (soundcloud) {
                    $(".soundcloud").replaceWith(soundcloud);
                }

                $("title").text(`<?= $dj["post_title"] ?> - BOS Philly`);

                particlesJS.load("particle-background", "css/dj-particles.json", function() {
                    $("#particle-background canvas").height($(".dj-content").height() + $(".button-container").height() + 100);
                    $("#particle-background").css("display", "inline");
                });
            });
            </script>
        </div>
    <?php
    break;
}
?>
</section>
<?php endif; ?>
<?php if(!query()["name"]): ?>
<section id="splash">
    <div class='splash-background'>
        <video preload autoplay loop muted playsinline poster="">
            <source src="wordpress/content-splash" type="video/mp4" id="video">
        </video>
    </div>
    <div class="splash-title">
        <h2>BOS PHILLY</h2>
        <p>Bringing Circuit back to Philly!</p>
    </div>
</section>
<section id="charity">
    <div class="charity-background">
        <div id='parallax'></div>
    </div>
    <div class="charity-title">
        <h2><span class="counter" start="0.00" current="0.00" end="75430.37"></span></h2>
        <p class="counter-title">Donated to local LGBT Charities since 2018</p>
    </div>
</section>
<section id="events">
    <h1>Events</h1>
    <div class="grid">
<?php
$args = [];
$args['post_status'] = "publish";
$args['post_type'] = "event";
$args['meta_key'] = "date_of_event";
$args['orderby'] = "meta_value";
$args['order'] = "ASC";
$args['meta_query'] = array(
    'relation' => 'AND',
    array('key' => 'date_of_event', 'compare' => '>=', 'value' => date('Ymd'))
);
$query = new WP_Query($args);
$posts = $query->get_posts();
foreach($posts as &$post):
    $post->image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];
    $post->fields = get_fields($post->ID);
    $primary_dj = new WP_Query(array(
        'connected_type' => 'primary_dj',
        'connected_items' => $post->ID,
        'nopaging' => true,
    ));
    $post->dj = $primary_dj->posts[0]->post_title;
    ?>
    <div class="tile container">
        <a href="events/<?= $post->post_name ?>"><img src="<?= $post->image ?>" loading="lazy" /></a>
        <a href="events/<?= $post->post_name ?>"><h3><?= $post->post_title ?></h3></a>
        <a href="events/<?= $post->post_name ?>"><h4><?= $post->fields["date_of_event"] ?></h4></a>
        <?php if ($post->dj): ?>
            <a href="events/<?= $post->post_name ?>"><h4><?= $post->dj ?></h4></a>
        <?php endif; ?>
        <a href="events/<?= $post->post_name ?>"><button class='tickets'>Tickets</button></a>
    </div>
    <?php
endforeach;
    ?>
    </div>
    <div class="button-container">
        <button class="more" href="event" style="display: none;">More Events</button>
    </div>
</section>
<section id="galleries">
    <h1>Galleries</h1>
    <div class="grid">
<?php
$args = [];
$args['post_status'] = "publish";
$args['post_type'] = "event";
$args['posts_per_page'] = 12;
$args['orderby'] = "meta_value_num";
$args['meta_query'] = array(
    'relation' => 'AND',
    array('key' => 'date_of_event', 'compare' => '<',  'value' => date('Ymd')),
    array('key' => 'gallery_link',  'compare' => '!=', 'value' => '')
);
$query = new WP_Query($args);
$posts = $query->get_posts();
foreach($posts as &$post):
    $post->image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];
    $post->fields = get_fields($post->ID);
    ?>
    <div class="tile container">
        <a href="galleries/<?= $post->post_name ?>">
            <img src="<?= $post->image ?>" class="hover" loading="lazy" />
            <div class='label'><?= $post->post_title ?></div>
            <div class="overlay"><div class="hover-text"><?= $post->post_title ?></div></div>
        </a>
    </div>
    <?php
endforeach;
?>
    </div>
    <div class="button-container">
        <button class="more" href="gallery">More Galleries</button>
    </div>
</section>
<section id="models">
    <h1>Models</h1>
    <div class="grid">
<?php
$args = [];
$args['post_status'] = "publish";
$args['post_type'] = "model";
$args['posts_per_page'] = 6;
$query = new WP_Query($args);
$posts = $query->get_posts();
foreach($posts as &$post):
    $post->image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];
    $post->fields = get_fields($post->ID);
    ?>
    <div class="tile container">
        <a href="models/<?= $post->post_name ?>">
            <img src="<?= $post->image ?>" class="hover" loading="lazy" />
            <div class='label'><?= $post->post_title ?></div>
            <div class="overlay"><div class="hover-text"><?= $post->post_title ?></div></div>
        </a>
    </div>
    <?php
endforeach;
?>
    </div>
    <div class="button-container">
        <button class="more" href="model">More Models</button>
    </div>
</section>
<section id="djs">
    <h1>DJs</h1>
    <div class="grid">
<?php
$args = [];
$args['post_status'] = "publish";
$args['post_type'] = "dj";
$args['posts_per_page'] = isMobile() ? 6 : 4;
$query = new WP_Query($args);
$posts = $query->get_posts();
foreach($posts as &$post):
    $post->image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];
    $post->fields = get_fields($post->ID);
    ?>
    <div class="tile container">
        <a href="djs/<?= $post->post_name ?>">
            <img src="<?= $post->image ?>" class="hover" loading="lazy" />
            <div class='label'><?= $post->post_title ?></div>
            <div class="overlay"><div class="hover-text"><?= $post->post_title ?></div></div>
        </a>
    </div>
    <?php
endforeach;
?>
    </div>
    <div class="button-container">
        <button class="more" href="dj">More DJs</button>
    </div>
</section>
<section id="board">
    <h1>Board</h1>
    <img src="wordpress/content-separator" id="separator" />
    <div class="grid">
        <div class="tile">
            <img src="wordpress/board-steve" alt="Steve McCann" loading="lazy" />
            <h3>Steve McCann</h3>
            <h4>Director of Business</h4>
            <div class="contact">
                <a href="https://www.facebook.com/steve.mccann" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:steve@bosphilly.com" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
                <a href="http://instagram.com/phillygaycalendar" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="tile">
            <img src="wordpress/board-matt" alt="Matt Rowe" loading="lazy" />
            <h3>Matt Rowe</h3>
            <h4>Director of Logistics</h4>
            <div class="contact">
                <a href="https://www.facebook.com/Mr.Breig" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:matt@bosphilly.com" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
                <a href="https://www.instagram.com/mr_breig/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="tile">
            <img src="wordpress/board-alex" alt="Alex Ortiz" loading="lazy" />
            <h3>Alex Ortiz</h3>
            <h4>Director of Outreach</h4>
            <div class="contact">
                <a href="https://www.facebook.com/AlexanderJohn" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:alex@bosphily.com" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
                <a href="https://www.instagram.com/alexanderjawn/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="tile">
            <img src="wordpress/board-justin" alt="Justin Dile" loading="lazy" />
            <h3>Justin Dile</h3>
            <h4>Director of Coordination</h4>
            <div class="contact">
                <a href="https://www.facebook.com/justindile" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:justin@bosphilly.com" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
                <a href="https://www.instagram.com/justindile/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="button-container">
        <!-- <button class="more" href="board">About Us...</button> -->
    </div>
</section>
<?php endif; ?>
<section id="partners">
    <div class="title"><h1>Partners:</h1></div>
    <div id="partners-grid">
        <div><a href="https://circuitmom.com/" target="_blank"><img id="circuitmom" src="wordpress/partner-circuit-mom-logo" loading="lazy" /></a></div>
        <div><a href="https://www.waygay.org/" target="_blank"><img src="wordpress/partner-william-way-logo" loading="lazy" /></a></div>
        <div><a href="https://www.andrewchristian.com/" target="_blank"><img src="wordpress/partner-andrew-christian-logo" loading="lazy" /></a></div>
        <div><a href="https://qcareplus.com/" target="_blank"><img src="wordpress/partner-q-care-logo" loading="lazy" /></a></div>
        <div><a href="https://www.instagram.com/alexanderjohnphoto/" target="_blank"><img src="wordpress/partner-alexander-john-logo" loading="lazy" /></a></div>
        <div><a href="https://americanharvestvodka.com/" target="_blank"><img src="wordpress/partner-american-harvest-logo" loading="lazy" /></a></div>
        <div><a href="https://www.marriott.com/en-us/hotels/phlcc-fairfield-inn-and-suites-philadelphia-downtown-center-city/overview/" target="_blank"><img src="wordpress/partner-fairfield-marriott-logo" loading="lazy" /></a></div>
        <div><a href="https://www.sickening.events/" target="_blank"><img src="wordpress/partner-sickening-events-logo" loading="lazy" /></a></div>
    </div>
</section>
<footer>
    <div>
        <h3>Stay Connected</h3>
        <p>Join the over 8,000 people who receive our regular newsletters about all the greatest events!</p>
        <span>
            <a class='social' href="http://facebook.com/bosphilly" target="_blank"><i class="fab fa-facebook"></i></a>
            <a class='social' href="http://instagram.com/bosphilly" target="_blank"><i class="fab fa-instagram"></i></a>
            <a class='social' href="mailto:info@bosphilly.com" target="_blank"><i class="fas fa-envelope"></i></a>
            <a class='social' href="https://soundcloud.com/bos-philly" target="_blank"><i class="fab fa-soundcloud"></i></a>
        </span>
        <a href="https://arep.co/m/bosphilly"><button id="signup"><i class="fa-solid fa-envelope-open-text"></i> Subscribe Here</button></a>
    </div>
    <div>
        <h3>Committed To Philadelphia</h3>
        <p>BOS Philly brings circuit parties to Philadelphia with international DJs and top-notch production while supporting local LGBT charities. Our events are unique and inclusive, creating an unforgettable experience. Join us to make a positive impact on the community while dancing the night away.</p>
        <p><a class='learn-more' href="https://www.bosphilly.com/board/">Learn more about us >></a></p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pluralize/8.0.0/pluralize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/konami@1.6.3/konami.min.js"></script>
<script src="utils.js"></script>
<script src="wp.js"></script>
<script>
let route = query();
debug = getQueryString().debug;
if (debug) console.log(getQueryString());

const sort = (a, b) => a.localeCompare(b, 'en', {numeric: true})

function adjustParticleBackground() {
    let increaseBy = $("#content").height() - $("#particle-background canvas").height();
    $("#particle-background canvas").css({
        width: $("#particle-background canvas").width()+increaseBy,
        height: $("#particle-background canvas").height()+increaseBy
    });
}
<?php if(!query()["name"]): ?>

async function loadTiles() {
    function loadPages(pages, url) {
        pages = pages.map(page => {
            return {
                name: page.post_title,
                url: `${pluralize.plural(url)}/${page.post_name}`,
                image: page.image || ''
            }
        });
        const isMobile = window.matchMedia("(width < 600px)").matches;
        $(`#${pluralize.plural(url)} .grid`).append(pages.map(page => {
            return `<div class="tile container">
                <a href="${page.url}">
                <img src="${page.image}" class="hover" loading="lazy" />
                ${isMobile 
                    ? `<div class='label'>${page.name}</div>` 
                    : `<div class="overlay"><div class="hover-text">${page.name}</div></div>`}
                </a>
            </div>`;
        }));
    }

    function loadGalleries(galleries) {
        if (debug) console.log("galleries: ", galleries);
        galleries = galleries.map(gallery => {
            return {
                name: gallery.post_title,
                date: gallery.date_of_event,
                timestamp: luxon.DateTime.fromMillis(Date.parse(gallery.date_of_event)),
                url: `galleries/${gallery.post_name}`,
                image: gallery.image
            }
        });
        galleries = galleries.sort(function(a, b) {
            return a.timestamp > b.timestamp ? -1 : a.timestamp < b.timestamp ? 1 : 0;
        });
        const isMobile = window.matchMedia("(width < 600px)").matches;
        $("#galleries .grid").append(galleries.map(gallery => {
            return `<div class="tile container">
                <a href="${gallery.url}">
                    <img src="${gallery.image}" class="hover" loading="lazy" />
                    ${isMobile 
                        ? `<div class='label'>${gallery.name}</div>` 
                        : `<div class="overlay"><div class="hover-text">${gallery.name}</div></div>`}
                </a>
            </div>`;
        }));
    }

    $("#galleries .more").click(async () => {
        let offset = $("#galleries .tile").length;
        let result = await getPages("gallery", offset, true);
        let galleries = result.posts;
        if (galleries.length) loadGalleries(galleries);
        $("#galleries .more").hide();
    });

    $(`#models .more`).click(async () => {
        let offset = $(`#models .tile`).length;
        let result = await getPages("model", offset, true);
        let pages = result.posts;
        if (pages.length) loadPages(pages, "model");
        $(`#models .more`).hide();
    });

    $(`#djs .more`).click(async () => {
        let offset = $(`#djs .tile`).length;
        let result = await getPages("dj", offset, true);
        let pages = result.posts;
        if (pages.length) loadPages(pages, "dj");
        $(`#djs .more`).hide();
    });

}

let hasFired = false;
let increment = 1000;
let USDollar = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

$(window).scroll(() => {
    function elementScrolled(elem) {
        let docViewTop = $(window).scrollTop();
        let docViewBottom = docViewTop + $(window).height();
        let elemTop = $(elem).offset().top;
        return ((elemTop <= docViewBottom) && (elemTop >= docViewTop));
    }
    if (elementScrolled('#charity')) {
        if (hasFired) return;
        let start = Math.round(parseFloat($(".counter").attr("start")), 2);
        let end = Math.round(parseFloat($(".counter").attr("end")), 2);
        $(".counter").text(start);

        let interval = setInterval(() => {
            let value = Math.round(parseFloat($(".counter").attr("current")), 2);
            if (value + increment <= end) {
                value += increment;
                $(".counter").attr("current", Math.round(value, 2));
                $(".counter").text(USDollar.format($(".counter").attr("current")))
            }
            else if (increment > .001) {
                increment /= 10;
            }
            else {
                clearInterval(interval);
            }
        }, 15);
        hasFired = true;
    }
}).scroll();

// parallax image movement
let currentZoom = 1;
let minZoom = 1;
let maxZoom = 2;
let stepSize = 0.005;
let deviceWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
let mobileScrollDirection = 1;

function zoomImage(direction) {
    let newZoom = currentZoom + direction * stepSize;
    if (newZoom < minZoom || newZoom > maxZoom) return;
    currentZoom = newZoom;
    let image = document.querySelector('#parallax');
    image.style.transform = 'scale(' + currentZoom + ')';
}
function parallax(event) {
    let direction = event.deltaY > 0 ? 1 : -1;
    if (deviceWidth <= 600) direction = mobileScrollDirection;
    zoomImage(direction);
}
window.addEventListener('touchstart', function(e) {
    start = e.changedTouches[0];
});
window.addEventListener('touchmove', function(e) {
    let end = e.changedTouches[0];
    mobileScrollDirection = end.screenY - start.screenY > 0 ? -1 : 1
});
['wheel', 'scroll', 'touchmove']
    .forEach(event => document.querySelector('body').addEventListener(event, parallax, false));


function scrollTo(element, to, duration) {
    const start = element.scrollTop;
    const change = to - start;
    const increment = 20;

    let currentTime = 0;

    const animateScroll = function() {
        currentTime += increment;
        const val = Math.easeInOutQuad(currentTime, start, change, duration);
        element.scrollTop = val;
        if (currentTime < duration) {
            setTimeout(animateScroll, increment);
        }
    };
    animateScroll();
}
Math.easeInOutQuad = function(t, b, c, d) {
    t /= d / 2;
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c / 2 * (t * (t - 2) - 1) + b;
};
function scrollToSection(section, offset) {
    // window.history.pushState({}, null, `${window.location.origin}/${section}${window.location.search}`);
    const target = document.querySelector(`#${section}`);
    if (!target) return
    const offsetTop = target.offsetTop - $("header").height() + offset;
    scrollTo(document.documentElement, offsetTop, 500);
    $("title").text(`${section.toTitleCase()} - BOS Philly`);
}
document.querySelectorAll('.nav').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        let section = this.getAttribute('href');
        scrollToSection(section, 0);
    });
});

$(async () => {
    $.ajaxSetup({cache: false});
    $(window).on("resize", () => $("#splash video").width($("#splash").width())).resize();

    const isMobile = window.matchMedia("(width < 600px)").matches;
    if (isMobile) $(".overlay").remove()
/* 
    $("header").after($("#navbar"));
    $("#mobileToggle").click(() => {
        $("#navbar").slideToggle();
    });
*/
    loadTiles();

    let route = query();
    if (route.page) scrollToSection(route.page, 100);

    const easterEgg = new Konami(() => {
        $(document.body).toggleClass("konami")
    });
});
<?php endif; ?>
</script>
</body>
</html>