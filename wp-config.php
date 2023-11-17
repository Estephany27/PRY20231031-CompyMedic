<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
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
define( 'DB_NAME', 'BD_CompyMedic' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '!{TsG.Y#n[tL`THaZh8L&_#hjs;(|OIJ%uVXIqW($vA>NZXtgn1S[JO&&!rOF_Co' );
define( 'SECURE_AUTH_KEY',  ';P&<]JHRxWLq1QtB!w-)}|M9kT5#TE3]7#5~P|k9aDN=uwz*BeWi*t4QhYk8kk_3' );
define( 'LOGGED_IN_KEY',    'P}0>bRB@rrw{+rZn/QbRc96Cx{X8=>,IB=aK?lIMGdOQhIAi47/LYfg#ZR*YIzL ' );
define( 'NONCE_KEY',        '|OWmwk1M3CO&*pX)&l1UMD/-y`y~)NMM ^J=BRPz8>*6<+u-^l8Oge:bKvpE(.md' );
define( 'AUTH_SALT',        'b+3 M78nZO2:Z7w5]K;B?nk^bm.xp53#R+2e[4v2r.xO_Sg8}(]z=yr;N<ken|2k' );
define( 'SECURE_AUTH_SALT', 'ch~X9>bLY>1YeIbQJ-d?Gt]},iSR,ibzh_/ ;bR#6ZLFtL3&7sHLn9)r?</mCzom' );
define( 'LOGGED_IN_SALT',   'qhR:n@&f3?i5pI?oW0i}&~0Wpb^WGjT`W<nT5XRxBY#yZP[a4k(FjB`0u_;%g}*e' );
define( 'NONCE_SALT',       'N|]Nu@(:V1Fu[ 7qoVHp?U8TV(8a+WDG^(L,2O->|N78RQGWk+[0?)N6xVM];Kx(' );

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
