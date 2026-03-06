<section id="splash">
    <?php $media_base = function_exists('bos_media_prod_base_url') ? bos_media_prod_base_url() : 'https://www.bosphilly.org'; ?>
    <div class='splash-background'>
        <video preload autoplay loop muted playsinline poster="">
            <source src="<?= esc_url($media_base . '/wordpress/body-shop-background'); ?>" type="video/mp4" id="video">
        </video>
    </div>
    <div class="splash-title">
        <h2>BOS PHILLY</h2>
        <p>Bringing Circuit back to Philly!</p>
    </div>
</section>
