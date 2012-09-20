<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', getenv('SHARK_DB_NAME'));

/** MySQL database username */
define('DB_USER', getenv('SHARK_DB_USER'));

/** MySQL database password */
define('DB_PASSWORD', getenv('SHARK_DB_PASS'));

/** MySQL hostname */
define('DB_HOST', getenv('SHARK_DB_HOST'));

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         ',kNcV09!R&h]eKpix|=8%A*YjYjQXS2j?QC:qfLPr|!*){-K5Xv<k[,M@4cs,QWd');
define('SECURE_AUTH_KEY',  ' -n?e+>GBm[~EP_wpPPwm0nMhwpC^)7gc|hc.mC)iH9xx~/LC4Upc@w~IF{E(F7Q');
define('LOGGED_IN_KEY',    'tw,+eEL><@T8-i8[R.kc<iu]-DC:B|G/k_v5@I.kM3*Eef;g4Mh;A%hQrnLtJWNy');
define('NONCE_KEY',        'nV/Nwo#u6RZtCdch}j{)j;V:bQUmt]7dm.NjH|)aT`zSpk-O&C]Ya%ANo-8N}`vh');
define('AUTH_SALT',        '}3+){@o0YG~chqQwcL 8|Z|OP[e>2v@/++%]Yo^[6z}fk*mD1tv?V1}oH!^BCQX{');
define('SECURE_AUTH_SALT', 'V5|#^V(O=DQTv7 a6(L)9)w*HSrTx]YD-i4J1r.@TE5]b~m,w|.KPI`}kj(kzD^#');
define('LOGGED_IN_SALT',   'L+.uyH+5.BpY%L-@gD&<1S0YhtD(rB$?A>5{9QJHd~A0hXd;nBDh]9-s]kr8Bf6&');
define('NONCE_SALT',       '-0|qx%4c$3j~SHC6YrbV+:Z]lsm5.-vo6X)Yod-KM{d[J,k4a~nJGBzSB eLJs|F');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'es_ES');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/**
 * Site URL.
 */
define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/blog');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
