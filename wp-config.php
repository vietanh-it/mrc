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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'a_mrc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ME6Y|R lH$Zf=h]a[c,^)_h_D&7Mna!PiLx?^-1i:%%Mgq-N&8V3v/cry8O/zEpr');
define('SECURE_AUTH_KEY',  '!Q}OnRYuvMaut9)!RQcR;X_fz+)l:J?(c9L]fII!]Eg^;5?JsJ}v0V83yk7y;Z|h');
define('LOGGED_IN_KEY',    'x`EaTNpF>`g6B<vIOz?h#d<YFTfjAAJ^{,TID^g=mHUsUUtiOHBL;^{JM+=-`4 z');
define('NONCE_KEY',        'mi,:<<}x<w4}Q+dhg )o(>DP4sup5(ISkO-?zPvhHun }lI=UOGZkmSu`nnrxf^k');
define('AUTH_SALT',        'L16`,&gtX$UBo.#$hp27I9/FzaI[0k3,Z|Um Q{[_)rtQ6cUL<%ryniy!!1+cOy?');
define('SECURE_AUTH_SALT', '}Sz|9@T V,I6y&~}9w-An7zlacnBZGkUv25X3-G>6=TG>zb?z.(o6fi8)7B1K@(3');
define('LOGGED_IN_SALT',   'w`$>c65r(=R$&OwB._>X8t{s*e`Y6J2Yo$+]kgHJ~2(B1*ZOM<9}q+Q]<d9Nrj{Y');
define('NONCE_SALT',       'F_BGAlvv%N8%3}or_K]am-DV%Ekf|[DWL&[k>b=V[IX3B<^Y,7;$.X|PnW8[)#)v');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mrc_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
