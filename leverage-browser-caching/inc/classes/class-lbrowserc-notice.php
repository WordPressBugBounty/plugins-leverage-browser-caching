<?php
/**
 * Donate admin notice class.
 *
 * Displays a dismissible admin notice encouraging users to donate.
 * The dismissed state is stored in the database and cleared on plugin
 * deactivation so the notice re-appears after the next activation.
 *
 * @package Leverage Browser Caching
 */

if ( ! class_exists( 'Lbrowserc_Notice' ) ) {
	/**
	 * Handles the dismissible donate admin notice.
	 */
	class Lbrowserc_Notice {

		/**
		 * Database option key used to track whether the notice was dismissed.
		 *
		 * @var string
		 */
		private $option_key = 'lbrowserc_notice_dismissed';

		/**
		 * AJAX action name for dismissing the notice.
		 *
		 * @var string
		 */
		private $ajax_action = 'lbrowserc_dismiss_notice';

		/**
		 * Nonce action name.
		 *
		 * @var string
		 */
		private $nonce_action = 'lbrowserc_dismiss_nonce';

		/**
		 * Donate URL.
		 *
		 * @var string
		 */
		private $donate_url = 'https://paypal.me/RinkuYadav';

		/**
		 * Constructor — registers admin notice and AJAX dismiss handler.
		 */
		public function __construct() {
			add_action( 'admin_notices', array( $this, 'show_notice' ) );
			add_action( 'wp_ajax_' . $this->ajax_action, array( $this, 'handle_dismiss' ) );
		}

		/**
		 * Renders the dismissible donate notice.
		 * Only shown to users who can manage options and have not dismissed it yet.
		 */
		public function show_notice() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( get_option( $this->option_key ) ) {
				return;
			}

			$nonce = wp_create_nonce( $this->nonce_action );
			?>
			<div class="notice notice-success is-dismissible" id="lbrowserc-donate-notice">
				<p>
					<strong><?php esc_html_e( '🚀 Leverage Browser Caching is working hard for your site!', 'lbrowserc' ); ?></strong>
					<br>
					<?php
					printf(
						/* translators: %s: donate link HTML */
						wp_kses(
							__( 'This plugin is completely <strong>free, ad-free, and open source</strong> — quietly improving your PageSpeed score and reducing server load every day. If it has saved you time or boosted your Google ranking, please consider a small donation to keep it maintained and updated. Even $1 makes a real difference. %s', 'lbrowserc' ),
							array(
								'strong' => array(),
								'a'      => array(
									'href'   => array(),
									'target' => array(),
									'rel'    => array(),
									'style'  => array(),
								),
							)
						),
						'<a href="' . esc_url( $this->donate_url ) . '" target="_blank" rel="noopener noreferrer" style="color:#e76500;font-weight:600;">&#9829; ' . esc_html__( 'Donate via PayPal — thank you!', 'lbrowserc' ) . '</a>'
					);
					?>
				</p>
			</div>
			<script>
			(function() {
				var notice = document.getElementById( 'lbrowserc-donate-notice' );
				if ( ! notice ) { return; }
				notice.addEventListener( 'click', function( e ) {
					if ( e.target.classList.contains( 'notice-dismiss' ) ) {
						var xhr = new XMLHttpRequest();
						xhr.open( 'POST', ajaxurl, true );
						xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
						xhr.send( 'action=<?php echo esc_js( $this->ajax_action ); ?>&nonce=<?php echo esc_js( $nonce ); ?>' );
					}
				} );
			}());
			</script>
			<?php
		}

		/**
		 * AJAX handler — saves the dismissed state to the database.
		 */
		public function handle_dismiss() {
			check_ajax_referer( $this->nonce_action, 'nonce' );

			if ( current_user_can( 'manage_options' ) ) {
				// Store with autoload disabled — only needed when admin pages load.
				update_option( $this->option_key, '1', false );
			}

			wp_die();
		}

		/**
		 * Clears the dismissed state so the notice re-appears after the next activation.
		 * Called via register_deactivation_hook().
		 */
		public function reset_notice() {
			delete_option( $this->option_key );
		}

	}
}
