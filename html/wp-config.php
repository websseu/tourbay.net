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
define( 'AUTH_KEY',         'PI|x~**7KGL!VHfZP=1ltUYxbp<]9<OJ;ukzU+6,L5UfW6>?$OAwNbzT=v.:XTr!' );
define( 'SECURE_AUTH_KEY',  '$=vz[5o$VqFB*+OH/bcI?G-GFLHFFmKbgITWR%8vp-i4.sgaEv-rNvYqu#IFp7iO' );
define( 'LOGGED_IN_KEY',    'U7f>%xUY>[m-(nOi|LxXS`l!zxi&pn9{G+nl-az]wLnlr&wZ]-)l%>G8=[c^8|j`' );
define( 'NONCE_KEY',        'I-|HF}=Ny$jJ]>LnfLQ=<#K~o=4!,Pvd7bp>}PX,uB]]HZIY_D9,]*t*zxEaAH2%' );
define( 'AUTH_SALT',        '+}1!sbzXCJb pJG.x}C^N;fxLq/mb.&#nV$(N-7xI6Mw`VOJ/y]J]SUR#b0O^Yva' );
define( 'SECURE_AUTH_SALT', '*73h_.$}+ZFq?4U6-E+S0}eVQKM,v%kaCI-c/j:/dK5.p%2* 7rZ+c`OihnERqK/' );
define( 'LOGGED_IN_SALT',   'JV +3S_9a;j(q^fW_t`h?Ht${M[+gLW)/};O 6%4TzPeKL/^c3X.0#jSrX[Soy*(' );
define( 'NONCE_SALT',       '3WoMNxLCN{`SB5JhfWqzKrx~-m:6xk|gS=dcJK7e(DpbLh6T2{ti5wd0R|~fg}-N' );

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
