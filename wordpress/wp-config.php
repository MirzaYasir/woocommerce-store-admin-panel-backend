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
define( 'DB_NAME', 'thailand' );

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
define( 'AUTH_KEY',         'YEL,s1[n>X1]nu;zY-Q6UJct;H=ANx7t78IX,C3ZJT[y3RTM0zE[zn6^9V>@{_nN' );
define( 'SECURE_AUTH_KEY',  '6S<U]@*^B5&>&%pg0`>RZL%Epq7Oc,ax`GnN(Q2^;k>rd|<6O`I#wu!9-,zi7~0?' );
define( 'LOGGED_IN_KEY',    'Mh12I%oh1ow8k-;2y9PB[KZ#Avk@y9y&Z1q3;OO}`Wo(?)cC]IWfsO0;PQ{j`vnG' );
define( 'NONCE_KEY',        'MBUEiy*p562H_g}y>4lMfC+8dyM1NBPB$CQn/^KwB_$$F)<iWjp^K=PoX~<k$#iG' );
define( 'AUTH_SALT',        ']wX_h_E-i_DM?*3BwA&B|1V~4;:pg3KfPtX(((u>0rBF#)#]:hZQM7>B:m(D=~Rw' );
define( 'SECURE_AUTH_SALT', 'r~_YoG}g7jS|T=L=-7eW#3[VCtxVfzqO.b|(`a]2M(`148YE|xYa9_h>[QE/GI0b' );
define( 'LOGGED_IN_SALT',   '!i>[m<K>mJimxl~[>etZ1axvP?xd^Wz-!%EcyB..?S6RizBmz`7xxe) Jh2C-S9?' );
define( 'NONCE_SALT',       'U-)1NOAVCam+ Bx6pQI4{Qf*Z(x),TWDr_txV`sc*?$,0!twpI+0,HL x.1QvUMT' );

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
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
define('FS_METHOD', 'direct');
define('FS_CHMOD_DIR', 0755);
define('FS_CHMOD_FILE', 0644);


/* Add any custom values between this line and the "stop editing" line. */

define('WP_MEMORY_LIMIT', '256M');


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
