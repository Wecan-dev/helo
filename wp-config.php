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
define( 'DB_NAME', 'db_helo' );
define( 'FS_METHOD', 'direct' );

/** MySQL database username */
define( 'DB_USER', 'adminwecan' );

/** MySQL database password */
define( 'DB_PASSWORD', '_*8gTYWqM9FHU' );

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
define( 'AUTH_KEY',         ')TBnte*~$HmR1H&).<[S[0>ba1HKKwAWd8Trmaa)6X#~!Vp$;<1G9v(*{8|-us;%' );
define( 'SECURE_AUTH_KEY',  '`<eE} ZdCM#hKvJ:=*-T;G)ni/L5qS5Q@X)ODWsv8 Oz?6e+dB>{9$~XlnbMeNkY' );
define( 'LOGGED_IN_KEY',    '%zUWVBeM%tekqLsOaiXYo };V{m{lbiG[ld2p1+cNX&_t(o!@W~|t4 9e&f^n%or' );
define( 'NONCE_KEY',        'M~ p Tu0i1^:^[m_Whr@4pusB^vS8vyxm-oWPa=7BpC:#L0{i7o5{pNMj@5sP,Bg' );
define( 'AUTH_SALT',        'c]%U.f1Sul,!Ae>pq4V.u-f2?8nQA%^A>MBo)5&FP-8|EiJLqfV)NT!2q/EaF:IQ' );
define( 'SECURE_AUTH_SALT', '2ZcGqNrYHx:L0Z MT#E<1&2X8a/141%T Wx}v`lS`<~8AE/WO@t,=5w.W/:-!0/M' );
define( 'LOGGED_IN_SALT',   '>y~{9ksQ(lWJ!ai-{nFtQWmo`c3$-[$|MV9rLqrIR#.zk*}!xq4/kK2}*W>Q6yn/' );
define( 'NONCE_SALT',       '2 (S{OvfdK^1+>>vy2O@7G0dO1W<p!uB@o5wx{oc*usCw51K^MI$A7SHGNk~JR~*' );

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
