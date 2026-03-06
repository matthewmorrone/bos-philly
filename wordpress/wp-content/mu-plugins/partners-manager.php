<?php
/**
 * Plugin Name: BOS Partners Manager
 * Description: Manage homepage partners in wp-admin (name, logo, link, order).
 */

if (!defined('ABSPATH')) {
    exit;
}

function bos_register_partner_post_type() {
    $labels = array(
        'name' => 'Partners',
        'singular_name' => 'Partner',
        'menu_name' => 'Partners',
        'name_admin_bar' => 'Partner',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Partner',
        'new_item' => 'New Partner',
        'edit_item' => 'Edit Partner',
        'view_item' => 'View Partner',
        'all_items' => 'All Partners',
        'search_items' => 'Search Partners',
        'not_found' => 'No partners found.',
        'not_found_in_trash' => 'No partners found in Trash.',
    );

    register_post_type('partner', array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'thumbnail', 'page-attributes'),
        'has_archive' => false,
        'rewrite' => false,
        'show_in_rest' => true,
    ));
}
add_action('init', 'bos_register_partner_post_type');

function bos_add_partner_meta_boxes() {
    add_meta_box(
        'bos_partner_link',
        'Partner Link',
        'bos_render_partner_link_meta_box',
        'partner',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'bos_add_partner_meta_boxes');

function bos_get_orderable_partner_posts() {
    $posts = get_posts(array(
        'post_type' => 'partner',
        'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));

    usort($posts, function ($first, $second) {
        $firstOrderRaw = get_post_meta($first->ID, 'partner_custom_order', true);
        $secondOrderRaw = get_post_meta($second->ID, 'partner_custom_order', true);

        $firstOrder = ($firstOrderRaw !== '' && is_numeric($firstOrderRaw)) ? (int) $firstOrderRaw : PHP_INT_MAX;
        $secondOrder = ($secondOrderRaw !== '' && is_numeric($secondOrderRaw)) ? (int) $secondOrderRaw : PHP_INT_MAX;

        if ($firstOrder !== $secondOrder) {
            return $firstOrder <=> $secondOrder;
        }

        $titleCompare = strcasecmp((string) $first->post_title, (string) $second->post_title);
        if ($titleCompare !== 0) {
            return $titleCompare;
        }

        return $first->ID <=> $second->ID;
    });

    return $posts;
}

function bos_normalize_partner_custom_order($preferredPostId = 0, $preferredPosition = null) {
    $posts = bos_get_orderable_partner_posts();
    if (empty($posts)) {
        return;
    }

    if (!empty($preferredPostId) && is_numeric($preferredPosition)) {
        $preferredPostId = (int) $preferredPostId;
        $preferredPosition = (int) $preferredPosition;

        if ($preferredPosition < 1) {
            $preferredPosition = 1;
        }

        $preferredPost = null;
        foreach ($posts as $index => $post) {
            if ((int) $post->ID === $preferredPostId) {
                $preferredPost = $post;
                unset($posts[$index]);
                break;
            }
        }

        if ($preferredPost) {
            $posts = array_values($posts);
            $insertIndex = min($preferredPosition - 1, count($posts));
            array_splice($posts, $insertIndex, 0, array($preferredPost));
        }
    }

    $normalizedOrder = 1;
    foreach ($posts as $post) {
        update_post_meta($post->ID, 'partner_custom_order', $normalizedOrder);
        $normalizedOrder++;
    }
}

function bos_render_partner_link_meta_box($post) {
    wp_nonce_field('bos_save_partner_link', 'bos_partner_link_nonce');
    $partner_link = get_post_meta($post->ID, 'partner_link', true);
    $partner_custom_order = get_post_meta($post->ID, 'partner_custom_order', true);
    $partner_linebreak_before = get_post_meta($post->ID, 'partner_linebreak_before', true);

    $orderablePartners = bos_get_orderable_partner_posts();
    $partnerCount = count($orderablePartners);
    $maxOrder = max(1, $partnerCount);
    $selectedOrder = ($partner_custom_order !== '' && is_numeric($partner_custom_order)) ? (int) $partner_custom_order : $maxOrder;
    if ($selectedOrder > $maxOrder) {
        $maxOrder = $selectedOrder;
    }
    ?>
    <p>
        <label for="bos_partner_link_field">Website URL</label>
    </p>
    <input
        type="url"
        id="bos_partner_link_field"
        name="bos_partner_link_field"
        value="<?php echo esc_attr($partner_link); ?>"
        placeholder="https://example.com"
        style="width: 100%;"
    />
    <p class="description">This URL is used when visitors click the partner logo.</p>

    <hr style="margin: 16px 0;" />

    <p>
        <label for="bos_partner_custom_order_field">Custom Order</label>
    </p>
    <select
        id="bos_partner_custom_order_field"
        name="bos_partner_custom_order_field"
        style="width: 140px;"
    >
        <?php for ($position = 1; $position <= $maxOrder; $position++): ?>
            <option value="<?php echo esc_attr($position); ?>" <?php selected($selectedOrder, $position); ?>>
                <?php echo esc_html($position); ?>
            </option>
        <?php endfor; ?>
    </select>
    <p class="description">Choose display position. Order is automatically normalized after saves and deletes.</p>

    <p style="margin-top: 14px;">
        <label>
            <input
                type="checkbox"
                name="bos_partner_linebreak_before_field"
                value="1"
                <?php checked($partner_linebreak_before, '1'); ?>
            />
            Start a new line before this partner
        </label>
    </p>
    <?php
}

function bos_save_partner_link_meta($post_id) {
    if (!isset($_POST['bos_partner_link_nonce']) || !wp_verify_nonce($_POST['bos_partner_link_nonce'], 'bos_save_partner_link')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $partner_link = isset($_POST['bos_partner_link_field']) ? esc_url_raw($_POST['bos_partner_link_field']) : '';
    if (!empty($partner_link)) {
        update_post_meta($post_id, 'partner_link', $partner_link);
    } else {
        delete_post_meta($post_id, 'partner_link');
    }

    $selectedOrder = isset($_POST['bos_partner_custom_order_field']) ? (int) $_POST['bos_partner_custom_order_field'] : 0;
    if ($selectedOrder < 1) {
        $selectedOrder = 1;
    }

    $partner_linebreak_before = isset($_POST['bos_partner_linebreak_before_field']) ? '1' : '0';
    if ($partner_linebreak_before === '1') {
        update_post_meta($post_id, 'partner_linebreak_before', '1');
    } else {
        delete_post_meta($post_id, 'partner_linebreak_before');
    }

    bos_normalize_partner_custom_order($post_id, $selectedOrder);
}
add_action('save_post_partner', 'bos_save_partner_link_meta');

function bos_normalize_partner_order_on_trash($post_id) {
    if (get_post_type($post_id) !== 'partner') {
        return;
    }

    bos_normalize_partner_custom_order();
}
add_action('trashed_post', 'bos_normalize_partner_order_on_trash');

function bos_normalize_partner_order_on_untrash($post_id) {
    if (get_post_type($post_id) !== 'partner') {
        return;
    }

    bos_normalize_partner_custom_order();
}
add_action('untrashed_post', 'bos_normalize_partner_order_on_untrash');

function bos_normalize_partner_order_on_delete($post_id) {
    if (get_post_type($post_id) !== 'partner') {
        return;
    }

    bos_normalize_partner_custom_order();
}
add_action('before_delete_post', 'bos_normalize_partner_order_on_delete');

function bos_partner_admin_columns($columns) {
    $newColumns = array();

    foreach ($columns as $key => $label) {
        $normalizedLabel = strtolower(trim(wp_strip_all_tags((string) $label)));
        if ($normalizedLabel === 'type') {
            continue;
        }

        $newColumns[$key] = $label;
        if ($key === 'title') {
            $newColumns['partner_custom_order'] = 'Custom Order';
            $newColumns['partner_linebreak_before'] = 'Line Break Before';
            $newColumns['partner_link'] = 'Partner Link';
        }
    }

    if (isset($newColumns['date'])) {
        unset($newColumns['date']);
    }
    if (isset($newColumns['type'])) {
        unset($newColumns['type']);
    }

    return $newColumns;
}
add_filter('manage_edit-partner_columns', 'bos_partner_admin_columns');

function bos_partner_admin_column_content($column, $post_id) {
    if ($column === 'partner_custom_order') {
        $value = get_post_meta($post_id, 'partner_custom_order', true);
        $value = $value !== '' ? (int) $value : 0;
        $moveNonce = wp_create_nonce('bos_partner_move_' . (int) $post_id);

        $upUrl = wp_nonce_url(
            admin_url('admin-post.php?action=bos_partner_move&post_id=' . (int) $post_id . '&direction=up'),
            'bos_partner_move_' . (int) $post_id
        );
        $downUrl = wp_nonce_url(
            admin_url('admin-post.php?action=bos_partner_move&post_id=' . (int) $post_id . '&direction=down'),
            'bos_partner_move_' . (int) $post_id
        );

        echo '<div style="display:flex;gap:8px;align-items:center;">';
        echo '<strong class="bos-partner-order-value">' . ($value > 0 ? esc_html((string) $value) : '—') . '</strong>';
        echo '<a href="' . esc_url($upUrl) . '" class="button button-small bos-partner-move" data-post-id="' . (int) $post_id . '" data-direction="up" data-nonce="' . esc_attr($moveNonce) . '" aria-label="Move partner up">↑</a>';
        echo '<a href="' . esc_url($downUrl) . '" class="button button-small bos-partner-move" data-post-id="' . (int) $post_id . '" data-direction="down" data-nonce="' . esc_attr($moveNonce) . '" aria-label="Move partner down">↓</a>';
        echo '</div>';
        return;
    }

    if ($column === 'partner_linebreak_before') {
        $value = get_post_meta($post_id, 'partner_linebreak_before', true);
        $linebreakNonce = wp_create_nonce('bos_partner_toggle_linebreak_' . (int) $post_id);

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" style="margin:0;">';
        wp_nonce_field('bos_partner_toggle_linebreak_' . (int) $post_id);
        echo '<input type="hidden" name="action" value="bos_partner_toggle_linebreak" />';
        echo '<input type="hidden" name="post_id" value="' . (int) $post_id . '" />';
        echo '<input type="hidden" name="redirect_to" value="' . esc_attr((string) wp_get_referer()) . '" />';
        echo '<label style="display:inline-flex;align-items:center;gap:6px;">';
        echo '<input type="checkbox" name="partner_linebreak_before" value="1" class="bos-partner-linebreak-toggle" data-post-id="' . (int) $post_id . '" data-nonce="' . esc_attr($linebreakNonce) . '" ' . checked($value, '1', false) . ' />';
        echo '</label>';
        echo '<noscript><button type="submit" class="button button-small" style="margin-left:8px;">Save</button></noscript>';
        echo '</form>';
        return;
    }

    if ($column === 'partner_link') {
        $value = get_post_meta($post_id, 'partner_link', true);
        if (!empty($value)) {
            echo '<a href="' . esc_url($value) . '" target="_blank" rel="noopener noreferrer">' . esc_html($value) . '</a>';
        } else {
            echo '—';
        }
    }
}
add_action('manage_partner_posts_custom_column', 'bos_partner_admin_column_content', 10, 2);

function bos_partner_move_action() {
    $postId = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
    $direction = isset($_GET['direction']) ? sanitize_key((string) $_GET['direction']) : '';

    if ($postId <= 0 || !in_array($direction, array('up', 'down'), true)) {
        wp_die('Invalid partner move request.');
    }

    if (!current_user_can('edit_post', $postId)) {
        wp_die('Permission denied.');
    }

    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce((string) $_GET['_wpnonce'], 'bos_partner_move_' . $postId)) {
        wp_die('Invalid nonce.');
    }

    if (get_post_type($postId) !== 'partner') {
        wp_die('Invalid post type.');
    }

    $posts = bos_get_orderable_partner_posts();
    $count = count($posts);
    $currentIndex = null;

    foreach ($posts as $index => $post) {
        if ((int) $post->ID === $postId) {
            $currentIndex = $index;
            break;
        }
    }

    if ($currentIndex !== null) {
        $preferredPosition = null;
        if ($direction === 'up' && $currentIndex > 0) {
            $preferredPosition = $currentIndex;
        }
        if ($direction === 'down' && $currentIndex < ($count - 1)) {
            $preferredPosition = $currentIndex + 2;
        }

        if ($preferredPosition !== null) {
            bos_normalize_partner_custom_order($postId, $preferredPosition);
        }
    }

    $redirect = wp_get_referer();
    if (!$redirect) {
        $redirect = admin_url('edit.php?post_type=partner');
    }
    wp_safe_redirect($redirect);
    exit;
}
add_action('admin_post_bos_partner_move', 'bos_partner_move_action');

function bos_partner_get_order_map() {
    $posts = bos_get_orderable_partner_posts();
    $orderMap = array();

    foreach ($posts as $post) {
        $value = get_post_meta($post->ID, 'partner_custom_order', true);
        $orderMap[(string) $post->ID] = ($value !== '' && is_numeric($value)) ? (int) $value : 0;
    }

    return $orderMap;
}

function bos_partner_move_ajax_action() {
    $postId = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    $direction = isset($_POST['direction']) ? sanitize_key((string) $_POST['direction']) : '';

    if ($postId <= 0 || !in_array($direction, array('up', 'down'), true)) {
        wp_send_json_error(array('message' => 'Invalid partner move request.'), 400);
    }

    if (!current_user_can('edit_post', $postId)) {
        wp_send_json_error(array('message' => 'Permission denied.'), 403);
    }

    $nonce = isset($_POST['_ajax_nonce']) ? (string) $_POST['_ajax_nonce'] : '';
    if (!wp_verify_nonce($nonce, 'bos_partner_move_' . $postId)) {
        wp_send_json_error(array('message' => 'Invalid nonce.'), 403);
    }

    if (get_post_type($postId) !== 'partner') {
        wp_send_json_error(array('message' => 'Invalid post type.'), 400);
    }

    $posts = bos_get_orderable_partner_posts();
    $count = count($posts);
    $currentIndex = null;

    foreach ($posts as $index => $post) {
        if ((int) $post->ID === $postId) {
            $currentIndex = $index;
            break;
        }
    }

    if ($currentIndex !== null) {
        $preferredPosition = null;
        if ($direction === 'up' && $currentIndex > 0) {
            $preferredPosition = $currentIndex;
        }
        if ($direction === 'down' && $currentIndex < ($count - 1)) {
            $preferredPosition = $currentIndex + 2;
        }

        if ($preferredPosition !== null) {
            bos_normalize_partner_custom_order($postId, $preferredPosition);
        }
    }

    wp_send_json_success(array(
        'orders' => bos_partner_get_order_map(),
    ));
}
add_action('wp_ajax_bos_partner_move', 'bos_partner_move_ajax_action');

function bos_partner_toggle_linebreak_ajax_action() {
    $postId = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if ($postId <= 0) {
        wp_send_json_error(array('message' => 'Invalid partner toggle request.'), 400);
    }

    if (!current_user_can('edit_post', $postId)) {
        wp_send_json_error(array('message' => 'Permission denied.'), 403);
    }

    $nonce = isset($_POST['_ajax_nonce']) ? (string) $_POST['_ajax_nonce'] : '';
    if (!wp_verify_nonce($nonce, 'bos_partner_toggle_linebreak_' . $postId)) {
        wp_send_json_error(array('message' => 'Invalid nonce.'), 403);
    }

    if (get_post_type($postId) !== 'partner') {
        wp_send_json_error(array('message' => 'Invalid post type.'), 400);
    }

    $enabled = isset($_POST['partner_linebreak_before']) && $_POST['partner_linebreak_before'] === '1';
    if ($enabled) {
        update_post_meta($postId, 'partner_linebreak_before', '1');
    } else {
        delete_post_meta($postId, 'partner_linebreak_before');
    }

    wp_send_json_success(array(
        'post_id' => $postId,
        'linebreak' => $enabled ? '1' : '0',
    ));
}
add_action('wp_ajax_bos_partner_toggle_linebreak', 'bos_partner_toggle_linebreak_ajax_action');

function bos_partner_toggle_linebreak_action() {
    $postId = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if ($postId <= 0) {
        wp_die('Invalid partner toggle request.');
    }

    if (!current_user_can('edit_post', $postId)) {
        wp_die('Permission denied.');
    }

    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce((string) $_POST['_wpnonce'], 'bos_partner_toggle_linebreak_' . $postId)) {
        wp_die('Invalid nonce.');
    }

    if (get_post_type($postId) !== 'partner') {
        wp_die('Invalid post type.');
    }

    if (isset($_POST['partner_linebreak_before'])) {
        update_post_meta($postId, 'partner_linebreak_before', '1');
    } else {
        delete_post_meta($postId, 'partner_linebreak_before');
    }

    $redirect = isset($_POST['redirect_to']) ? esc_url_raw((string) $_POST['redirect_to']) : '';
    if (empty($redirect)) {
        $redirect = admin_url('edit.php?post_type=partner');
    }
    wp_safe_redirect($redirect);
    exit;
}
add_action('admin_post_bos_partner_toggle_linebreak', 'bos_partner_toggle_linebreak_action');

function bos_partner_sortable_columns($columns) {
    $columns['partner_custom_order'] = 'partner_custom_order';
    return $columns;
}
add_filter('manage_edit-partner_sortable_columns', 'bos_partner_sortable_columns');

function bos_partner_custom_order_sorting($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') !== 'partner') {
        return;
    }

    $orderby = $query->get('orderby');

    if (empty($orderby)) {
        $query->set('orderby', 'partner_custom_order');
        $query->set('order', 'ASC');
        $orderby = 'partner_custom_order';
    }

    if ($orderby !== 'partner_custom_order') {
        return;
    }

    $query->set('meta_key', 'partner_custom_order');
    $query->set('orderby', 'meta_value_num');
}
add_action('pre_get_posts', 'bos_partner_custom_order_sorting');

function bos_partner_admin_inline_scripts() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->id !== 'edit-partner') {
        return;
    }
    ?>
    <script>
    (function() {
        const moveButtons = document.querySelectorAll('.bos-partner-move');
        const linebreakCheckboxes = document.querySelectorAll('.bos-partner-linebreak-toggle');
        if (!moveButtons.length || typeof ajaxurl === 'undefined') {
            if (!linebreakCheckboxes.length) {
                return;
            }
        }

        let latestMoveRequestId = 0;

        function setButtonLoading(button, isLoading) {
            if (!button) {
                return;
            }
            button.setAttribute('aria-busy', isLoading ? 'true' : 'false');
        }

        function getCurrentOrderMap() {
            const map = {};
            document.querySelectorAll('tr[id^="post-"]').forEach((row) => {
                const postId = row.id.replace('post-', '');
                const valueNode = row.querySelector('.column-partner_custom_order .bos-partner-order-value');
                if (!valueNode) {
                    return;
                }
                const parsed = parseInt(valueNode.textContent, 10);
                map[postId] = Number.isFinite(parsed) ? parsed : 0;
            });
            return map;
        }

        function createOptimisticOrderMap(currentMap, postId, direction) {
            const optimisticMap = { ...currentMap };
            const currentOrder = parseInt(optimisticMap[postId], 10);
            if (!Number.isFinite(currentOrder) || currentOrder <= 0) {
                return optimisticMap;
            }

            const targetOrder = direction === 'up' ? currentOrder - 1 : currentOrder + 1;
            if (targetOrder < 1) {
                return optimisticMap;
            }

            let swapPostId = null;
            Object.entries(optimisticMap).forEach(([id, order]) => {
                if (parseInt(order, 10) === targetOrder) {
                    swapPostId = id;
                }
            });

            if (!swapPostId) {
                return optimisticMap;
            }

            optimisticMap[postId] = targetOrder;
            optimisticMap[swapPostId] = currentOrder;
            return optimisticMap;
        }

        function applyOrderMap(orderMap) {
            if (!orderMap || typeof orderMap !== 'object') {
                return;
            }

            Object.entries(orderMap).forEach(([postId, orderValue]) => {
                const row = document.getElementById(`post-${postId}`);
                if (!row) {
                    return;
                }

                const valueNode = row.querySelector('.column-partner_custom_order .bos-partner-order-value');
                if (valueNode) {
                    valueNode.textContent = String(orderValue || '—');
                }
            });

            resortTableByOrder();
        }

        function resortTableByOrder() {
            const tableBody = document.getElementById('the-list');
            if (!tableBody) {
                return;
            }

            const rows = Array.from(tableBody.querySelectorAll('tr[id^="post-"]'));
            if (!rows.length) {
                return;
            }

            rows.sort((firstRow, secondRow) => {
                const firstValueNode = firstRow.querySelector('.column-partner_custom_order .bos-partner-order-value');
                const secondValueNode = secondRow.querySelector('.column-partner_custom_order .bos-partner-order-value');

                const firstValue = firstValueNode ? parseInt(firstValueNode.textContent, 10) : Number.MAX_SAFE_INTEGER;
                const secondValue = secondValueNode ? parseInt(secondValueNode.textContent, 10) : Number.MAX_SAFE_INTEGER;

                const firstOrder = Number.isFinite(firstValue) ? firstValue : Number.MAX_SAFE_INTEGER;
                const secondOrder = Number.isFinite(secondValue) ? secondValue : Number.MAX_SAFE_INTEGER;

                if (firstOrder !== secondOrder) {
                    return firstOrder - secondOrder;
                }

                const firstId = parseInt(firstRow.id.replace('post-', ''), 10);
                const secondId = parseInt(secondRow.id.replace('post-', ''), 10);
                return firstId - secondId;
            });

            rows.forEach((row, index) => {
                row.classList.remove('alternate');
                if (index % 2 === 1) {
                    row.classList.add('alternate');
                }
                tableBody.appendChild(row);
            });
        }

        async function movePartner(button) {
            const postId = button.getAttribute('data-post-id');
            const direction = button.getAttribute('data-direction');
            const nonce = button.getAttribute('data-nonce');
            if (!postId || !direction || !nonce) {
                return;
            }

            const requestId = ++latestMoveRequestId;

            const originalMap = getCurrentOrderMap();
            const optimisticMap = createOptimisticOrderMap(originalMap, postId, direction);
            applyOrderMap(optimisticMap);

            setButtonLoading(button, true);

            const abortController = new AbortController();
            const timeoutId = window.setTimeout(() => abortController.abort(), 3000);

            try {
                const params = new URLSearchParams();
                params.set('action', 'bos_partner_move');
                params.set('post_id', postId);
                params.set('direction', direction);
                params.set('_ajax_nonce', nonce);

                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    },
                    body: params.toString(),
                    credentials: 'same-origin',
                    signal: abortController.signal,
                });

                const responseText = await response.text();
                let data = null;
                try {
                    data = JSON.parse(responseText);
                } catch (parseError) {
                    throw new Error('Invalid server response while reordering partner.');
                }

                if (!response.ok || !data || !data.success) {
                    throw new Error((data && data.data && data.data.message) ? data.data.message : 'Failed to move partner.');
                }

                if (requestId === latestMoveRequestId) {
                    applyOrderMap(data.data.orders);
                }
            } catch (error) {
                console.error(error);
                if (requestId === latestMoveRequestId) {
                    applyOrderMap(originalMap);
                    const href = button.getAttribute('href');
                    if (href) {
                        window.location.href = href;
                        return;
                    }
                }
            } finally {
                window.clearTimeout(timeoutId);
                setButtonLoading(button, false);
            }
        }

        moveButtons.forEach((button) => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                movePartner(button);
            });
        });

        linebreakCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', async function() {
                const postId = checkbox.getAttribute('data-post-id');
                const nonce = checkbox.getAttribute('data-nonce');
                if (!postId || !nonce) {
                    return;
                }

                const previousChecked = !checkbox.checked;
                checkbox.disabled = true;

                const abortController = new AbortController();
                const timeoutId = window.setTimeout(() => abortController.abort(), 3000);

                try {
                    const params = new URLSearchParams();
                    params.set('action', 'bos_partner_toggle_linebreak');
                    params.set('post_id', postId);
                    params.set('_ajax_nonce', nonce);
                    params.set('partner_linebreak_before', checkbox.checked ? '1' : '0');

                    const response = await fetch(ajaxurl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        },
                        body: params.toString(),
                        credentials: 'same-origin',
                        signal: abortController.signal,
                    });

                    const responseText = await response.text();
                    let data = null;
                    try {
                        data = JSON.parse(responseText);
                    } catch (parseError) {
                        throw new Error('Invalid server response while updating line break.');
                    }

                    if (!response.ok || !data || !data.success) {
                        throw new Error((data && data.data && data.data.message) ? data.data.message : 'Failed to update line break.');
                    }
                } catch (error) {
                    console.error(error);
                    checkbox.checked = previousChecked;
                } finally {
                    window.clearTimeout(timeoutId);
                    checkbox.disabled = false;
                }
            });
        });
    })();
    </script>
    <?php
}
add_action('admin_footer-edit.php', 'bos_partner_admin_inline_scripts');
