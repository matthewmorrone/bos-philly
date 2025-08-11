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
