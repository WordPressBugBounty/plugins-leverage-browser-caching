<?php
/**
 * Admin page class.
 *
 * Registers a top-level Dashboard menu page titled "LbCache" that shows
 * the current plugin status, a Pro feature list, and an Upgrade to Pro CTA.
 * The page (and menu item) is hidden when the Pro plugin is already active.
 *
 * @package Leverage Browser Caching
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lbrowserc_Admin_Page' ) ) {
	/**
	 * Registers and renders the LbCache admin page.
	 */
	class Lbrowserc_Admin_Page {

		/**
		 * Upgrade to Pro URL.
		 *
		 * @var string
		 */
		private $upgrade_url = 'https://lbcache.com';

		/**
		 * Absolute path to the .htaccess file.
		 *
		 * @var string
		 */
		private $htaccess_file;

		/**
		 * Unique marker used to detect whether the plugin block is in .htaccess.
		 *
		 * @var string
		 */
		private $unique_string = 'LBROWSERCSTART';

		/**
		 * Constructor — hooks into admin_menu and admin_enqueue_scripts.
		 */
		public function __construct() {
			$this->htaccess_file = wp_normalize_path( ABSPATH . '.htaccess' );
			add_action( 'admin_menu', array( $this, 'register_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		}

		/**
		 * Registers the top-level "LbCache" menu page.
		 * Hidden when the Pro plugin is already active.
		 */
		public function register_menu() {
			if ( defined( 'LBROWSERC_PRO_PATH' ) ) {
				return;
			}

			add_menu_page(
				__( 'LbCache', 'leverage-browser-caching' ),
				__( 'LbCache', 'leverage-browser-caching' ),
				'manage_options',
				'lbcache',
				array( $this, 'render_page' ),
				'dashicons-performance',
				80
			);
		}

		/**
		 * Enqueues inline styles only on the LbCache admin page.
		 *
		 * @param string $hook Current admin page hook suffix.
		 */
		public function enqueue_styles( $hook ) {
			if ( 'toplevel_page_lbcache' !== $hook ) {
				return;
			}

			$css = '
				/* ── LbCache Admin Page ───────────────────────────────── */
				#lbcache-wrap {
					max-width: 860px;
					margin: 30px 20px 0;
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
				}
				#lbcache-wrap h1.lbc-title {
					font-size: 26px;
					font-weight: 700;
					color: #1d2327;
					margin-bottom: 6px;
					display: flex;
					align-items: center;
					gap: 10px;
				}
				#lbcache-wrap h1.lbc-title .lbc-badge {
					font-size: 11px;
					font-weight: 600;
					background: #e7f5e7;
					color: #00a32a;
					padding: 2px 9px;
					border-radius: 20px;
					letter-spacing: .4px;
				}
				#lbcache-wrap .lbc-subtitle {
					color: #646970;
					font-size: 14px;
					margin-bottom: 28px;
				}

				/* Cards */
				.lbc-card {
					background: #fff;
					border: 1px solid #e2e4e7;
					border-radius: 10px;
					padding: 24px 28px;
					margin-bottom: 24px;
					box-shadow: 0 1px 4px rgba(0,0,0,.05);
				}
				.lbc-card h2 {
					font-size: 15px;
					font-weight: 600;
					color: #1d2327;
					margin: 0 0 18px;
					padding-bottom: 12px;
					border-bottom: 1px solid #f0f0f1;
				}

				/* Status grid */
				.lbc-status-grid {
					display: grid;
					grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
					gap: 14px;
				}
				.lbc-status-item {
					display: flex;
					align-items: flex-start;
					gap: 12px;
					background: #f9f9f9;
					border: 1px solid #ebebeb;
					border-radius: 8px;
					padding: 14px 16px;
				}
				.lbc-status-item .lbc-icon { font-size: 22px; line-height: 1; flex-shrink: 0; }
				.lbc-item-label  { font-size: 12px; color: #646970; margin-bottom: 3px; }
				.lbc-item-value  { font-size: 14px; font-weight: 600; color: #1d2327; }
				.lbc-ok   { color: #00a32a; }
				.lbc-warn { color: #d63638; }

				/* Pro features grid */
				.lbc-features-grid {
					display: grid;
					grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
					gap: 12px;
				}
				.lbc-feature-item {
					display: flex;
					align-items: center;
					gap: 10px;
					padding: 12px 16px;
					background: #f9f9f9;
					border: 1px solid #ebebeb;
					border-radius: 8px;
					font-size: 14px;
					color: #3c434a;
				}

				/* CTA banner */
				.lbc-cta {
					background: linear-gradient(135deg, #0e1629 0%, #1a2f5e 100%);
					border-radius: 12px;
					padding: 32px 36px;
					color: #fff;
					display: flex;
					align-items: center;
					justify-content: space-between;
					gap: 24px;
					flex-wrap: wrap;
					margin-bottom: 30px;
				}
				.lbc-cta h2 { font-size: 20px; font-weight: 700; margin: 0 0 8px; color: #fff; border: none; padding: 0; }
				.lbc-cta p  { margin: 0; font-size: 14px; color: rgba(255,255,255,.75); max-width: 500px; }
				.lbc-cta-btn {
					display: inline-block;
					background: #00a32a;
					color: #fff !important;
					font-size: 15px;
					font-weight: 700;
					padding: 13px 28px;
					border-radius: 8px;
					text-decoration: none !important;
					white-space: nowrap;
					flex-shrink: 0;
					transition: background .2s;
				}
				.lbc-cta-btn:hover { background: #008a22; }
			';

			wp_register_style( 'lbcache-admin', false );
			wp_enqueue_style( 'lbcache-admin' );
			wp_add_inline_style( 'lbcache-admin', $css );
		}

		/**
		 * Renders the LbCache admin page.
		 */
		public function render_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// ── Status checks ────────────────────────────────────────────
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			$htaccess_exists   = $wp_filesystem->exists( $this->htaccess_file );
			$htaccess_writable = $htaccess_exists && $wp_filesystem->is_writable( $this->htaccess_file );
			$caching_active    = false;

			if ( $htaccess_exists ) {
				$contents       = $wp_filesystem->get_contents( $this->htaccess_file );
				$caching_active = ( strpos( $contents, $this->unique_string ) !== false );
			}

			$server_software = isset( $_SERVER['SERVER_SOFTWARE'] )
				? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) )
				: __( 'Unknown', 'leverage-browser-caching' );
			$is_apache = ( stripos( $server_software, 'apache' ) !== false );

			// ── Pro feature list ─────────────────────────────────────────
			$pro_features = array(
				array( 'icon' => '⚡', 'label' => __( 'GZIP / Brotli Compression', 'leverage-browser-caching' ) ),
				array( 'icon' => '🎨', 'label' => __( 'CSS Minification', 'leverage-browser-caching' ) ),
				array( 'icon' => '📜', 'label' => __( 'JavaScript Minification', 'leverage-browser-caching' ) ),
				array( 'icon' => '🖼️', 'label' => __( 'Image Lazy Loading', 'leverage-browser-caching' ) ),
				array( 'icon' => '🗜️', 'label' => __( 'HTML Minification', 'leverage-browser-caching' ) ),
				array( 'icon' => '🔗', 'label' => __( 'DNS Prefetch & Preconnect', 'leverage-browser-caching' ) ),
				array( 'icon' => '📦', 'label' => __( 'Combine CSS & JS Files', 'leverage-browser-caching' ) ),
				array( 'icon' => '🚀', 'label' => __( 'Critical CSS Inlining', 'leverage-browser-caching' ) ),
				array( 'icon' => '🛡️', 'label' => __( 'Security Headers', 'leverage-browser-caching' ) ),
				array( 'icon' => '📊', 'label' => __( 'Performance Dashboard', 'leverage-browser-caching' ) ),
				array( 'icon' => '🔄', 'label' => __( 'One-click Cache Purge', 'leverage-browser-caching' ) ),
				array( 'icon' => '🎯', 'label' => __( 'Priority Support', 'leverage-browser-caching' ) ),
			);
			?>
			<div id="lbcache-wrap">

				<h1 class="lbc-title">
					<?php esc_html_e( 'LbCache', 'leverage-browser-caching' ); ?>
					<span class="lbc-badge"><?php esc_html_e( 'FREE', 'leverage-browser-caching' ); ?></span>
				</h1>
				<p class="lbc-subtitle"><?php esc_html_e( 'Leverage Browser Caching — speed up your WordPress site with zero configuration.', 'leverage-browser-caching' ); ?></p>

				<!-- ── Status ─────────────────────────────────────────── -->
				<div class="lbc-card">
					<h2><?php esc_html_e( '📋 Plugin Status', 'leverage-browser-caching' ); ?></h2>
					<div class="lbc-status-grid">

						<div class="lbc-status-item">
							<div class="lbc-icon"><?php echo $caching_active ? '✅' : '❌'; ?></div>
							<div>
								<div class="lbc-item-label"><?php esc_html_e( 'Browser Caching', 'leverage-browser-caching' ); ?></div>
								<div class="lbc-item-value <?php echo $caching_active ? 'lbc-ok' : 'lbc-warn'; ?>">
									<?php echo $caching_active ? esc_html__( 'Active', 'leverage-browser-caching' ) : esc_html__( 'Inactive', 'leverage-browser-caching' ); ?>
								</div>
							</div>
						</div>

						<div class="lbc-status-item">
							<div class="lbc-icon"><?php echo $htaccess_exists ? '✅' : '❌'; ?></div>
							<div>
								<div class="lbc-item-label"><?php esc_html_e( '.htaccess File', 'leverage-browser-caching' ); ?></div>
								<div class="lbc-item-value <?php echo $htaccess_exists ? 'lbc-ok' : 'lbc-warn'; ?>">
									<?php echo $htaccess_exists ? esc_html__( 'Found', 'leverage-browser-caching' ) : esc_html__( 'Not Found', 'leverage-browser-caching' ); ?>
								</div>
							</div>
						</div>

						<div class="lbc-status-item">
							<div class="lbc-icon"><?php echo $htaccess_writable ? '✅' : '❌'; ?></div>
							<div>
								<div class="lbc-item-label"><?php esc_html_e( '.htaccess Writable', 'leverage-browser-caching' ); ?></div>
								<div class="lbc-item-value <?php echo $htaccess_writable ? 'lbc-ok' : 'lbc-warn'; ?>">
									<?php echo $htaccess_writable ? esc_html__( 'Yes', 'leverage-browser-caching' ) : esc_html__( 'No', 'leverage-browser-caching' ); ?>
								</div>
							</div>
						</div>

						<div class="lbc-status-item">
							<div class="lbc-icon"><?php echo $is_apache ? '✅' : '⚠️'; ?></div>
							<div>
								<div class="lbc-item-label"><?php esc_html_e( 'Web Server', 'leverage-browser-caching' ); ?></div>
								<div class="lbc-item-value <?php echo $is_apache ? 'lbc-ok' : 'lbc-warn'; ?>">
									<?php echo esc_html( $server_software ); ?>
								</div>
							</div>
						</div>

						<div class="lbc-status-item">
							<div class="lbc-icon">🔌</div>
							<div>
								<div class="lbc-item-label"><?php esc_html_e( 'Plugin Version', 'leverage-browser-caching' ); ?></div>
								<div class="lbc-item-value">
									<?php
									$plugin_data = get_plugin_data( LBROWSERC_FILE );
									echo esc_html( isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '—' );
									?>
								</div>
							</div>
						</div>

						<div class="lbc-status-item">
							<div class="lbc-icon">🌐</div>
							<div>
								<div class="lbc-item-label"><?php esc_html_e( 'PHP Version', 'leverage-browser-caching' ); ?></div>
								<div class="lbc-item-value"><?php echo esc_html( PHP_VERSION ); ?></div>
							</div>
						</div>

					</div><!-- .lbc-status-grid -->
				</div><!-- .lbc-card -->

				<!-- ── Pro Features ───────────────────────────────────── -->
				<div class="lbc-card">
					<h2><?php esc_html_e( '🔒 Pro Features — Not Available in Free', 'leverage-browser-caching' ); ?></h2>
					<div class="lbc-features-grid">
						<?php foreach ( $pro_features as $feature ) : ?>
							<div class="lbc-feature-item">
								<span><?php echo esc_html( $feature['icon'] ); ?></span>
								<?php echo esc_html( $feature['label'] ); ?>
							</div>
						<?php endforeach; ?>
					</div>

                    <p style="text-align: center;font-size: 16px;padding-top: 14px;">... <?php esc_html_e( 'and much more', 'leverage-browser-caching' ); ?> ❤️ !</p>

				</div><!-- .lbc-card -->

				<!-- ── Upgrade CTA ────────────────────────────────────── -->
				<div class="lbc-cta">
					<div>
						<h2><?php esc_html_e( '⭐ Unlock the Full Power of Leverage Browser Caching PRO', 'leverage-browser-caching' ); ?></h2>
						<p><?php esc_html_e( 'Get GZIP compression, CSS/JS minification, lazy loading, HTML minification, and much more — all in one plugin. Boost your PageSpeed score to 100.', 'leverage-browser-caching' ); ?></p>
					</div>
					<a href="<?php echo esc_url( $this->upgrade_url ); ?>" target="_blank" rel="noopener noreferrer" class="lbc-cta-btn">
						<?php esc_html_e( '⭐ Upgrade to Pro', 'leverage-browser-caching' ); ?>
					</a>
				</div>

			</div><!-- #lbcache-wrap -->
			<?php
		}

	}
}
