<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

$projectRootIndex = dirname(__DIR__) . '/index.php';
$projectRootTemplates = dirname(__DIR__) . '/templates/header.php';

if (file_exists($projectRootIndex) && file_exists($projectRootTemplates)) {
	require $projectRootIndex;
	exit;
}

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
