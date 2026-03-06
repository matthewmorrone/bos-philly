<section id="partners">
    <div class="title"><h1>Partners:</h1></div>
    <div id="partners-grid">
        <?php
        $partners = get_posts(array(
            'post_type' => 'partner',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        usort($partners, function ($first, $second) {
            $firstOrderRaw = get_post_meta($first->ID, 'partner_custom_order', true);
            $secondOrderRaw = get_post_meta($second->ID, 'partner_custom_order', true);

            $firstOrder = ($firstOrderRaw !== '' && is_numeric($firstOrderRaw)) ? (int) $firstOrderRaw : PHP_INT_MAX;
            $secondOrder = ($secondOrderRaw !== '' && is_numeric($secondOrderRaw)) ? (int) $secondOrderRaw : PHP_INT_MAX;

            if ($firstOrder === $secondOrder) {
                return strcasecmp($first->post_title, $second->post_title);
            }

            return $firstOrder <=> $secondOrder;
        });

        $partnerRows = array();
        $currentRow = array();

        foreach ($partners as $partner) {
            $partnerLogo = get_the_post_thumbnail_url($partner->ID, 'medium');
            if (!$partnerLogo) {
                continue;
            }

            $linebreakBefore = get_post_meta($partner->ID, 'partner_linebreak_before', true) === '1';

            if ($linebreakBefore && !empty($currentRow)) {
                $partnerRows[] = $currentRow;
                $currentRow = array();
            }

            if (count($currentRow) >= 3) {
                $partnerRows[] = $currentRow;
                $currentRow = array();
            }

            $currentRow[] = $partner;
        }

        if (!empty($currentRow)) {
            $partnerRows[] = $currentRow;
        }

        foreach ($partnerRows as $partnerRow):
            ?>
            <div>
                <?php foreach ($partnerRow as $partner): ?>
                    <?php
                    $partnerLogo = get_the_post_thumbnail_url($partner->ID, 'medium');
                    if (!$partnerLogo) {
                        continue;
                    }
                    $partnerLink = get_post_meta($partner->ID, 'partner_link', true);
                    ?>
                    <div>
                        <?php if (!empty($partnerLink)): ?>
                            <a href="<?= esc_url($partnerLink); ?>" target="_blank" rel="noopener noreferrer">
                                <img src="<?= esc_url($partnerLogo); ?>" loading="lazy" alt="<?= esc_attr(get_the_title($partner->ID)); ?>" />
                            </a>
                        <?php else: ?>
                            <img src="<?= esc_url($partnerLogo); ?>" loading="lazy" alt="<?= esc_attr(get_the_title($partner->ID)); ?>" />
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
