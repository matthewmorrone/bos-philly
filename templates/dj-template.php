<?php
$args['name'] = query()["name"];
$args['post_type'] = "dj";
$query = new WP_Query($args);
$posts = $query->get_posts();
$dj = $posts[0];
$dj->fields = get_fields(post_id: $dj->ID);
$dj->image = get_the_post_thumbnail_url($dj->ID);
$dj = (array)$dj;
?>
<div class='dj-template'>
    <link rel="stylesheet" href="css/dj.css?version=<?= randomId(4); ?>" />
    <?php if ($dj["fields"]["logo"]): ?>
        <div class='banner'>
            <img src='<?= $dj["fields"]["logo"]["url"] ?>' class='logo' />
        </div>
    <?php endif; ?>
    <div id="particle-background"></div>
    <div class='dj-content'>
        <div class='dj-header'>
            <h2><?= $dj["post_title"] ?></h2>
            <h3><?= $dj["fields"]["hometown"] ?></h3>
        </div>
        <div class='dj-left'>
            <img src='<?= $dj["image"] ?>' class='featured' />
            <?php if ($dj["fields"]["photos"]): ?>
            <?php
                $dj["fields"]["photos"] = array_map(function($photo) {
                    return [
                        "small" => @$photo["media_details"]["sizes"]["thumbnail"]["source_url"],
                        "medium" => @$photo["media_details"]["sizes"]["medium"]["source_url"],
                        "large" => @$photo["media_details"]["sizes"]["large"]["source_url"],
                        "full" => @$photo["full_image_url"]
                    ];
                }, $dj["fields"]["photos"]);
            ?>
            <div class='gallery'>
            <?php foreach($dj["fields"]["photos"] as $photo): ?>
                <a href='<?= $photo["large"] ?>' data-lightbox='<?= $dj["post_name"] ?>'>
                    <img src='<?= $photo["small"] ?>' data-lightbox='<?= $dj["post_name"] ?>' loading="lazy" />
                </a>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        </div>
        <div class='dj-right'>
            <div class='description'><?= $dj["post_content"] ?></div>
            <?php if ($dj["fields"]["instagram_link"]): ?>
                <button class='instagram'>
                    <a href="<?=$dj["fields"]["instagram_link"]?>" target="_blank">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" role="img" focusable="false"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1 .5.5.8.9 1 1.5.2.4.3 1.1.4 2.3.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5-.5.5-.9.8-1.5 1-.4.2-1.1.3-2.3.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1-.5-.5-.8-.9-1-1.5-.2-.4-.3-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5.5-.5.9-.8 1.5-1 .4-.2 1.1-.3 2.3-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1 .1-1.6.2-2 .3-.5.2-.8.4-1.1.7-.3.3-.5.6-.7 1.1-.1.4-.2 1-.3 2-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1 .2 1.6.3 2 .2.5.4.8.7 1.1.3.3.6.5 1.1.7.4.1 1 .2 2 .3 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1-.1 1.6-.2 2-.3.5-.2.8-.4 1.1-.7.3-.3.5-.6.7-1.1.1-.4.2-1 .3-2 .1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1-.2-1.6-.3-2-.2-.5-.4-.8-.7-1.1-.3-.3-.6-.5-1.1-.7-.4-.1-1-.2-2-.3-1.2-.1-1.6-.1-4.7-.1zm0 3.3a6.7 6.7 0 1 1 0 13.4 6.7 6.7 0 0 1 0-13.4zm0 1.8a4.9 4.9 0 1 0 0 9.8 4.9 4.9 0 0 0 0-9.8zm6.9-2.1a1.6 1.6 0 1 1-3.2 0 1.6 1.6 0 0 1 3.2 0z"/></svg>
                    &nbsp;
                        <?= array_slice(explode("/", $dj["fields"]["instagram_link"]), -2)[0] ?>
                    </a>
                </button>
            <?php endif; ?>
            <?php if ($dj["fields"]["soundcloud_link"]): ?>
                <button class='soundcloud'>
                    <a href="<?= $dj["fields"]["soundcloud_link"] ?>"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" role="img" focusable="false"><path d="M23.999 14.165c-.052 1.796-1.612 3.169-3.4 3.169h-8.18a.68.68 0 0 1-.675-.683V7.862a.747.747 0 0 1 .452-.724s.75-.513 2.333-.513a5.364 5.364 0 0 1 2.76 3.755 5.433 5.433 0 0 1 2.57 3.54c.282-.08.574-.121.868-.12.884 0 1.73.358 2.347.992s.948 1.49.922 2.373ZM10.721 8.421c.247 2.98.427 5.697 0 8.672a.264.264 0 0 1-.53 0c-.395-2.946-.22-5.718 0-8.672a.264.264 0 0 1 .53 0ZM9.072 9.448c.285 2.659.37 4.986-.006 7.655a.277.277 0 0 1-.55 0c-.331-2.63-.256-5.02 0-7.655a.277.277 0 0 1 .556 0Zm-1.663-.257c.27 2.726.39 5.171 0 7.904a.266.266 0 0 1-.532 0c-.38-2.69-.257-5.21 0-7.904a.266.266 0 0 1 .532 0Zm-1.647.77a26.108 26.108 0 0 1-.008 7.147.272.272 0 0 1-.542 0 27.955 27.955 0 0 1 0-7.147.275.275 0 0 1 .55 0Zm-1.67 1.769c.421 1.865.228 3.5-.029 5.388a.257.257 0 0 1-.514 0c-.21-1.858-.398-3.549 0-5.389a.272.272 0 0 1 .543 0Zm-1.655-.273c.388 1.897.26 3.508-.01 5.412-.026.28-.514.283-.54 0-.244-1.878-.347-3.54-.01-5.412a.283.283 0 0 1 .56 0Zm-1.668.911c.4 1.268.257 2.292-.026 3.572a.257.257 0 0 1-.514 0c-.241-1.262-.354-2.312-.023-3.572a.283.283 0 0 1 .563 0Z"/></svg>&nbsp;&nbsp;<?= $dj["post_title"] ?></a>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="button-container">
        <button class="more"><a href="djs">More DJs</a></button>
    </div>
    <script>
    $(async () => {
        let soundcloud = await $.ajax({
            url: 'wp.php',
            type: 'POST',
            dataType: "text",
            data: {
                action: "soundcloud",
                url: $(".soundcloud a").attr("href")
            }
        });
        if (debug) console.log(soundcloud);
        if (soundcloud) {
            $(".soundcloud").replaceWith(soundcloud);
        }
        else {
            $(".soundcloud").remove();
        }

        $("title").text(`<?= $dj["post_title"] ?> - BOS Philly`);

        particlesJS.load("particle-background", "css/dj-particles.json", function() {
            $("#particle-background canvas").height($(".dj-content").height() + $(".button-container").height() + 100);
            $("#particle-background").css("display", "inline");
        });
    });
    </script>
</div>
