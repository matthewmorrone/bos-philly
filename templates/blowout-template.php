<?php
$args['name'] = query()["name"];
$args['post_type'] = "blowout";
$query = new WP_Query($args);
$posts = $query->get_posts();
$blowout = $posts[0];
$blowout->fields = get_fields($blowout->ID);

/** @phpstan-ignore-start */
$splash_background = $blowout->fields["splash_background"]["url"];
$splash_content = $blowout->fields["splash_content"];
$ticket_background = $blowout->fields["ticket_background"];
$ticket_image = $blowout->fields["ticket_image"]["url"];
$ticket_link = $blowout->fields["ticket_link"];
$djs_background = $blowout->fields["djs_background"]["url"];
$djs = $blowout->fields["djs"];
foreach($djs as $dj):
    $dj->fields = get_fields($dj->ID);
    $dj->image = get_the_post_thumbnail_url($dj->ID);
    $dj->link = get_permalink($dj->ID);
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
$hotel_link = $blowout->fields["hotel_link"];
$hotel_anchor = $blowout->fields["hotel_anchor"];
/** @phpstan-ignore-end */
?>

<link rel="stylesheet" href="css/blowout.css?version=<?= randomId(4); ?>" />
<style>
/* Dynamic background images set via PHP */
#blowout-splash { background-image: url("<?= $splash_background ?>"); }
#blowout-ticket { background-image: url("<?= $ticket_background ?>"); }
#blowout-djs    { background-image: url("<?= $djs_background ?>"); }
#blowout-vip    { background-image: url("<?= $vip_background ?>"); }
#blowout-venue  { background-image: url("<?= $venue_background ?>"); }
#blowout-hotel  { background-image: url("<?= $hotel_background ?>"); }
</style>
<div id="blowout-splash" class="bg-cover">
    <div class="shade">
        <?= $splash_content ?>
    </div>
</div>
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
<div id="blowout-djs" class="bg-cover">
    <h1 class="section-title">Music</h1>
    <?php foreach($djs as $dj): ?>
        <div class="shade">
            <div class="shade-fg dj-profile">
                <div class="dj-header">
                    <a href="<?= $dj->link ?>">
                    <div class="dj-photo">
                        <img src='<?= $dj->image ?>' alt="<?= $dj->post_title ?>" />
                    </div>
                    <div class="dj-info">
                        <div class="dj-logo">
                            <img src='<?=$dj->fields["logo"]["url"]?>' alt="<?= $dj->post_title ?> Logo" />
                        </div>
                        <div class="dj-content">
                            <div class="dj-description">
                                <p><?= $dj->post_content ?></p>
                            </div>
                            <?php if($dj->soundcloud_link): ?>
                                <div class="dj-soundcloud">
                                    <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=<?php echo urlencode($dj->soundcloud_link); ?>&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    </a>
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
<!-- Floating Back to Top Button -->
<button class="floating-back-to-top" onclick="scrollToTop()">
    <?= icon('arrow-up') ?>
</button>
