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
$event_thumb_id = get_post_thumbnail_id($event->ID);
$event_img_full = wp_get_attachment_image_src($event_thumb_id, 'full');
$event->image = $event_img_full[0] ?? '';
$event->image_w = $event_img_full[1] ?? null;
$event->image_h = $event_img_full[2] ?? null;
$event->srcset = wp_get_attachment_image_srcset($event_thumb_id, 'large');
$event->sizes = '(max-width: 900px) 100vw, 50vw';

$primary_dj = new WP_Query(array(
    'connected_type' => 'primary_dj',
    'connected_items' => $event->ID,
    'nopaging' => true,
));

$primary_dj = $primary_dj->posts[0] ?? null;
if ($primary_dj !== null) {
    $primary_dj_thumb_id = get_post_thumbnail_id($primary_dj->ID);
    $primary_dj_img = wp_get_attachment_image_src($primary_dj_thumb_id, 'large');
    $primary_dj->post_image = $primary_dj_img[0] ?? '';
    $primary_dj->image_w = $primary_dj_img[1] ?? null;
    $primary_dj->image_h = $primary_dj_img[2] ?? null;
    $primary_dj->srcset = wp_get_attachment_image_srcset($primary_dj_thumb_id, 'large');
    $primary_dj->sizes = '(max-width: 900px) 50vw, 25vw';
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
    <link rel="stylesheet" href="css/event.css?version=<?= asset_version('css/event.css'); ?>" />
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
                            <img 
                                src='<?=$event["primary_dj"]["post_image"]?>'
                                <?php if (!empty($event["primary_dj"]["srcset"])): ?>srcset='<?= esc_attr($event["primary_dj"]["srcset"]) ?>'<?php endif; ?>
                                sizes='<?= esc_attr($event["primary_dj"]["sizes"]) ?>'
                                <?php if(!empty($event["primary_dj"]["image_w"]) && !empty($event["primary_dj"]["image_h"])): ?>width='<?= (int)$event["primary_dj"]["image_w"] ?>' height='<?= (int)$event["primary_dj"]["image_h"] ?>'<?php endif; ?>
                                alt='<?= htmlspecialchars($event["primary_dj"]["post_title"]) ?>'
                                class='feature'
                                loading="lazy"
                            />
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
                                    <img src='<?= $sdj["post_image"] ?>' alt='<?= htmlspecialchars($sdj["post_title"]) ?>' loading="lazy" />
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
                <img 
                    src='<?= $event["image"] ?>'
                    <?php if (!empty($event["srcset"])): ?>srcset='<?= esc_attr($event["srcset"]) ?>'<?php endif; ?>
                    sizes='<?= esc_attr($event["sizes"]) ?>'
                    <?php if(!empty($event["image_w"]) && !empty($event["image_h"])): ?>width='<?= (int)$event["image_w"] ?>' height='<?= (int)$event["image_h"] ?>'<?php endif; ?>
                    alt='<?= htmlspecialchars($event["post_title"]) ?>'
                    class='feature'
                    fetchpriority="high"
                />
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
                    <a href='<?=$event["fields"]["ticket_link"]?>' target="_blank"><button id="modal-trigger-element-id">Ticket</button></a>
                <!-- class='ticket-capture'  -->
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
