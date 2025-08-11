<?php
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
