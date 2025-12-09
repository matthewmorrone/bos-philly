<?php
error_reporting(0);
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ("wordpress/wp-config.php");

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", @$_SERVER["HTTP_USER_AGENT"]);
}

function containsAnySubstring($string, $substrings) {
    foreach ($substrings as $substring) {
        if (strpos($string, $substring) !== false) {
            return true;
        }
    }
    return false;
}

function randomId($length = 10) {
    $x = '0123456789';
    return substr(str_shuffle(str_repeat($x, ceil($length / strlen($x)))), 1, $length);
}

// Deterministic asset versioning based on last modified time to enable long-term caching
function asset_version($relativePath) {
    $fullPath = __DIR__ . '/' . ltrim($relativePath, '/');
    if (file_exists($fullPath)) {
        return filemtime($fullPath);
    }
    return '1';
}

function query() {
    $qs = [];
    $components = explode("/", $_SERVER['REQUEST_URI']);
    @[$root, $page, $name] = $components;
    $qs["page"] = sanitize_text_field($page);
    $qs["name"] = sanitize_text_field($name);
    return $qs;
}

// Include icon library and header
include 'templates/icons.php';
include 'templates/header.php';

// Handle different page types
if(query()["name"]) {
    echo '<section id="content">';
    
    switch(query()["page"]) {
        case "pages":
            include 'templates/page-template.php';
            break;
        case "events":
            include 'templates/event-template.php';
            break;
        case "blowouts":
            include 'templates/blowout-template.php';
            break;
        case "galleries":
            include 'templates/gallery-template.php';
            break;
        case "djs":
            include 'templates/dj-template.php';
            break;
    }
    
    echo '</section>';
} else {
    // Homepage sections
    include 'templates/home-splash.php';
    include 'templates/home-charity.php';
    include 'templates/home-pandering.php';
    include 'templates/home-events.php';
    include 'templates/home-galleries.php';
    include 'templates/home-djs.php';
    include 'templates/home-board.php';
}

// Always include partners and footer
include 'templates/home-partners.php';
include 'templates/footer.php';
include 'templates/scripts.php';
?>
