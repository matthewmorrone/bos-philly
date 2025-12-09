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
?>
<script src="https://tickets.bosphilly.com/ts_modal.js"></script>
<?php
foreach($posts as &$post):
    $thumb_id = get_post_thumbnail_id($post->ID);
    $img_info = wp_get_attachment_image_src($thumb_id, 'large');
    $post->image = $img_info[0] ?? '';
    $post->image_w = $img_info[1] ?? null;
    $post->image_h = $img_info[2] ?? null;
    $post->srcset = wp_get_attachment_image_srcset($thumb_id, 'large');
    $post->sizes = '(max-width: 768px) 100vw, 33vw';
    $post->fields = get_fields($post->ID);
    $primary_dj = new WP_Query(array(
        'connected_type' => 'primary_dj',
        'connected_items' => $post->ID,
        'nopaging' => true,
    ));
    @$post->dj = $primary_dj->posts[0]->post_title;
    ?>
    <script>
    console.log(<?php echo wp_json_encode($post); ?>);
    </script>
    <?php
    ?>
    <div class="tile container">
        <a>
            <img 
                src="<?= $post->image ?>"
                <?php if (!empty($post->srcset)): ?>srcset="<?= esc_attr($post->srcset) ?>"<?php endif; ?>
                sizes="<?= esc_attr($post->sizes) ?>"
                <?php if(!empty($post->image_w) && !empty($post->image_h)): ?>width="<?= (int)$post->image_w ?>" height="<?= (int)$post->image_h ?>"<?php endif; ?>
                alt="<?= esc_attr($post->post_title) ?>"
                loading="lazy"
            />
        </a>
        <a><h3><?= $post->post_title ?></h3></a>
        <a><h4><?= $post->fields["date_of_event"] ?></h4></a>
        <?php if ($post->dj): ?>
            <a><h4><?= $post->dj ?></h4></a>
        <?php endif; ?>
        <div class="ticket-actions">
            <a href="events/<?= $post->post_name ?>"><button class='ticket'>More Info</button></a>
            <a href='<?= $post->fields["ticket_link"] ?>' id='modal-trigger-<?= $post->post_name ?>'><button class='ticket'>Tickets</button></a>
        </div>
    </div>
    <?php
endforeach;
    ?>
    </div>
    <div class="button-container">
        <button class="more" href="event" style="display: none;">More Events</button>
    </div>
</section>
