<?php
error_reporting(0);
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ("wordpress/wp-config.php");
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", @$_SERVER["HTTP_USER_AGENT"]);
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
    $qs["page"] = sanitize_text_field($page);
    $qs["name"] = sanitize_text_field($name);
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
<!-- Facebook verification -->
<meta name="facebook-domain-verification" content="ca9qnb8k0n5fkq5jfuw2unmwkxcj13" />
<!-- End Facebook verification -->
</head>
<body>
<header class="fixed">
    <div class="header-inner">
        <a href="/" id="home">
            <img id="logo" src="wordpress/wp-content/uploads/content-bos-logo.png" alt="BOS Logo">
        </a>
        <nav>
            <?php if (!isMobile()): ?>
            <ul id="navbar">
                <li><a class='nav' href="events">Events</a></li>
                <li><a class='nav' href="galleries">Galleries</a></li>
                <li><a class='nav' href="djs">DJs</a></li>
                <li><a class='nav' href="board">Board</a></li>
            </ul>
            <?php endif; ?>
            <span>
                <a class='social' href="http://facebook.com/bosphilly" target="_blank"><i class="fab fa-facebook"></i></a>
                <a class='social' href="http://instagram.com/bosphilly" target="_blank"><i class="fab fa-instagram"></i></a>
                <a class='social' href="mailto:info@bosphilly.org" target="_blank"><i class="fas fa-envelope"></i></a>
                <a class='social' href="https://soundcloud.com/bos-philly" target="_blank"><i class="fab fa-soundcloud"></i></a>
                <a class='social' id="mobileToggle"><i class="fa-solid fa-bars"></i></a>
            </span>
        </nav>
    </div>
</header>
<?php if (isMobile()): ?>
    <ul id="navbar">
        <li><a class='nav' href="events">Events</a></li>
        <li><a class='nav' href="galleries">Galleries</a></li>
        <li><a class='nav' href="djs">DJs</a></li>
        <li><a class='nav' href="board">Board</a></li>
    </ul>
<?php endif; ?>
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
                            <a href='<?=$event["fields"]["ticket_link"]?>' target="_blank"><button>Ticket</button></a>
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
    case "blowouts":
        $args['name'] = query()["name"];
        $args['post_type'] = "blowout";
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        $blowout = $posts[0];
        $blowout->fields = get_fields($blowout->ID);
        ?>
        <?php
        $splash_background = $blowout->fields["splash_background"]["url"];
        $splash_content = $blowout->fields["splash_content"];
        $ticket_background = $blowout->fields["ticket_background"];
        $ticket_image = $blowout->fields["ticket_image"]["url"];
        $djs_background = $blowout->fields["djs_background"]["url"];
        $djs = $blowout->fields["djs"];
        foreach($djs as $dj):
            $dj->fields = get_fields($dj->ID);
            $dj->image = get_the_post_thumbnail_url($dj->ID);
        endforeach;
        $content = $blowout->fields["content"];
        $vip_background = $blowout->fields["vip_background"]["url"];
        $vip_content = $blowout->fields["vip_content"];
        $venue_name = $blowout->fields["venue_name"];
        $venue_background = $blowout->fields["venue_background"]["url"];
        $venue_content = $blowout->fields["venue_content"];
        $hotel_background = $blowout->fields["hotel_background"]["url"];
        $hotel_content = $blowout->fields["hotel_content"];
        $music_anchor = $blowout->fields["music_anchor"];
        $venue_anchor = $blowout->fields["venue_anchor"];
        $vip_anchor = $blowout->fields["vip_anchor"];
        $hotel_anchor = $blowout->fields["hotel_anchor"];
        ?>
<style>
* {
    list-style-type: none;
}
.bg-cover {
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    min-height: 100vh;
    padding: 20px 0;
}
#blowout-splash {
    position: relative;
    background-image: url("<?= $splash_background ?>");
    color: white;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    min-height: 100vh;
    padding: 40px 20px;
}
#blowout-splash::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1;
}
#blowout-splash > * {
    position: relative;
    z-index: 2;
}
/* Section-specific backgrounds */
#blowout-ticket  { background-image: url("<?= $ticket_background ?>"); }
#blowout-djs     { background-image: url("<?= $djs_background ?>"); }
#blowout-vip     { background-image: url("<?= $vip_background ?>"); }
#blowout-venue   { background-image: url("<?= $venue_background ?>"); }
#blowout-hotel   { background-image: url("<?= $hotel_background ?>"); }
#blowout-ticket,
#blowout-djs,
#blowout-vip,
#blowout-venue,
#blowout-hotel {
    color: white;
}
.shade {
    max-width: 90%;
    margin: 0 auto;
    padding: 20px 0;
}
.shade-fg {
    background-color: rgba(2, 1, 1, 0.85);
    padding: 20px;
    border-radius: 15px;
    margin: 20px 0;
}
.ticket-image {
    max-width: 90%;
    height: auto;
    margin: 20px;
    transition: transform 0.2s ease-in-out;
}
.ticket-image:hover {
    transform: rotate(3deg) scale(1.05);
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .bg-cover {
        background-position: center;
        background-attachment: scroll;
        padding: 10px 0;
        min-height: auto;
    }
    
    #blowout-splash {
        min-height: 80vh;
        padding: 20px 10px;
    }

    img {
        width: -webkit-fill-available;
        height: auto;
    }
    div {
        font-family: 'Work Sans', sans-serif;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
    }
    
    /* Make first image in splash section full width on mobile */
    #blowout-splash .shade p:first-child a img,
    #blowout-splash .shade p:first-child img {
        width: 100vw !important;
        max-width: 100vw !important;
        height: auto !important;
        margin-left: calc(-50vw + 50%) !important;
        margin-right: calc(-50vw + 50%) !important;
        object-fit: contain;
    }
    
    .shade {
        max-width: 95%;
        padding: 10px 0;
    }
    
    .shade-fg {
        padding: 25px;
        border-radius: 25px;
        margin: 10px 0;
    }
    
    .ticket-image {
        max-width: 85%;
        margin: 15px auto;
        display: block;
    }
    
    .ticket-image:hover {
        transform: scale(1.02);
    }
}

/* DJ Profile Styles */
.dj-profile {
    margin-bottom: 30px;
}

.dj-header {
    display: flex;
    align-items: flex-start;
    gap: 30px;
    margin-bottom: 0;
}

.dj-photo {
    flex: 0 0 250px;
}

.dj-photo img {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ed208b;
}

.dj-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding-top: 20px;
}

.dj-logo {
    margin-bottom: 15px;
}

.dj-logo img {
    max-width: 250px;
    max-height: 100px;
    object-fit: contain;
}

.dj-name {
    font-size: 2.2rem;
    color: #ed208b;
    font-weight: bold;
    margin-bottom: 20px;
}

.dj-content {
    text-align: left;
}

.dj-description {
    margin-bottom: 25px;
    line-height: 1.6;
}

.dj-description p {
    margin: 0;
    font-size: 1.2rem;
}

.dj-soundcloud {
    margin-top: 20px;
}

/* Mobile DJ Profile Styles */
@media (max-width: 768px) {
    .dj-profile {
        margin-bottom: 20px;
    }
    
    .dj-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 15px;
    }
    
    .dj-photo {
        flex: none;
        margin-bottom: 10px;
        width: 100%;
    }
    
    /* Keep DJ profile photo within shade container on mobile */
    .dj-photo img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        object-fit: cover;
        border-radius: 10px !important;
        border-width: 2px;
        max-height: 50vh;
    }
    
    .dj-info {
        align-items: center;
        padding-top: 0;
        width: 100%;
    }
    
    /* Make DJ logo full width on mobile */
    .dj-logo img {
        width: 100vw !important;
        max-width: 100vw !important;
        height: auto !important;
        margin-left: calc(-50vw + 50%) !important;
        margin-right: calc(-50vw + 50%) !important;
        object-fit: contain;
        max-height: 150px;
        background: rgba(0, 0, 0, 0.1);
        padding: 10px;
        border-radius: 5px;
    }
    
    .dj-name {
        font-size: 1.8rem;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .dj-content {
        text-align: center;
        width: 100%;
    }
    .dj-content strong {
        margin-top: 15px;
    }
    .dj-description p {
        /* font-size: 1rem; */

        line-height: 1.5;
    }
    
    .dj-soundcloud iframe {
        height: 120px;
        width: 100%;
    }
}
</style>
<div id="blowout-splash" class="bg-cover">
    <div class="shade">
        <?= $splash_content ?>
    </div>
</div>
<style>
#blowout-highlights {
    display: flex;
    justify-content: center;
    align-items: stretch;
    gap: 32px;
    background: #000;
    padding: 20px 0;
    margin: 0;
}
.highlight-tile {
    flex: 1 1 0;
    max-width: 220px;
    text-align: center;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 0;
    height: 100%;
    padding: 10px;
}
.highlight-tile img {
    width: 100%;
    max-width: 240px;
    margin-bottom: 12px;
    display: block;
    height: auto;
}
.highlight-label {
    font-size: 1.3rem;
    font-weight: bold;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 2.5em;
    width: 100%;
    box-sizing: border-box;
    height: 2.5em;
    line-height: 2.5em;
    text-align: center;
}
.center-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* Mobile Highlights Styles */
@media (max-width: 768px) {
    #blowout-highlights {
        flex-wrap: nowrap;
        gap: 8px;
        padding: 15px 5px;
        overflow-x: auto;
    }
    
    .highlight-tile {
        flex: 0 0 calc(25% - 6px);
        max-width: calc(25% - 6px);
        min-width: 80px;
        padding: 4px;
    }
    
    .highlight-tile img {
        max-width: 100%;
        margin-bottom: 6px;
    }
    
    .highlight-label {
        font-size: 0.9rem;
        min-height: 1.5em;
        height: 1.5em;
        line-height: 1.5em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .center-wrapper {
        padding: 15px 10px;
    }

    .dj-description {
        display: none;
    }
}

@media (max-width: 480px) {
    #blowout-highlights {
        gap: 4px;
        padding: 10px 2px;
    }
    
    .highlight-tile {
        flex: 0 0 calc(25% - 3px);
        max-width: calc(25% - 3px);
        min-width: 70px;
        padding: 2px;
    }
    
    .highlight-label {
        font-size: 0.8rem;
        min-height: 1.2em;
        height: 1.2em;
        line-height: 1.2em;
    }
}
</style>
<div id="blowout-highlights">
    <a href="/blowouts/<?= htmlspecialchars(query()['name']) ?>#blowout-djs" class="highlight-tile">
        <img src="<?= $music_anchor ?>" alt="Music" loading="lazy" />
        <div class="highlight-label">Music</div>
    </a>
    <a href="/blowouts/<?= htmlspecialchars(query()['name']) ?>#blowout-vip" class="highlight-tile">
        <img src="<?= $vip_anchor ?>" alt="VIP" loading="lazy" />
        <div class="highlight-label">VIP</div>
    </a>
    <a href="/blowouts/<?= htmlspecialchars(query()['name']) ?>#blowout-venue" class="highlight-tile">
        <img src="<?= $venue_anchor ?>" alt="Venue" loading="lazy" />
        <div class="highlight-label">Venue</div>
    </a>
    <a href="/blowouts/<?= htmlspecialchars(query()['name']) ?>#blowout-hotel" class="highlight-tile">
        <img src="<?= $hotel_anchor ?>" alt="Hotel" loading="lazy" />
        <div class="highlight-label">Hotel</div>
    </a>
</div>
<div id="blowout-ticket" class="bg-cover">
    <div class="center-wrapper">
        <a href="<?= $ticket_link ?>"><img class="ticket-image" src="<?= $ticket_image ?>" /></a>
    </div>
</div>
<!-- <div id="blowout-content" class="bg-cover">
    <div class="shade">
        <div class="shade-fg">
            <?= $content ?>
        </div>
    </div>
</div> -->
<div id="blowout-djs" class="bg-cover">
    <?php foreach($djs as $dj): ?>
        <div class="shade">
            <div class="shade-fg dj-profile">
                <div class="dj-header">
                    <div class="dj-photo">
                        <img src='<?=$dj->image?>' alt="<?=$dj->post_title?>" />
                    </div>
                    <div class="dj-info">
                        <div class="dj-logo">
                            <img src='<?=$dj->fields["logo"]["url"]?>' alt="<?=$dj->post_title?> Logo" />
                        </div>
                        <div class="dj-content">
                            <div class="dj-description">
                                <p><?php echo $dj->post_content; ?></p>
                            </div>
                            <?php if($dj->fields["soundcloud_link"]): ?>
                                <div class="dj-soundcloud">
                                    <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=<?php echo urlencode($dj->fields["soundcloud_link"]); ?>&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="center-wrapper">
        <a href="<?= $ticket_link ?>"><img class="ticket-image" src="<?= $ticket_image ?>" /></a>
    </div>
</div>
<div id="blowout-vip" class="bg-cover">
    <h1 class="section-title">VIP</h1>
    <div class="shade">
        <div class="shade-fg">
            <?= $vip_content ?>
        </div>
    </div>
    <div class="center-wrapper">
        <a href="<?= $ticket_link ?>"><img class="ticket-image" src="<?= $ticket_image ?>" /></a>
    </div>
</div>
<div id="blowout-venue" class="bg-cover">
    <h1 class="section-title">The Venue: <?= $venue_name ?></h1>
    <div class="shade">
        <div class="shade-fg">
            <?= $venue_content ?>
        </div>
    </div>
    <div class="center-wrapper">
        <a href="<?= $ticket_link ?>"><img class="ticket-image" src="<?= $ticket_image ?>" /></a>
    </div>
</div>
<div id="blowout-hotel" class="bg-cover">
    <h1 class="section-title">Hotel</h1>
    <style>
        #blowout-hotel p {
            text-align: center;
        }
        
        /* Section Title Styles */
        .section-title {
            text-align: center;
            color: white;
            font-size: 3rem;
            font-weight: bold;
            margin: 20px 0;
            padding: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }
        
        /* Button Styles */
        .button-container {
            text-align: center;
            padding: 20px;
        }
        
        .button-container .more {
            background-color: #ed208b;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .button-container .more:hover {
            background-color: #d41d7a;
            transform: translateY(-2px);
        }
        
        .button-container .more a {
            color: white;
            text-decoration: none;
        }
        
        /* Mobile Section Styles */
        @media (max-width: 768px) {
            .section-title {
                font-size: 2.2rem;
                margin: 15px 0;
                padding: 15px 10px;
                line-height: 1.2;
            }
            
            .button-container {
                padding: 15px 10px;
            }
            
            .button-container .more {
                padding: 10px 20px;
                font-size: 1rem;
                border-radius: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .section-title {
                font-size: 1.8rem;
                margin: 10px 0;
                padding: 10px 5px;
            }
            
            .button-container .more {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
        }
        
        /* General Mobile Typography */
        @media (max-width: 768px) {
            .shade-fg h1,
            .shade-fg h2 {
                font-size: 1.8rem;
                line-height: 1.3;
                margin-bottom: 15px;
            }
            
            .shade-fg h3 {
                font-size: 1.4rem;
                line-height: 1.3;
                margin-bottom: 12px;
            }
            
            .shade-fg p {
                /* font-size: 1rem; */
                line-height: 1.5;
                margin-bottom: 15px;
            }
            
            .shade-fg {
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 480px) {
            .shade-fg h1,
            .shade-fg h2 {
                font-size: 1.5rem;
            }
            
            .shade-fg h3 {
                font-size: 1.2rem;
            }
            
            .shade-fg p {
                font-size: 0.9rem;
                line-height: 1.4;
            }
        }
        
        /* Fix for background attachment on mobile */
        @media (max-width: 768px) {
            .bg-cover {
                background-attachment: scroll !important;
            }
        }
        
        /* Venue section specific styles */
        #blowout-venue .shade-fg img {
            display: none !important;
        }
        
        #blowout-venue .shade-fg p:first-child img:first-child {
            display: block !important;
        }
        
        #blowout-venue .shade-fg {
            font-size: 16px;
        }
        
        #blowout-hotel .shade-fg {
            font-size: 16px;
        }
        
        /* VIP section specific styles */
        #blowout-vip .shade-fg {
            font-size: 16px;
            line-height: 1.6;
        }
        
        #blowout-vip .shade-fg p {
            line-height: 1.6;
            margin-bottom: 16px;
        }
        
        #blowout-vip .shade-fg h1,
        #blowout-vip .shade-fg h2,
        #blowout-vip .shade-fg h3 {
            line-height: 1.4;
            margin-bottom: 16px;
        }
        
        /* Mobile VIP styles */
        @media (max-width: 768px) {
            #blowout-vip .shade-fg {
                line-height: 1.5;
            }
            
            #blowout-vip .shade-fg p {
                line-height: 1.5;
                margin-bottom: 15px;
            }
            
            #blowout-vip .shade-fg h1,
            #blowout-vip .shade-fg h2,
            #blowout-vip .shade-fg h3 {
                line-height: 1.3;
                margin-bottom: 15px;
            }
        }
        
        /* Protect Partners section from VIP styling */
        #partners * {
            line-height: initial !important;
        }
    </style>
    <div class="shade">
        <div class="shade-fg">
            <?= $hotel_content ?>
            <div class="button-container">
                <button class="more">
                    <a href="<?= $hotel_link ?>">$179 Per Night</a>
                </button>
            </div>
        </div>
    </div>
    <div class="center-wrapper">
        <a href="<?= $ticket_link ?>"><img class="ticket-image" src="<?= $ticket_image ?>" /></a>
    </div>
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
            $response = wp_safe_remote_get($gallery_url);
            if (!is_wp_error($response)) {
                $images = wp_remote_retrieve_body($response);
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
<section id="pandering">
    <div>
        <h2>Committed To Philadelphia</h2>
        <h3>BOS Philly, a 501c3, brings circuit parties to Philadelphia with international DJs and top-notch production while supporting local LGBT charities. Our events are unique and inclusive, creating an unforgettable experience. Join us to make a positive impact on the community while dancing the night away.</h3>
        <p><a href="about-us">Learn more about us >></a></p>
    </div>
    <style>
    #pandering {
        text-align: center;
    }
    #pandering div {
        display: flex;
        flex-flow: row wrap;
        justify-content: space-around;
    }
    #pandering h2, #pandering h3, #pandering p {
        margin: 20px;
        width: 50%;
    }
    #pandering h3 {
        font-weight: normal;
    }
    #pandering a {
        color: #ed208b;
    }
    </style>
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
    array('key' => 'date_of_event', 'compare' => '>=', 'value' => date('Ymd', strtotime("yesterday")))
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
        <a href="events/<?= $post->post_name ?>"><button class='ticket'>Tickets</button></a>
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
        <button class="more" href="about-us">About Us...</button>
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


<?php // if(!query()["name"]): ?>

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

    $(`#board .more`).click(async () => {
        window.location.href = "about-us";
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
        try {
            let docViewTop = $(window).scrollTop();
            let docViewBottom = docViewTop + $(window).height();
            let elemTop = $(elem).offset().top;
            return ((elemTop <= docViewBottom) && (elemTop >= docViewTop));
        }
        catch (e) {
            return false;
        }
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
    if (image) image.style.transform = 'scale(' + currentZoom + ')';
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

    $("#mobileToggle").click(() => {
        $("#navbar").slideToggle();
    });

    let route = query();
    if (route.page === "about-us") {
        $("#splash, #charity, #pandering, #events, #galleries, #djs").remove();
        $("#board .button-container button, #board h1, #board #separator").remove();
        let page = await $get("board.html");
        $("#board").prepend(page);
    }
    // else if (route.page === "blowout") {
    //     console.log(route)
    //     let page = await getPageByName("page", route.name);
    //     console.log(page)
    // }
    else if (route.page) {
        console.log(route);
        // scrollToSection(route.page, 100);
    }
    else {
        loadTiles();
    }

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
</script>
<?php // endif; ?>
</body>
</html>