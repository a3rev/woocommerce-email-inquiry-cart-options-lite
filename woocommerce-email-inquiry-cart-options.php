<?php
/*
Plugin Name: Email Inquiry & Cart Options for WooCommerce
Description: Transform your entire WooCommerce products catalog or any individual product into an online brochure with Product Email Inquiry button and pop-up email form. Add product email inquiry functionality to any product either with WooCommerce functionality or hide that functionality and the page becomes a brochure.
Version: 3.4.1
Requires at least: 6.0
Tested up to: 6.6
Author: a3rev Software
Author URI: https://a3rev.com/
Text Domain: woocommerce-email-inquiry-cart-options
Domain Path: /languages
WC requires at least: 6.0.0
WC tested up to: 9.0
License: This software is under commercial license and copyright to A3 Revolution Software Development team

	WooCommerce Email Inquiry & Cart Options. Plugin for the WooCommerce shopping Cart.
	Copyright© 2011 A3 Revolution Software Development team

	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define('WC_EMAIL_INQUIRY_FILE_PATH', dirname(__FILE__));
define('WC_EMAIL_INQUIRY_DIR_NAME', basename(WC_EMAIL_INQUIRY_FILE_PATH));
define('WC_EMAIL_INQUIRY_FOLDER', dirname(plugin_basename(__FILE__)));
define('WC_EMAIL_INQUIRY_URL', untrailingslashit(plugins_url('/', __FILE__)));
define('WC_EMAIL_INQUIRY_DIR', WP_PLUGIN_DIR . '/' . WC_EMAIL_INQUIRY_FOLDER);
define('WC_EMAIL_INQUIRY_NAME', plugin_basename(__FILE__));
define('WC_EMAIL_INQUIRY_TEMPLATE_PATH', WC_EMAIL_INQUIRY_FILE_PATH . '/templates');
define('WC_EMAIL_INQUIRY_IMAGES_URL', WC_EMAIL_INQUIRY_URL . '/assets/images');
define('WC_EMAIL_INQUIRY_JS_URL', WC_EMAIL_INQUIRY_URL . '/assets/js');
define('WC_EMAIL_INQUIRY_CSS_URL', WC_EMAIL_INQUIRY_URL . '/assets/css');

if (!defined("WC_EMAIL_ULTIMATE_URI")) define("WC_EMAIL_ULTIMATE_URI", "https://a3rev.com/shop/woocommerce-email-inquiry-ultimate/");

define( 'WC_EMAIL_INQUIRY_KEY', 'wc_email_inquiry' );
define( 'WC_EMAIL_INQUIRY_PREFIX', 'wc_ei_' );
define( 'WC_EMAIL_INQUIRY_VERSION',  '3.4.1' );
define( 'WC_EMAIL_INQUIRY_G_FONTS', true );

// declare compatibility with new HPOS of WooCommerce
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

use \A3Rev\WCEmailInquiry\FrameWork;

if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	require __DIR__ . '/vendor/autoload.php';

	global $wc_ei_ajax;
	$wc_ei_ajax = new \A3Rev\WCEmailInquiry\Ajax();

	/**
	 * Plugin Framework init
	 */
	$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_interface'] = new FrameWork\Admin_Interface();

	global $wc_ei_settings_page;
	$wc_ei_settings_page = new FrameWork\Pages\EI_Settings();

	$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init'] = new FrameWork\Admin_Init();

	$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'less'] = new FrameWork\Less_Sass();

	global $wp_version;
	if ( version_compare( $wp_version, '5.5', '>=' ) ) {
		// Gutenberg Blocks init
		global $wc_ei_blocks;
		$wc_ei_blocks = new \A3Rev\WCEmailInquiry\Blocks();

		require 'src/blocks/inquiry-button/block.php';
	}

} else {
	return;
}

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/woocommerce-email-inquiry-cart-options/woocommerce-email-inquiry-cart-options-LOCALE.mo
 * 		- WP_LANG_DIR/plugins/woocommerce-email-inquiry-cart-options-LOCALE.mo
 * 	 	- /wp-content/plugins/woocommerce-email-inquiry-cart-options/languages/woocommerce-email-inquiry-cart-options-LOCALE.mo (which if not found falls back to)
 */
function wc_email_inquiry_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-email-inquiry-cart-options' );

	load_textdomain( 'woocommerce-email-inquiry-cart-options', WP_LANG_DIR . '/woocommerce-email-inquiry-cart-options/woocommerce-email-inquiry-cart-options-' . $locale . '.mo' );
	load_plugin_textdomain( 'woocommerce-email-inquiry-cart-options', false, WC_EMAIL_INQUIRY_FOLDER . '/languages/' );
}

include ('includes/wc-email-inquiry-template-functions.php');

include( 'includes/wc-qo-product-addons-compatibility.php' );

include ('admin/wc-email-inquiry-init.php');

/**
 * Call when the plugin is activated
 */
register_activation_hook(__FILE__, 'wc_email_inquiry_install');
