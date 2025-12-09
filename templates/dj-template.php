<?php
$args['name'] = query()["name"];
$args['post_type'] = "dj";
$query = new WP_Query($args);
$posts = $query->get_posts();
$dj = $posts[0];
$dj->fields = get_fields(post_id: $dj->ID);
$dj_thumb_id = get_post_thumbnail_id($dj->ID);
$dj_img_full = wp_get_attachment_image_src($dj_thumb_id, 'full');
$dj->image = $dj_img_full[0] ?? '';
$dj->image_w = $dj_img_full[1] ?? null;
$dj->image_h = $dj_img_full[2] ?? null;
$dj->srcset = wp_get_attachment_image_srcset($dj_thumb_id, 'large');
$dj->sizes = '(max-width: 900px) 100vw, 50vw';
$dj = (array)$dj;
?>
<div class='dj-template'>
    <link rel="stylesheet" href="css/dj.css?version=<?= asset_version('css/dj.css'); ?>" />
    <!-- Lightbox CSS only when needed on DJ pages -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
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
            <img 
                src='<?= $dj["image"] ?>'
                <?php if (!empty($dj["srcset"])): ?>srcset='<?= esc_attr($dj["srcset"]) ?>'<?php endif; ?>
                sizes='<?= esc_attr($dj["sizes"]) ?>'
                <?php if(!empty($dj["image_w"]) && !empty($dj["image_h"])): ?>width='<?= (int)$dj["image_w"] ?>' height='<?= (int)$dj["image_h"] ?>'<?php endif; ?>
                alt='<?= htmlspecialchars($dj["post_title"]) ?>'
                class='featured'
                loading="lazy"
            />
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
                    <img 
                        src='<?= $photo["small"] ?>' 
                        srcset='<?= $photo["small"] ?> 320w, <?= $photo["medium"] ?> 640w, <?= $photo["large"] ?> 1024w'
                        sizes='(max-width: 768px) 45vw, 220px'
                        data-lightbox='<?= $dj["post_name"] ?>' 
                        loading="lazy" 
                        alt='<?= htmlspecialchars($dj["post_title"]) ?> photo'
                    />
                </a>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        </div>
        <div class='dj-right'>
            <div class='description'><?= $dj["post_content"] ?></div>
            <?php if ($dj["fields"]["instagram_link"]): ?>
                <button class='instagram'>
                    <a href="<?=$dj["fields"]["instagram_link"]?>" target="_blank"><?= svg_icon('instagram') ?>
                    &nbsp;
                        <?= array_slice(explode("/", $dj["fields"]["instagram_link"]), -2)[0] ?>
                    </a>
                </button>
            <?php endif; ?>
            <?php if ($dj["fields"]["soundcloud_link"]): ?>
                <button class='soundcloud'>
                    <a href="<?= $dj["fields"]["soundcloud_link"] ?>"><?= svg_icon('soundcloud') ?>&nbsp;&nbsp;<?= $dj["post_title"] ?></a>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="button-container">
        <button class="more"><a href="djs">More DJs</a></button>
    </div>
    <!-- Load particles.js only on DJ pages -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
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
