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
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tourbay' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'qwer1234!@#$' );

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
define( 'AUTH_KEY',         'BKjgng3t)9Re<d,gTSStDnN#^}1kD%[`.q;wpH&R=XIf>MH4y$+a08TT[cIq<~3!' );
define( 'SECURE_AUTH_KEY',  '0}?]@=,b3*r,5Re|EzX*=~V33+ceT%8gwL.ilLJou=7H=y~$-xU^pa[v.Uk?G[}X' );
define( 'LOGGED_IN_KEY',    'k_nNyh>s/$W$.LD`LWpxysXC^m 0Z^`!E/j|NKiScpi{P%h%Jwn(6|sZ1qe.{pC#' );
define( 'NONCE_KEY',        '*$5xODwjdw2maccUr4i(sY.dA~[^00V[g*~Q_L/pLjq=t!(r[ xsG,oE w5 nV{f' );
define( 'AUTH_SALT',        ']hN-i!saJ`]r4;]e)1F]D%HA0B7Bg}rYdP%uBOG$rh7_Xno,9UVG3!8Y9FpgmN:R' );
define( 'SECURE_AUTH_SALT', '2>{>,7Gxg`JNGq`p.]DwTWJ}Fd}*Y]n>krybm6[1TspMZG3!2fke0cLHSji}#wm=' );
define( 'LOGGED_IN_SALT',   'cDD|Y$WLFYH~6uXFdOHsIG&/J7{LEwj7NYN))_=PbFAaF1)VcQyU?VqY]z5n#{Pp' );
define( 'NONCE_SALT',       'GYdR$}(X`+}>1&V1~;,Q15BOf,fwR*4]vlo4bWXD$@I}8$_D,x_^4?`xS$920+~q' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
