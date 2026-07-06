<?php
/**
 * Plugin action links class.
 *
 * @package Leverage Browser Caching
 */

if ( ! class_exists( 'Lbrowserc_Links' ) ) {
	/**
	 * Adds custom links to the plugin's row on the Plugins list table.
	 */
	class Lbrowserc_Links {

		/**
		 * Upgrade to Pro URL.
		 *
		 * @var string
		 */
		private $upgrade_url = 'https://lbcache.com';

		/**
		 * Constructor — hooks into the plugin action links filter.
		 */
		public function __construct() {
			add_filter(
				'plugin_action_links_' . LBROWSERC_BASE_FILE,
				array( $this, 'add_action_links' )
			);
		}

		/**
		 * Appends a Settings and Upgrade to Pro link to the plugin's action links.
		 * Both links are hidden when the Pro plugin is already active.
		 *
		 * @param array $links Existing action links.
		 * @return array Modified action links.
		 */
		public function add_action_links( $links ) {
			// Hide all custom links if the Pro plugin is already active.
			if ( defined( 'LBROWSERC_PRO_PATH' ) ) {
				return $links;
			}

			$settings_link = sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'admin.php?page=lbcache' ) ),
				esc_html__( 'Settings', 'lbrowserc' )
			);

			$upgrade_link = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer" style="color:#00a32a;font-weight:600;">&#11088; %s</a>',
				esc_url( $this->upgrade_url ),
				esc_html__( 'Upgrade to Pro', 'lbrowserc' )
			);

			// Prepend Settings so it appears before the default Deactivate link.
			array_unshift( $links, $settings_link );
			$links[] = $upgrade_link;

			return $links;
		}

	}
}
