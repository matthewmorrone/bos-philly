<?php
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
                <div class='marker'><svg aria-hidden="true" width="20" height="20" viewBox="0 0 576 512" role="img" focusable="false"><path d="M64 64C28.7 64 0 92.7 0 128l0 64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320l0 64c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-64c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6l0-64c0-35.3-28.7-64-64-64L64 64zm64 112l0 160c0 8.8 7.2 16 16 16l288 0c8.8 0 16-7.2 16-16l0-160c0-8.8-7.2-16-16-16l-288 0c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32l320 0c17.7 0 32 14.3 32 32l0 192c0 17.7-14.3 32-32 32l-320 0c-17.7 0-32-14.3-32-32l0-192z"/></svg></div>
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
                <div class='marker'><svg aria-hidden="true" width="20" height="20" viewBox="0 0 512 512" role="img" focusable="false"><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg></div>
                <div class='panel'>
                    <h3>Time</h3>
                    <p><?= $event["fields"]["date_of_event"] ?></p>
                    <p><?= $event["fields"]["start_time"] ?> - <?= $event["fields"]["end_time"] ?></p>
                </div>
            </div>
            <div>
                <div class='marker'><svg aria-hidden="true" width="20" height="20" viewBox="0 0 384 512" role="img" focusable="false"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg></div>
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
