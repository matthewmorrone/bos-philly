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
        <div class="ticket-actions">
            <a href="events/<?= $post->post_name ?>"><button class='ticket'>More Info</button></a>
            <a href="<?= $post->fields["ticket_link"] ?>"><button class='ticket'>Tickets</button></a>
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
