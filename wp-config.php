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
define( 'DB_NAME', 'radheshyam' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'A.)M&`WwCsJY!/k9 {zwkRK&;9C,%h<H1o)?J}&Q](*GBJ{<Vu W*r0`UbZB/`G~' );
define( 'SECURE_AUTH_KEY',  'YQK>an1r{ECe4N~u;%%*z@x]5cV-? $P?vAz2z5}uhLD22GVHKp`xG@qedHcX-:P' );
define( 'LOGGED_IN_KEY',    'dUyt>U/;~z4Ua<)1Bi~KgNs!+eRTEnwZMPb9_&;f{}~<)mOZwENP;(y1R%K.wq=t' );
define( 'NONCE_KEY',        '3U/Z!v%Lk8d?EP}rB/50/jXHwZ2^ *bnQB&mok%bt<o>:|G3FMEbWyt~G!k),`hh' );
define( 'AUTH_SALT',        ':l%wlQormvg*/aJIC-A|r 5x?8ex7C6H&I Bbnn/rwco~QoBk zP,d|X#<u%GNF{' );
define( 'SECURE_AUTH_SALT', 'Ff/@Y4nDuV]MP+0E m@N>bnD+SF3FO?-bVm{F}6^#t6guf~c5!m4wq7_o|rnAUkU' );
define( 'LOGGED_IN_SALT',   '7HSOv)WayQN#59xy>bVJ(*&<`oH#sEq!9bu$8o$o8~[C$cAl6IS3iC?E@%rbkr/B' );
define( 'NONCE_SALT',       'V~*w&D0bD^G,,VHOxzzBu7&Ftrf,5s[Ta,[%mA/B,WJk0<nkhA`VCy $IT@pWutk' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
