<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bos' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'mysql' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'b{]o-)Vg+L,4Ge9}iW2DA@t/pJM*<~RjTyrmkH4%h|nDympjy*.m>{cN>L:^tT0[' );
define( 'SECURE_AUTH_KEY',  'j_(o46DE.5!chPwy~m>r/;?a~,s#TCo g&$Nd,[PpyVcW_LL| 7Cym>Q~]~-I*K(' );
define( 'LOGGED_IN_KEY',    'ZZ>qsL?zwp0TTteBY_v0?k-b&Ho)Ei9%B=/G&%KJn7#=?D>9}5!M<!pA4V21q?SH' );
define( 'NONCE_KEY',        'gbYm=!0I:cQH;|w7/9GKI`Nv?$hWanpK%Bwjmy__Pes:KOD>/WqP8};.<:x>7S8)' );
define( 'AUTH_SALT',        'WzKyZ<;`S,4D2wM>5)6!v<-hn-TC#!C}pyPF1Pk{PD:*Y5Y3g&AD,=c6]=I}f=pv' );
define( 'SECURE_AUTH_SALT', 'h]^C6v)Ir6=(:B7KPJK0kIu}hbED@PXrWCAu{.h+i4MGts!BT2|L3g.AyTMqA)8g' );
define( 'LOGGED_IN_SALT',   'pC;Aql7F}i#|LAmL?V])u+FR%?!8lg:z-8 ?@$+8a*%V6sMp dv#vh4/B/]E,.~X' );
define( 'NONCE_SALT',       'my h]SD_!2PkU2}Scrd(,kP}Tg<WkxdMf O1XhC)wElDr(x,v+QOB99gPc:^<klZ' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */

define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false ); // Set to true to display errors directly in the browser

/* Add any custom values between this line and the "stop editing" line. */

define( 'FS_METHOD', 'direct' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
