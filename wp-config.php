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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'railway' );
// restsaboresdeliz

/** Database username */
define( 'DB_USER', 'root' );
// root

/** Database password */
define( 'DB_PASSWORD', '7DvRGuSzLyUGRlTviJrX' );
// vacio

/** Database hostname */
define( 'DB_HOST', 'containers-us-west-110.railway.app:6301' );
// localhost

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
define( 'AUTH_KEY',         'e,Oa-Jz1Yele8zqO3a<g7Do!Na0*,z)`.,6|5D/[7&;3H0}zK5i_+Bha15TgJoQZ' );
define( 'SECURE_AUTH_KEY',  '] E %j%2?>M5<iB[riR{~Bnctc~t;f0p 2JHR8e`8C j]0c{`jsXU~S>:~O>_:,B' );
define( 'LOGGED_IN_KEY',    'BiE!^e#~/9>-=BvGAaZ9M_UaX>LYmncsC>1]X8|sl)vR$ H;=0sSG+|p?yn !z9&' );
define( 'NONCE_KEY',        'g[~omwk((~2Z.:En^zNG_6.w0`DJSoqyrX4;v|PUneU[k5YK}?~%mCcP2/pDuWa5' );
define( 'AUTH_SALT',        'jWJkah0=>%s&OGF,*33f!zqRhwvgQ-ugqVw]-# 7M=(H!%]t%r:92k{y RW`-9^U' );
define( 'SECURE_AUTH_SALT', '6!4N`omZ6ROgfC|tW^o0xchK;d>=h.{@xRkS:<8NG;BE6&Q45Ef#|0OV`)*u`%>l' );
define( 'LOGGED_IN_SALT',   'uZ&#:=cW-Q#;$<G<4wNXUR3!g`;QbbIg2+OyK%rW}I6m3QWQx-rMZ$_5Blo&MZh%' );
define( 'NONCE_SALT',       'D$c(dba)~50]TB pp9@N|6l*R%I:W(B}M4Ijs{?LwG2UKKbYy3*k2`n+P i;>*`J' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
