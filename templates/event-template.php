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
    // enrich with image and custom fields similar to primary_dj
    $secondary_dj->post_image = get_the_post_thumbnail_url($secondary_dj->ID);
    $secondary_dj->fields = get_fields($secondary_dj->ID);
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
            <div class='dj-area'>
                <div class='primary-dj'>
                <?php if (isset($event["primary_dj"])): ?>
                    <?php
                    echo "<script>console.log(" . json_encode($event) . ");</script>";
                    echo "<script>console.log(" . json_encode($primary_dj) . ");</script>";
                    echo "<script>console.log(" . json_encode($secondary_djs) . ");</script>";
                    ?>
                    <a href="djs/<?=$event["primary_dj"]["post_name"]?>">
                        <?php if (!empty($event["primary_dj"]["post_image"])): ?>
                            <img src='<?=$event["primary_dj"]["post_image"]?>' class='feature' />
                        <?php endif; ?>
                        <h2><?=$event["primary_dj"]["post_title"]?> »</h2>
                    </a>
                    <?php if (!empty($event["primary_dj"]["fields"]["instagram_link"])): ?>
                        <button class='instagram'>
                            <a href="<?=$event["primary_dj"]["fields"]["instagram_link"]?>" target="_blank"><?= svg_icon('instagram') ?>
                            &nbsp;
                                <?= array_slice(explode("/", $event["primary_dj"]["fields"]["instagram_link"]), -2)[0] ?>
                            </a>
                        </button>
                    <?php endif; ?>
                    <?php if (!empty($event["primary_dj"]["fields"]["soundcloud_link"])): ?>
                        <button class='soundcloud'>
                            <a href="<?= $event["primary_dj"]["fields"]["soundcloud_link"] ?>"><?= svg_icon('soundcloud') ?>&nbsp;&nbsp;<?= $event["primary_dj"]["post_title"] ?></a>
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <h2>DJ To Be Announced...</h2>
                <?php endif; ?>
                </div>

                <?php if (!empty($event["secondary_djs"])): ?>
                <div class='secondary-rail'>
                    <?php foreach ($event["secondary_djs"] as $sdj): ?>
                        <div class='sdj-card'>
                            <?php if (!empty($sdj["post_image"])): ?>
                                <a class='image-link' href="djs/<?= $sdj["post_name"] ?>">
                                    <img src='<?= $sdj["post_image"] ?>' alt='<?= htmlspecialchars($sdj["post_title"]) ?>' />
                                </a>
                            <?php endif; ?>
                            <a class='name' href="djs/<?= $sdj["post_name"] ?>">
                                <h4><?= $sdj["post_title"] ?> »</h4>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                <div class='marker'><?= svg_icon('ticket') ?></div>
                <div class='panel'>
                    <h3>Tickets</h3>
                </div>
                <div class='ticket-button'>
                <script src="https://tickets.bosphilly.com/ts_modal.js"></script>
                <?php if ($event["fields"]["ticket_link"]): ?>
                <a class='ticket-capture' target="_blank"><button id="modal-trigger-element-id">Ticket</button></a>
                <!-- href='<?=$event["fields"]["ticket_link"]?>'  -->
                <?php else: ?>
                    <button>Coming Soon</button>
                <?php endif; ?>
                </div>
            </div>
            <div>
                <div class='marker'><?= svg_icon('clock') ?></div>
                <div class='panel'>
                    <h3>Time</h3>
                    <p><?= $event["fields"]["date_of_event"] ?></p>
                    <p><?= $event["fields"]["start_time"] ?> - <?= $event["fields"]["end_time"] ?></p>
                </div>
            </div>
            <div>
                <div class='marker'><?= svg_icon('location-dot') ?></div>
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
    window.TSModals.buildModal({
        url: 'https://tickets.bosphilly.com/e/victory/tickets',
        modalTriggerElementId: 'modal-trigger-element-id'
    });
    </script>
</div>
