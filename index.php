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
function randomId($length = 10) {
    return substr(str_shuffle(str_repeat($x = '0123456789', ceil($length / strlen($x)))), 1, $length);
}

function query() {
    $qs = [];
    $components = explode("/", $_SERVER['REQUEST_URI']);
    @[$root, $page, $name] = $components;
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
<link rel="stylesheet" href="css/index.css?version=<?= randomId(4); ?>" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-75x75.png" sizes="32x32">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png" sizes="192x192">
<link rel="apple-touch-icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png">
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
<!-- End Google tag (gtag.js) -->
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1255364439052457');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1255364439052457&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<!-- Facebook verification -->
<meta name="facebook-domain-verification" content="ca9qnb8k0n5fkq5jfuw2unmwkxcj13" />
<!-- End Facebook verification -->
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
            <li><a class='nav' href="djs">DJs</a></li>
            <li><a class='nav' href="board">Board</a></li>
        </ul>
        <span>
            <a class='social' href="http://facebook.com/bosphilly" target="_blank"><i class="fab fa-facebook"></i></a>
            <a class='social' href="http://instagram.com/bosphilly" target="_blank"><i class="fab fa-instagram"></i></a>
            <a class='social' href="mailto:info@bosphilly.org" target="_blank"><i class="fas fa-envelope"></i></a>
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
"><div id="cssCage" style="display: block;"><?php
@get_header();
?>
<script>
let ruleList = $("#global-styles-inline-css")[0].sheet.cssRules;
for (let i = 0; i < ruleList.length; i++) {
    $("#global-styles-inline-css")[0].sheet.cssRules[i].selectorText = "#cssCage "+ruleList[i].selectorText
}
for (let i = 0; i < ruleList.length; i++) {
    console.log(ruleList[i].selectorText);
}
</script>
<?php
apply_filters('the_content', get_post_field('post_content', $page->id));
        print_r($page->post_content);
        ?></div></div><?php
    break;
    case "events":
        $args['name'] = query()["name"];
        $args['post_type'] = "event";
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        $event = $posts[0] ?? null;
        if ($event === null): ?> 
        <script>
        window.location.replace("..");
        </script>

        <?php endif;
        $event->fields = get_fields($event->ID);
        $event->image = get_the_post_thumbnail_url($event->ID);

        $primary_dj = new WP_Query(array(
            'connected_type' => 'primary_dj',
            'connected_items' => $event->ID,
            'nopaging' => true,
        ));

        $primary_dj = $primary_dj->posts[0] ?? null;
        if ($primary_dj !== null) {
            $primary_dj->post_image = get_the_post_thumbnail_url($primary_dj->ID);
            $event->primary_dj = (array)$primary_dj;
        }

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
            <link rel="stylesheet" href="css/event.css?version=<?= randomId(4); ?>" />
            <div class='shade'>
                <div class='title'>
                    <h1><?=$event["post_title"]?></h1>
                </div>
                <div class='images'>
                    <div>
                        <?php if (isset($event["primary_dj"])): ?>
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
                            <a href='<?=$event["fields"]["ticket_link"]?>' target="_blank"><button>Tickets</button></a>
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
            <link rel="stylesheet" href="css/gallery.css?version=<?= randomId(4); ?>" />
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
            <link rel="stylesheet" href="css/dj.css?version=<?= randomId(4); ?>" />
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
                                "small" => @$photo["media_details"]["sizes"]["thumbnail"]["source_url"],
                                "medium" => @$photo["media_details"]["sizes"]["medium"]["source_url"],
                                "large" => @$photo["media_details"]["sizes"]["large"]["source_url"],
                                "full" => @$photo["full_image_url"]
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
                            <a href="<?=$dj["fields"]["instagram_link"]?>" target="_blank"><i class="fab fa-instagram"></i>
                            &nbsp;
                                <?= array_slice(explode("/", $dj["fields"]["instagram_link"]), -2)[0] ?>
                            </a>
                        </button>
                    <?php endif; ?>
                    <?php if ($dj["fields"]["soundcloud_link"]): ?>
                        <button class='soundcloud'>
                            <a href="<?= $dj["fields"]["soundcloud_link"] ?>"><i class="fa-brands fa-soundcloud"></i>&nbsp;&nbsp;<?= $dj["post_title"] ?></a>
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
                    }/* ,
                    error: function(e) {
                        console.log(e);
                    } */
                });
                if (debug) console.log(soundcloud);
                if (soundcloud) {
                    $(".soundcloud").replaceWith(soundcloud);
                }
                else {
                    $(".soundcloud").remove();
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
            <source src="wordpress/body-shop-background" type="video/mp4" id="video">
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
        <h2><span class="counter" start="0.00" current="0.00" end="77497.33"></span></h2>
        <!-- last updated 2024/10/29 -->
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
    @$post->dj = $primary_dj->posts[0]->post_title;
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
            <?php if (isMobile()): ?>
                <div class='label'><?= $post->post_title ?></div>
            <?php else: ?>
                <div class="overlay"><div class="hover-text"><?= $post->post_title ?></div></div>
            <?php endif; ?>
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
            <?php if (isMobile()): ?>
                <div class='label'><?= $post->post_title ?></div>
            <?php else: ?>
                <div class="overlay"><div class="hover-text"><?= $post->post_title ?></div></div>
            <?php endif; ?>
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
                <a href="mailto:steve@bosphilly.org" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
                <a href="http://instagram.com/phillygaycalendar" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="tile">
            <img src="wordpress/board-matt" alt="Matt Rowe" loading="lazy" />
            <h3>Matt Rowe</h3>
            <h4>Director of Logistics</h4>
            <div class="contact">
                <a href="https://www.facebook.com/Mr.Breig" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:matt@bosphilly.org" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
                <a href="https://www.instagram.com/mr_breig/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="tile">
            <img src="wordpress/board-alex" alt="Alex Ortiz" loading="lazy" />
            <h3>Alex Ortiz</h3>
            <h4>Director of Outreach</h4>
            <div class="contact">
                <a href="https://www.facebook.com/AlexanderJohn" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:alex@bosphilly.org" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
                <a href="https://www.instagram.com/alexanderjawn/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="tile">
            <img src="wordpress/board-justin" alt="Justin Dile" loading="lazy" />
            <h3>Justin Dile</h3>
            <h4>Director of Coordination</h4>
            <div class="contact">
                <a href="https://www.facebook.com/justindile" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:justin@bosphilly.org" target="_blank"><i class="fa-fw far fa-envelope"></i></a>
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
        <div>
            <div><a href="https://circuitmom.com/" target="_blank"><img id="circuitmom" src="wordpress/partner-circuit-mom-logo" loading="lazy" /></a></div>
            <div><a href="https://www.waygay.org/" target="_blank"><img src="wordpress/partner-william-way-logo" loading="lazy" /></a></div>
            <div><a href="https://www.andrewchristian.com/" target="_blank"><img src="wordpress/partner-andrew-christian-logo" loading="lazy" /></a></div>
            <div><a href="https://qcareplus.com/" target="_blank"><img src="wordpress/partner-q-care-logo" loading="lazy" /></a></div>
        </div>
        <div>
            <div><a href="https://www.instagram.com/alexanderjohnphoto/" target="_blank"><img src="wordpress/partner-alexander-john-logo" loading="lazy" /></a></div>
            <div><a href="https://www.marriott.com/en-us/hotels/phlcc-fairfield-inn-and-suites-philadelphia-downtown-center-city/overview/" target="_blank"><img src="wordpress/partner-fairfield-marriott-logo" loading="lazy" /></a></div>
            <div><a href="https://www.sickening.events/" target="_blank"><img src="wordpress/partner-sickening-events-logo" loading="lazy" /></a></div>
        </div>
    </div>
</section>
<footer>
    <div>
        <h3>Stay Connected</h3>
        <p>Join the over 8,000 people who receive our regular newsletters about all the greatest events!</p>
        <div id="connect">
            <a class='social' href="http://facebook.com/bosphilly" target="_blank"><i class="fab fa-facebook"></i></a>
            <a class='social' href="http://instagram.com/bosphilly" target="_blank"><i class="fab fa-instagram"></i></a>
            <a class='social' href="mailto:info@bosphilly.org" target="_blank"><i class="fas fa-envelope"></i></a>
            <a class='social' href="https://soundcloud.com/bos-philly" target="_blank"><i class="fab fa-soundcloud"></i></a>
        </div>
        <div id="subscribe">
            <a href="https://arep.co/m/bosphilly"><button id="signup"><i class="fa-solid fa-envelope-open-text"></i> Newsletter</button></a>
            <a><button id="calendar"><i class="fa-solid fa-calendar-days"></i> Calendar</button></a>
        </div>
    </div>
    <div>
        <h3>Committed To Philadelphia</h3>
        <p>BOS Philly brings circuit parties to Philadelphia with international DJs and top-notch production while supporting local LGBT charities. Our events are unique and inclusive, creating an unforgettable experience. Join us to make a positive impact on the community while dancing the night away.</p>
        <p><a class='learn-more' href="https://www.bosphilly.org/board/">Learn more about us >></a></p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pluralize/8.0.0/pluralize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/konami@1.6.3/konami.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    $(`#djs .more`).click(async () => {
        let offset = $(`#djs .tile`).length;
        let result = await getPages("dj", offset, true);
        let pages = result.posts;
        if (pages.length) loadPages(pages, "dj");
        $(`#djs .more`).hide();
    });
}

String.prototype.replaceAt = function(index, replacement) {
    return this.substring(0, index) + replacement + this.substring(index + replacement.length);
}
String.prototype.splice = function(idx, rem, str) {
    return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
};


let hasFired = false;
let increment = 1000;
let USDollar = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});
let nums = [0,1,2,3,4,5,6,7,8,9];
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
/* 
        let count = 0, flutter = 0;
        let interval = setInterval(() => {
            let text = ""+Math.round(parseFloat($(".counter").attr("end")), 2);
            if (count % 150 - (flutter * 10) === 0) flutter++;
            for(let i = flutter; i < text.length; i++) {
                if (text[i].match(/\d/) !== null) {
                    text = text.replaceAt(i, ""+nums[Math.floor(Math.random()*10)]);
                }
            }
            $(".counter").text(USDollar.format(text));
            count++;
            if (flutter === text.length) clearInterval(interval);
        }, 15);
*/
        let flutter = 0;
        let interval = setInterval(() => {
            let value = Math.round(parseFloat($(".counter").attr("current")), 2);
            if (value + increment <= end) {
                value += increment;
                $(".counter").attr("current", Math.round(value, 2));
                let text = $(".counter").attr("current");
                text = USDollar.format(text).slice(1);
                for(let i = flutter; i < text.length; i++) {
                    if (text[i].match(/\d/) !== null) {
                        text = text.replaceAt(i, ""+nums[Math.floor(Math.random()*10)])
                    }
                }
                $(".counter").text("$"+text);
            }
            else if (increment > .01) {
                increment /= 10;
                flutter++;
            }
            else {
                clearInterval(interval);
                $(".counter").text(USDollar.format(end))
            }
        }, 30);
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
    window.history.pushState({}, null, `${window.location.origin}/${section}${window.location.search}`);
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

    loadTiles();

    let route = query();
    if (route.page) scrollToSection(route.page, 100);

    $('#calendar').click(function() {
        Swal.fire({
            title: "<strong>Live Calendar</strong>",
            icon: "info",
            html: `Stay up to date with our latest events by subscribing to our live calendar. Just click below and it will open in your default calendar app.`,
            confirmButtonText: `
<a href="webcal://calendar.google.com/calendar/ical/c_e5ccfcf9265560b5a19219e3e0cc2047926d5adb287c163e59322c00137ec065%40group.calendar.google.com/public/basic.ics">Subscribe</a>
  `,
            iconHtml: `<i class="fa-solid fa-calendar-days"></i>`,
            showCloseButton: true,
            showCancelButton: true,
            iconColor: "#ed208b",
            confirmButtonColor: "#ed208b",
        });
    });
    

    const easterEgg = new Konami(() => {
        $(document.body).toggleClass("konami")
    });
});

<?php endif; ?>
</script>
</body>
</html>