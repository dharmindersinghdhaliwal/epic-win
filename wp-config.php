<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'epic-win' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'XaGQ7pq&hO_n&A3$+mVKPiBXi{7A*xc0y2uY@$_f~D,5sZ`?/XP0J!o0Cl<xD0,e' );
define( 'SECURE_AUTH_KEY',  '^ys`>PyXS_rHUa?ruGv{9y*>=RSkY%$Y6UUlWtZ9iKs>+bA*FJ_Pj?22E[,Kb??)' );
define( 'LOGGED_IN_KEY',    'H[i*W[V$6fbX!Pb!Ub]U5`=e;>8j7)S)^0;75m9|0wd.}%;D*)tfKl~S{2uzv5D|' );
define( 'NONCE_KEY',        ';d_,vq-JthA2#9-gV7d?,!R};1<>CGTlxavVV3JDAax!ufMKBoZjW.3Rj~p@:[(S' );
define( 'AUTH_SALT',        '1XD}d^XYb=bpMj~xc@j a~2!~t!pb9gzG/-aUi/h%P,LV/(V,{ExiFaM7.5.m}zo' );
define( 'SECURE_AUTH_SALT', '~Tc_LhMyjafG/uyvKOcc-(Cc%0nNKqOWsmA.u)aAE(&J@Vg?3?*0s|es#ctLseuy' );
define( 'LOGGED_IN_SALT',   '?O^Q{z`3jj&xStLP!M-k$W-g/Ka3UL,g0e05Y}V]X&!Ee#,[rx_ljM/;pn= g=tf' );
define( 'NONCE_SALT',       'NJOAP:dA=t|Xi$t?>{tA*fe0&Ik`)n:2IwRONe,h D?0.Z@k5<f<z186S|N:]cV}' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'FS_METHOD', 'direct');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

@ini_set( 'upload_max_size' , '120M' );
@ini_set( 'post_max_size', '113M');
@ini_set( 'memory_limit', '115M' );