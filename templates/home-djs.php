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
    <div class="tile container no-hover">
        <a href="djs/<?= $post->post_name ?>">
            <img src="<?= $post->image ?>" loading="lazy" alt="<?= esc_attr($post->post_title) ?>" />
            <div class='tile-caption'><?= $post->post_title ?></div>
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
