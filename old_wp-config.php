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
define('DB_NAME', 'history');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'media123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '.rlXD!)U !|H:o=r7g4f>TBrMumdB8Ml#t=a9*Y&;9g!<*S7z&WoKwuXVoB D[F&');
define('SECURE_AUTH_KEY',  '+CfztbS!Er$h6`;`Troq;GH]6I#G2N67I*J>[Q=ZR-tc+6W5/UDv8 Kzhw{_W:Iq');
define('LOGGED_IN_KEY',    '_031A23_4)3i?ws=nCqivy|Z^)UOpVgOr,U1<2r](nF^XP00M:*+am_Cb{L0?*~D');
define('NONCE_KEY',        'L2-=Ji`l>.2BXSOU_QEh|Hxrl$b{N9Duz^pT.07okCx;aRS`AA(.O!His{Se6IIH');
define('AUTH_SALT',        'lCb5~WLR^2I0V^3,oY}POh2qS*U*pzhsJE.]wyc!)GSfY$o6yC_niQ*R~{)_9WOh');
define('SECURE_AUTH_SALT', ',i{9I8+}xId_?B{Z1~G>HP{6>N+<s(oV,4@umI16ls,_`Y5ykbM|<{%VQe%k!)S=');
define('LOGGED_IN_SALT',   'UUo``a*UH-nDTpELy#B52oQ3cpP)C:0>BUmyjF2HBZ1=$L{<8Y*kbTLqW5o!Y#8r');
define('NONCE_SALT',       'f~Sk9W*kk9^kT22r=~f/jfft^KidCIFC(Mnm<  #5`p$grV/njdUaU@%dmw5+%d{');

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
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
