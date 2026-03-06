<?php
/**
 * Plugin Name: BOS Media URL Normalizer
 * Description: Rewrites local attachment/media URLs to production host for frontend output.
 */

if (!defined('ABSPATH')) {
    exit;
}

function bos_media_prod_base_url() {
    if (defined('BOS_PROD_MEDIA_BASE_URL') && BOS_PROD_MEDIA_BASE_URL) {
        return rtrim(BOS_PROD_MEDIA_BASE_URL, '/');
    }

    $envValue = getenv('BOS_PROD_MEDIA_BASE_URL');
    if ($envValue) {
        return rtrim($envValue, '/');
    }

    return 'https://www.bosphilly.org';
}

function bos_is_media_path($path) {
    if (!is_string($path) || $path === '') {
        return false;
    }

    return strpos($path, '/wp-content/uploads/') !== false
        || strpos($path, '/wordpress/wp-content/uploads/') !== false;
}

function bos_local_media_hosts() {
    $hosts = [];

    $siteHost = parse_url(site_url(), PHP_URL_HOST);
    $homeHost = parse_url(home_url(), PHP_URL_HOST);
    $prodHost = parse_url(bos_media_prod_base_url(), PHP_URL_HOST);

    foreach ([$siteHost, $homeHost, 'localhost', '127.0.0.1', 'bos.ddev.site'] as $host) {
        if ($host && $host !== $prodHost) {
            $hosts[] = strtolower($host);
        }
    }

    return array_values(array_unique($hosts));
}

function bos_normalize_media_url($url) {
    if (!is_string($url) || $url === '') {
        return $url;
    }

    $prodBase = bos_media_prod_base_url();
    $prodHost = parse_url($prodBase, PHP_URL_HOST);
    $parts = wp_parse_url($url);

    if (!$parts) {
        return $url;
    }

    $path = isset($parts['path']) ? $parts['path'] : '';
    if (!bos_is_media_path($path)) {
        return $url;
    }

    if (isset($parts['host'])) {
        $host = strtolower($parts['host']);
        if ($host === strtolower((string) $prodHost)) {
            return $url;
        }

        if (!in_array($host, bos_local_media_hosts(), true)) {
            return $url;
        }

        $normalized = $prodBase . $path;
        if (isset($parts['query'])) {
            $normalized .= '?' . $parts['query'];
        }
        if (isset($parts['fragment'])) {
            $normalized .= '#' . $parts['fragment'];
        }

        return $normalized;
    }

    if (strpos($url, '//') === 0) {
        return $url;
    }

    if (strpos($url, '/') === 0 && bos_is_media_path($url)) {
        return $prodBase . $url;
    }

    return $url;
}

add_filter('wp_get_attachment_url', 'bos_normalize_media_url', 99);

add_filter('wp_get_attachment_image_src', function ($image) {
    if (is_array($image) && isset($image[0])) {
        $image[0] = bos_normalize_media_url($image[0]);
    }

    return $image;
}, 99);

add_filter('wp_calculate_image_srcset', function ($sources) {
    if (!is_array($sources)) {
        return $sources;
    }

    foreach ($sources as &$source) {
        if (is_array($source) && isset($source['url'])) {
            $source['url'] = bos_normalize_media_url($source['url']);
        }
    }

    return $sources;
}, 99);
