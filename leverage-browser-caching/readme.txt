=== Leverage Browser Caching ===
Contributors: rinkuyadav999
Donate link: https://lbcache.com
Tags: browser caching, leverage browser caching, page speed, htaccess, site speed
Requires at least: 4.0
Tested up to: 7.0
Requires PHP: 5.6
Stable tag: 3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Speed up WordPress with browser caching. Automatically adds expiry headers for images, CSS, JS & fonts via .htaccess Zero config (Apache only)

== Description ==

**Leverage Browser Caching** is a lightweight, zero-configuration WordPress plugin that dramatically improves your website's loading speed by instructing visitors' browsers to cache static files locally.

With a single activation, the plugin writes optimized browser caching rules to your `.htaccess` file. When you deactivate the plugin, those rules are cleanly removed — leaving your `.htaccess` exactly as it was.

= Why Browser Caching Matters =

Every time a visitor loads a page, their browser downloads CSS, JavaScript, images, and fonts. Without caching, those same files are re-downloaded on every single visit — wasting bandwidth and slowing down your site.

Browser caching tells the visitor's browser to store those static files locally for a set period. On their next visit, the browser serves them from its local cache instead of fetching them from the server again. The result: **faster page loads, lower server load, and a better user experience**.

= What This Plugin Does =

* Adds browser caching expiry headers to your `.htaccess` file on plugin **activation**.
* Removes them cleanly on plugin **deactivation** — no leftover code.
* Requires **zero configuration** — install, activate, and it works.
* Covers all major static asset types:
  * **Images** — JPEG, PNG, GIF, WebP, AVIF, SVG, ICO
  * **Web Fonts** — WOFF, WOFF2, TTF
  * **Stylesheets** — CSS
  * **Scripts** — JavaScript
  * **Documents** — HTML, XHTML, PDF
  * **Data** — JSON
  * **Other** — SWF

= Improve Your PageSpeed Score =

This plugin directly fixes the "Leverage Browser Caching" or "Serve static assets with an efficient cache policy" warning shown by:

* **Google PageSpeed Insights**
* **GTmetrix**
* **Pingdom Website Speed Test**
* **WebPageTest**
* **YSlow**

= Cache Expiry Periods =

| Asset Type | Cache Duration |
|---|---|
| Images (JPEG, PNG, GIF, WebP, AVIF, SVG, ICO) | 1 Year |
| Web Fonts (WOFF, WOFF2, TTF) | 1 Year |
| CSS, JavaScript | 1 Month |
| HTML, PDF, JSON | 1 Month |
| Everything else | 1 Month |

= Requirements =

* **Apache web server** with `mod_expires` module enabled.
* A writable `.htaccess` file in your WordPress root directory.
* This plugin does **not** work on Nginx or IIS servers.

= Upgrade to Pro =

Get even faster load times and more advanced features with **Leverage Browser Caching Pro**! 

* **Advanced Caching & Compression**: Customizable Cache Durations, GZIP Compression, Cache-Control Headers, Keep-Alive, and ETag Removal.
* **Code Minification**: Minify CSS, JS, and HTML, plus Delay JavaScript for a massive PageSpeed boost.
* **Media & Font Optimization**: Lazy Load Images & iFrames, and Google Fonts Optimization.
* **Database & Site Health**: Database Cleanup, DNS Prefetch, Preload Links, Heartbeat Control, and XML-RPC Disable.
* **WooCommerce Ready**: Built-in WooCommerce Optimization.
* **Settings Admin Page**: Easy-to-use interface to manage all features.
* **Premium Support & Updates**: Get fast, dedicated assistance and regular new features.

[Upgrade to Leverage Browser Caching Pro today!](https://lbcache.com)

== Installation ==

= Option 1: Install via WordPress Dashboard (Recommended) =

1. Go to **Dashboard → Plugins → Add New**.
2. Search for **Leverage Browser Caching**.
3. Click **Install Now**, then click **Activate**.
4. That's it — the plugin is working immediately.

= Option 2: Upload via WordPress Dashboard =

1. Download the plugin ZIP from: https://wordpress.org/plugins/leverage-browser-caching/
2. Go to **Dashboard → Plugins → Add New → Upload Plugin**.
3. Select the downloaded ZIP file and click **Install Now**.
4. Click **Activate Plugin**.

= Option 3: Upload via FTP =

1. Download the plugin ZIP from: https://wordpress.org/plugins/leverage-browser-caching/
2. Extract the ZIP to find the `leverage-browser-caching` folder.
3. Upload the folder to `/wp-content/plugins/` on your server.
4. Go to **Dashboard → Plugins**, find the plugin, and click **Activate**.

== Frequently Asked Questions ==

= Do I need to configure anything after activating? =

No. The plugin is completely **zero-configuration**. Simply activate it and it starts working immediately.

= What happens when I deactivate the plugin? =

The plugin **cleanly removes** all caching rules it added to `.htaccess`. Your file will be restored to its previous state with no leftover code.

= Some third-party JavaScript files still show as uncached — why? =

Browsers can only cache files served from your own domain. Files loaded from third-party domains (e.g., Google Analytics, Facebook Pixel, or ad networks) are outside your control and cannot be cached by your server's `.htaccess`. This is a browser security restriction. [Learn more](https://stackoverflow.com/questions/9580307/set-cache-control-to-external-resources/9580334#9580334).

= My PageSpeed score improved but still shows a warning — what should I do? =

Remaining warnings are almost always caused by third-party scripts (see above). For files on your own domain, ensure the plugin is activated and that your `.htaccess` is writable. You can verify by checking your `.htaccess` file for the `# LBROWSERCSTART Browser Caching` block.

= Where can I find the options page? =

There is no options page — the plugin works automatically with no settings to configure.

= Does this plugin work on Nginx or Windows IIS servers? =

No. This plugin works exclusively on **Apache web servers** by writing directives to `.htaccess`. It has no effect on Nginx or IIS. For Nginx, caching headers must be configured at the server level.

= I need support =

Please open a support topic on the [WordPress.org support forum](https://wordpress.org/support/plugin/leverage-browser-caching/).

== Screenshots ==

1. Browser caching rules added to .htaccess after plugin activation.

== Changelog ==

= 3.1 =
* Code improvements
* Plugin check issues fixed

= 3.0 =
* Code improvements
* Pro info and link added

= 2.7 =
* Code improvements: browser caching rules now added only on plugin activation and removed only on deactivation.
* Added modern MIME types: WebP, AVIF, SVG, WOFF, WOFF2, TTF, JSON.
* Removed invalid `image/jpg` MIME type (duplicate of `image/jpeg`).
* Security: added `LOCK_EX` flag to all file write operations to prevent race conditions.
* Security: added `current_user_can()` capability check before modifying `.htaccess`.
* Code quality: all class properties changed from `public` to `private`.
* Fixed typo: "Apace" corrected to "Apache" in admin notice.
* Fixed overly aggressive newline normalisation in `.htaccess` cleanup.
* Improved admin notices to use modern WordPress `notice notice-error` CSS classes.

= 2.6 =
* Removed additional class.

= 2.5 =
* Tested up to WordPress 6.6.
* Added additional class.

= 2.4 =
* Tested up to latest WordPress.

= 2.3 =
* Tested up to WordPress 6.3.

= 2.2 =
* Tested up to WordPress 6.0.

= 2.1 =
* 14 December, 2020
* Tested up to WordPress 5.6.

= 2.0 =
* 24 July, 2020
* Tested up to WordPress 5.4.2.
* Removed action link.
* Improved info messages.

= 1.9 =
* 26 November, 2019
* Tested up to WordPress 5.3.

= 1.8 =
* 23 February, 2018
* Removed rate-this-plugin code.
* Added donate link.

= 1.7 =
* 07 August, 2018
* Description updates.
* Rating link.

= 1.6 =
* 07 February, 2018
* Fix: Fatal Error.

= 1.5 =
* 15 November, 2017
* Added capability check.

= 1.4 =
* 1 November, 2017
* Small bug fix.
* Refactored credits.

= 1.3 =
* 1 November, 2017
* Credit link changes.

= 1.2 =
* 26 October, 2017
* Credit links updated.

= 1.1 =
* 01 August, 2017
* Fix: ExpiresByType entries.

= 1.0 =
* 30 June, 2017
* First release.

== Upgrade Notice ==

= 3.1 =
Important update: browser caching rules are now only written to .htaccess on plugin activation and removed on deactivation. Modern MIME types added. Upgrade recommended for all users. Simply deactivate and then activate the plugin for Modern MIME types. 

