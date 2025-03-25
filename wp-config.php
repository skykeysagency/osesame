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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u570625223_mug2B' );

/** Database username */
define( 'DB_USER', 'u570625223_2Kp8k' );

/** Database password */
define( 'DB_PASSWORD', '38Sncx9qZQ' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '5tGh?ZBQUH,*0zt6(Y$rh%:VrZs.z;|VKM&RLMpSvIBFqa_H5{>HGX6B}xm#[ik,' );
define( 'SECURE_AUTH_KEY',   '430Sys6p/Oc1h}(^*yy@<W<[i;=ryN~,6L-SGe,KxIbljd8`+j`7kCAa2%4wh_.f' );
define( 'LOGGED_IN_KEY',     '=NOfS,v;OA!%gRtj^tkWun5Tocu II{dV3`%jj#<SDY#PuToRM!3~+NfT<h+Ujf5' );
define( 'NONCE_KEY',         'HHrLBTPe=_w0}9~hslJ[r7ibmW+E]k==kb06G8SV*;.nfxbmSqutk!y<2/Rf^~h ' );
define( 'AUTH_SALT',         '^xb]L3A.lDI$2v9y^y=3|YfoUVQG?F`T.iD_}S~CAc=k=_t)7ssf(80=bA/Fhija' );
define( 'SECURE_AUTH_SALT',  'W>B+W9:I<JckF)*J?sRF{)k^MKy3vfkD)(]=AC^IDX$x0Zi>]5$ ;?V3J5>yDo3K' );
define( 'LOGGED_IN_SALT',    'ZME.fhH= F#U4B j&j+9!s{sSj;-sC&BH2Kd[.+Ztw.`(}mILyn>SA+ZSe /;4dp' );
define( 'NONCE_SALT',        '$wiOrP~C{l+xo}8sN2i5oztBB2c;5-9i$ljp:6.BX%^QdE2)?biKeB^&w)It~t9!' );
define( 'WP_CACHE_KEY_SALT', 'ly>k~;F_Aob&:|EyvS(Q=/i(N}~]`6$36? R0`L@{[itwvUJs].*i=8VApMAP{n[' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '38eccf9f6f7109f7aac6b5634dac477b' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
