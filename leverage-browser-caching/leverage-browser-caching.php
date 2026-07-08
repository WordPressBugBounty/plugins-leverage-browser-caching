<?php
/**
 * Plugin Name:	Leverage Browser Caching
 * Description:	Speed up WordPress with browser caching. Automatically adds expiry headers for images, CSS, JS & fonts via .htaccess Zero config (Apache only)
 * Version:		3.1
 * Author:		Rinku Yadav
 * Author URI:	https://lbcache.com
 * License:		GPLv2 or later
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: leverage-browser-caching
 *
 * @package     Leverage Browser Caching
 */

// Exit if directly accessed files.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Set path to constant.
if ( ! defined( 'LBROWSERC_PATH' ) ) {
	define( 'LBROWSERC_PATH', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
}

// Set url to constant.
if ( ! defined( 'LBROWSERC_URL' ) ) {
	define( 'LBROWSERC_URL', plugin_dir_url( __FILE__ ) );
}

// Set __FILE__ to constant.
if ( ! defined( 'LBROWSERC_FILE' ) ) {
	define( 'LBROWSERC_FILE', __FILE__ );
}

// Set plugin base.
if ( ! defined( 'LBROWSERC_BASE_FILE' ) ) {
	define( 'LBROWSERC_BASE_FILE', plugin_basename( __FILE__ ) );
}

// Load core class.
require_once LBROWSERC_PATH . 'inc/classes/class-lbrowserc-core.php';

$lbrowserc = new Lbrowserc_Core();

// Write caching rules to .htaccess on activation; remove them on deactivation.
register_activation_hook( LBROWSERC_FILE, array( $lbrowserc, 'add_code' ) );
register_deactivation_hook( LBROWSERC_FILE, array( $lbrowserc, 'remove_code' ) );

// Load dismissible notice class globally so its deactivation hook can fire properly.
require_once LBROWSERC_PATH . 'inc/classes/class-lbrowserc-notice.php';
$lbrowserc_notice = new Lbrowserc_Notice();

// Clear dismissed state on deactivation so notice reappears after re-activation.
register_deactivation_hook( LBROWSERC_FILE, array( $lbrowserc_notice, 'reset_notice' ) );

// Load admin-only classes: plugin action links, and admin page.
if ( is_admin() ) {
	require_once LBROWSERC_PATH . 'inc/classes/class-lbrowserc-links.php';
	new Lbrowserc_Links();

	require_once LBROWSERC_PATH . 'inc/classes/class-lbrowserc-admin-page.php';
	new Lbrowserc_Admin_Page();
}


