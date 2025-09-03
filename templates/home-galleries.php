<section id="galleries">
    <h1>Galleries</h1>
    <div class="grid">
<?php
$args = [];
$args['post_status'] = "publish";
$args['post_type'] = "event";
$args['posts_per_page'] = 12;
$args['meta_key'] = 'date_of_event';
$args['orderby'] = 'meta_value_num';
$args['order'] = 'DESC'; // most recent past events first
$args['meta_query'] = array(
    'relation' => 'AND',
    array('key' => 'date_of_event', 'compare' => '<',  'value' => date('Ymd')),
    array('key' => 'gallery_link',  'compare' => '!=', 'value' => '')
);
$query = new WP_Query($args);
// echo "<script>console.log(" . json_encode($args) . ");</script>";
$posts = $query->get_posts();
// echo "<script>console.log(" . json_encode($posts) . ");</script>";
foreach($posts as &$post):
    $post->image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];
    $post->fields = get_fields($post->ID);
    ?>
    <div class="tile container no-hover">
        <a href="galleries/<?= $post->post_name ?>">
            <img src="<?= $post->image ?>" loading="lazy" alt="<?= esc_attr($post->post_title) ?>" />
            <div class='tile-caption'><?= $post->post_title ?></div>
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
