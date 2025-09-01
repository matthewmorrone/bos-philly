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
                    <a href="<?=$dj["fields"]["instagram_link"]?>" target="_blank"><?= icon('instagram') ?>
                        <?= array_slice(explode("/", $dj["fields"]["instagram_link"]), -2)[0] ?>
                    </a>
                </button>
            <?php endif; ?>
            <?php if ($dj["fields"]["soundcloud_link"]): ?>
                <button class='soundcloud'>
                    <a href="<?= $dj["fields"]["soundcloud_link"] ?>"><?= icon('soundcloud') ?>&nbsp;&nbsp;<?= $dj["post_title"] ?></a>
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
