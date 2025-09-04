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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/justifiedGallery/3.8.1/css/justifiedGallery.min.css" integrity="sha512-/L1YypZGk57GHZN1dbfdJ1IcNZ/ziEt2d45u/1cWh1cULWSjzFzP3XcS96pVWbn73tb+ca1gGGpo1st4j/wJaA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div class='gallery-content'>
        <h1><?= $gallery["post_title"] ?></h1>
    </div>
    <div class='photo-gallery'>
        <?php foreach($gallery["images"] as $photo): ?>
            <a href='<?= $photo["large"] ?>' data-lightbox='<?= $gallery["post_name"] ?>'>
                <img src='<?= $photo["medium"] ?>' loading="lazy" />
            </a>
        <?php endforeach; ?>
    </div>
    <div class="button-container">
        <button class="more"><a href="galleries">More Galleries</a></button>
    </div>
</div>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/justifiedGallery/3.8.1/js/jquery.justifiedGallery.min.js" integrity="sha512-eDmqS1xiUTtWrKJeNEgnC/LEqs2WIGeGZ7mSXAPh3divgLPgAvFp8ZUBKbjrKMg09uXBJgp7wa9u0edPFoG4Ng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script defer>
$(() => {
    $("title").text(`<?= $gallery["post_title"]?> - BOS Philly`);
    $('.photo-gallery').justifiedGallery({
        rowHeight: 220,
        margins: 0,
        lastRow: 'justify',
        captions: false
    });
});
</script>

