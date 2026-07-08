<?php
/**
 * Core class.
 *
 * @package 	Leverage Browser Caching
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lbrowserc_Core' ) ) {
	/**
	 * Core class of plugin.
	 */
	class Lbrowserc_Core {

		/**
		 * Absolute path to the .htaccess file.
		 *
		 * @var string
		 */
		private $htaccess_file;

		/**
		 * Unique marker string used to identify the plugin's block in .htaccess.
		 *
		 * @var string
		 */
		private $unique_string = 'LBROWSERCSTART';

		/**
		 * Constructor — sets up the htaccess path and registers admin notice hooks.
		 */
		public function __construct() {
			$this->htaccess_file = wp_normalize_path( ABSPATH . '.htaccess' );

			// Show admin notices when .htaccess cannot be found or accessed.
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		}

		/**
		 * Displays admin notices when .htaccess is missing or not accessible.
		 */
		public function admin_notices() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$server_software = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';
			$is_apache       = ( stripos( $server_software, 'apache' ) !== false );

			if ( ! $is_apache ) {
				return;
			}

			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			if ( ! $wp_filesystem->exists( $this->htaccess_file ) ) {
				$message  = '<div class="notice notice-error"><p>';
				$message .= __( 'Plugin Leverage Browser Caching: .htaccess file not found. This plugin works only for Apache server. If you are using Apache server, please create it.', 'leverage-browser-caching' );
				$message .= '</p></div>';
				echo wp_kses_post( $message );
			} elseif ( ! $wp_filesystem->is_readable( $this->htaccess_file ) || ! $wp_filesystem->is_writable( $this->htaccess_file ) ) {
				$message  = '<div class="notice notice-error"><p>';
				$message .= __( 'Plugin Leverage Browser Caching: .htaccess file is not readable or writable. Please change the file permissions.', 'leverage-browser-caching' );
				$message .= '</p></div>';
				echo wp_kses_post( $message );
			}
		}

		/**
		 * Adds browser caching rules to .htaccess on plugin activation.
		 * Called via register_activation_hook().
		 */
		public function add_code() {
			// Only allow users with sufficient capability to modify server files.
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			// Bail if .htaccess does not exist.
			if ( ! $wp_filesystem->exists( $this->htaccess_file ) ) {
				return;
			}

			// Bail if .htaccess is not readable or writable.
			if ( ! $wp_filesystem->is_readable( $this->htaccess_file ) || ! $wp_filesystem->is_writable( $this->htaccess_file ) ) {
				return;
			}

			$htaccess_cntn = $wp_filesystem->get_contents( $this->htaccess_file );

			// Do nothing if the plugin block is already present.
			if ( strpos( $htaccess_cntn, $this->unique_string ) !== false ) {
				return;
			}

			// Append the caching block and write back.
			$htaccess_cntn .= $this->code_to_add();
			$wp_filesystem->put_contents( $this->htaccess_file, $htaccess_cntn, FS_CHMOD_FILE );
		}

		/**
		 * Removes browser caching rules from .htaccess on plugin deactivation.
		 * Called via register_deactivation_hook().
		 */
		public function remove_code() {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			// Bail if .htaccess does not exist.
			if ( ! $wp_filesystem->exists( $this->htaccess_file ) ) {
				return;
			}

			// Bail if .htaccess is not readable or writable.
			if ( ! $wp_filesystem->is_readable( $this->htaccess_file ) || ! $wp_filesystem->is_writable( $this->htaccess_file ) ) {
				return;
			}

			$htaccess_cntn = $wp_filesystem->get_contents( $this->htaccess_file );

			// Do nothing if the plugin block is not present.
			if ( strpos( $htaccess_cntn, $this->unique_string ) === false ) {
				return;
			}

			// Remove the plugin's caching block.
			$pattern       = '/#\s?LBROWSERCSTART.*?LBROWSERCEND/s';
			$htaccess_cntn = preg_replace( $pattern, '', $htaccess_cntn );

			// Remove only the extra blank lines left by the removed block (max 2 → 1),
			// without touching intentional formatting elsewhere in the file.
			$htaccess_cntn = preg_replace( '/\n{3,}/', "\n\n", $htaccess_cntn );

			$wp_filesystem->put_contents( $this->htaccess_file, $htaccess_cntn, FS_CHMOD_FILE );
		}

		/**
		 * Builds and returns the browser caching directives to insert into .htaccess.
		 *
		 * Uses a local variable to avoid overwriting the stored .htaccess content.
		 *
		 * @return string
		 */
		private function code_to_add() {
			$code  = "\n";
			$code .= '# LBROWSERCSTART Browser Caching' . "\n";
			$code .= '<IfModule mod_expires.c>' . "\n";
			$code .= 'ExpiresActive On' . "\n";

			// Images.
			$code .= 'ExpiresByType image/gif "access 1 year"' . "\n";
			$code .= 'ExpiresByType image/jpeg "access 1 year"' . "\n";
			$code .= 'ExpiresByType image/png "access 1 year"' . "\n";
			$code .= 'ExpiresByType image/webp "access 1 year"' . "\n";
			$code .= 'ExpiresByType image/avif "access 1 year"' . "\n";
			$code .= 'ExpiresByType image/svg+xml "access 1 year"' . "\n";
			$code .= 'ExpiresByType image/x-icon "access 1 year"' . "\n";
			$code .= 'ExpiresByType image/vnd.microsoft.icon "access 1 year"' . "\n";

			// Web fonts.
			$code .= 'ExpiresByType font/woff "access 1 year"' . "\n";
			$code .= 'ExpiresByType font/woff2 "access 1 year"' . "\n";
			$code .= 'ExpiresByType font/ttf "access 1 year"' . "\n";
			$code .= 'ExpiresByType application/font-woff "access 1 year"' . "\n";
			$code .= 'ExpiresByType application/font-woff2 "access 1 year"' . "\n";

			// Stylesheets and scripts.
			$code .= 'ExpiresByType text/css "access 1 month"' . "\n";
			$code .= 'ExpiresByType text/javascript "access 1 month"' . "\n";
			$code .= 'ExpiresByType application/javascript "access 1 month"' . "\n";
			$code .= 'ExpiresByType application/x-javascript "access 1 month"' . "\n";

			// Documents.
			$code .= 'ExpiresByType text/html "access 1 month"' . "\n";
			$code .= 'ExpiresByType application/xhtml+xml "access 1 month"' . "\n";
			$code .= 'ExpiresByType application/pdf "access 1 month"' . "\n";

			// Data and other.
			$code .= 'ExpiresByType application/json "access 1 month"' . "\n";
			$code .= 'ExpiresByType application/x-shockwave-flash "access 1 month"' . "\n";

			$code .= 'ExpiresDefault "access 1 month"' . "\n";
			$code .= '</IfModule>' . "\n";
			$code .= '# END Caching LBROWSERCEND' . "\n";

			return $code;
		}

	}
}
