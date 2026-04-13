<section id="splash">
    <?php $media_base = function_exists('bos_media_prod_base_url') ? bos_media_prod_base_url() : 'https://www.bosphilly.org'; ?>
    <div class='splash-background'>
        <video preload="none" loop muted playsinline poster="<?= esc_url($media_base . '/wordpress/wp-content/uploads/BOS_Joe_mac_Creative_Pride_2024-335-scaled-1.jpg'); ?>">
            <source src="<?= esc_url($media_base . '/wordpress/wp-content/uploads/body-shop-background-1.mp4'); ?>" type="video/mp4" id="video">
        </video>
    </div>
    <div class="splash-title">
        <h2>BOS PHILLY</h2>
        <p>Bringing Circuit back to Philly!</p>
    </div>
    <script>
    // Lazy-load video: wait until page is idle, then start download + playback
    (function() {
        var video = document.querySelector('#splash video');
        if (!video) return;
        if ('requestIdleCallback' in window) {
            requestIdleCallback(function() { video.play(); });
        } else {
            setTimeout(function() { video.play(); }, 200);
        }
    })();
    </script>
</section>
