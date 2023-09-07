<?php

use A3Rev\WCEmailInquiry;

function wc_email_inquiry_install(){
	update_option('a3rev_wc_email_inquiry_version', WC_EMAIL_INQUIRY_VERSION );
	update_option('a3rev_wc_email_inquiry_ultimate_version', '1.2.6');

	delete_metadata( 'user', 0, $GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->plugin_name . '-' . 'plugin_framework_global_box' . '-' . 'opened', '', true );

	update_option('a3rev_wc_email_inquiry_just_installed', true);
}

function wc_email_inquiry_init() {
	if ( get_option('a3rev_wc_email_inquiry_just_installed') ) {
		delete_option('a3rev_wc_email_inquiry_just_installed');

		// Set Settings Default from Admin Init
		$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->set_default_settings();

		// Build sass
		$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'less']->plugin_build_sass();
	}

	wc_email_inquiry_plugin_textdomain();
}

// Add language
add_action('init', 'wc_email_inquiry_init');

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'a3_wp_admin' ) );

// Add admin sidebar menu css
add_action( 'admin_enqueue_scripts', array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'admin_sidebar_menu_css' ) );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'plugin_extra_links'), 10, 2 );


// Need to call Admin Init to show Admin UI
$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->init();

$woocommerce_db_version = get_option( 'woocommerce_db_version', null );

// Add upgrade notice to Dashboard pages
add_filter( $GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->plugin_name . '_plugin_extension_boxes', array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'plugin_extension_box' ) );

// Add extra link on left of Deactivate link on Plugin manager page
add_action( 'plugin_action_links_' . WC_EMAIL_INQUIRY_NAME, array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'settings_plugin_links' ) );

// Include style into header
add_action('wp_enqueue_scripts', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'add_style_header') );

// Include google fonts into header
add_action( 'wp_enqueue_scripts', array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'add_google_fonts'), 9 );


// Change item meta value as long url to short url
add_filter('woocommerce_order_item_display_meta_value', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'change_order_item_display_meta_value' ) );

// AJAX hide yellow message dontshow
add_action('wp_ajax_wc_ei_yellow_message_dontshow', array('\A3Rev\WCEmailInquiry\Functions', 'wc_ei_yellow_message_dontshow') );
add_action('wp_ajax_nopriv_wc_ei_yellow_message_dontshow', array('\A3Rev\WCEmailInquiry\Functions', 'wc_ei_yellow_message_dontshow') );

// AJAX hide yellow message dismiss
add_action('wp_ajax_wc_ei_yellow_message_dismiss', array('\A3Rev\WCEmailInquiry\Functions', 'wc_ei_yellow_message_dismiss') );
add_action('wp_ajax_nopriv_wc_ei_yellow_message_dismiss', array('\A3Rev\WCEmailInquiry\Functions', 'wc_ei_yellow_message_dismiss') );

// Check for hide add to cart on Product Grid Block
add_filter( 'woocommerce_blocks_product_grid_item_html', array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'product_grid_block' ), 10, 3 );

// Hide Add to Cart button on Shop page
add_action('woocommerce_before_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'shop_before_hide_add_to_cart_button'), 100, 4 );
add_action('woocommerce_after_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'shop_after_hide_add_to_cart_button'), 1, 4 );

// Hide Add to Cart button on Details page
add_action('woocommerce_before_add_to_cart_button', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'details_before_hide_add_to_cart_button'), 100 );
add_action('woocommerce_after_add_to_cart_button', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'details_after_hide_add_to_cart_button'), 1 );

// Hide Quantity Control and Add to Cart button for Child Product of Grouped Product Type in Details Page
add_action('woocommerce_before_add_to_cart_form', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'grouped_product_hide_add_to_cart_style'), 100 );
add_filter('single_add_to_cart_text', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'grouped_product_hide_add_to_cart'), 100, 2 );
add_filter('woocommerce_product_single_add_to_cart_text', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'grouped_product_hide_add_to_cart'), 100, 2 ); // for Woo 2.1
add_action('woocommerce_before_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'before_grouped_product_hide_quatity_control'), 100, 4 );
add_action('woocommerce_after_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'after_grouped_product_hide_quatity_control'), 1, 4 );


// Hide Price on Shop page and Details page
add_action('woocommerce_before_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'shop_before_hide_price'), 100, 4 );
add_action('woocommerce_after_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'shop_after_hide_price'), 1, 4 );

// Hide Price
add_filter('woocommerce_get_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 100 );
add_filter('woocommerce_variation_sale_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 100 );
add_filter('woocommerce_variation_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 100 );
add_filter('woocommerce_variation_free_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 100 );
add_filter('woocommerce_variation_empty_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 100 );

add_filter('woocommerce_cart_item_price', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'hide_price_from_mini_cart'), 100 );

add_filter( 'woocommerce_cart_item_subtotal', array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'hide_price_from_mini_cart' ), 100 );
add_filter('woocommerce_widget_cart_item_quantity', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'remove_x_character_mini_cart'), 100 );
add_filter('woocommerce_cart_product_subtotal', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'hide_cart_product_subtotal'), 100 );

// Hide Price from On Page Checkout plugin
add_filter( 'wc_product_options_discount_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'hide_price_from_mini_cart'), 101 );
add_filter( 'wc_product_options_discount_variation_onsale_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 101 );
add_filter( 'wc_product_options_discount_variable_onsale_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 101 );
add_filter( 'wc_product_options_discount_onsale_price_html', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'global_hide_price'), 101);

add_filter('woocommerce_cart_subtotal', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'hide_mini_cart_subtotal'), 101 );
add_filter('woocommerce_cart_total', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'hide_mini_cart_contents_total'), 101 );
add_filter('woocommerce_cart_contents_total', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'hide_mini_cart_contents_total'), 101 );

// Include Modal container to footer
add_action( 'wp_footer', array( '\A3Rev\WCEmailInquiry\Hook_Filter', 'wc_email_inquiry_modal_popup' ) );

$wc_email_inquiry_customize_email_button_settings = get_option( 'wc_email_inquiry_customize_email_button', array( 'inquiry_button_position' => 'below' ) );
$wc_email_inquiry_button_position = $wc_email_inquiry_customize_email_button_settings['inquiry_button_position'];

$wc_email_inquiry_global_settings = get_option( 'wc_email_inquiry_global_settings', array( 'inquiry_single_type' => 'auto', 'inquiry_card_type' => 'shortcode' ) );
// Add Email Inquiry Button on Shop page
$inquiry_card_type = $wc_email_inquiry_global_settings['inquiry_card_type'] ?? 'shortcode';
if ( 'auto' == $inquiry_card_type ) {
	if ($wc_email_inquiry_button_position == 'above' )
		add_action('woocommerce_before_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'shop_add_email_inquiry_button_above'), 9, 4);
	else
		add_action('woocommerce_after_shop_loop_item', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'shop_add_email_inquiry_button_below'), 12);
}

// Add Email Inquiry Button on Product Details page
$inquiry_single_type = $wc_email_inquiry_global_settings['inquiry_single_type'] ?? 'auto';
if ( 'auto' == $inquiry_single_type ) {
	if ($wc_email_inquiry_button_position == 'above' )
		add_action('woocommerce_before_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'details_add_email_inquiry_button_above'), 9, 4 );
	else
		add_action('woocommerce_after_template_part', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'details_add_email_inquiry_button_below'), 2, 4);
}

// Force do_shortcode for the content from the shortcode & paragraph blocks
add_filter( 'render_block_core/shortcode', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'render_block_core_shortcode'), 10, 3);
add_filter( 'render_block_core/paragraph', array('\A3Rev\WCEmailInquiry\Hook_Filter', 'render_block_core_shortcode'), 10, 3);

// Add shortcode [wc_email_inquiry_bt] show email inquiry button for one product
add_shortcode('wc_email_inquiry_bt', function( $attributes ) {
	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) return;

	if ( ! is_array( $attributes ) ) {
		$attributes = array();
	}

	extract( array_merge( array(
		'product_id'   => 0,
		'button_class' => '',
    ), $attributes ) );

    $product_id = WCEmailInquiry\Functions::get_current_product_id( $product_id );

	if ( empty( $product_id ) ) return '';

	if ( ! WCEmailInquiry\Functions::check_add_email_inquiry_button( $product_id ) ) return '';

    $output = WCEmailInquiry\Hook_Filter::add_email_inquiry_button( $product_id );

    return $output;

} );


// Check upgrade functions
add_action('init', 'wc_ei_upgrade_plugin');
function wc_ei_upgrade_plugin () {

	// Upgrade to 1.0.3
	if ( version_compare( get_option( 'a3rev_wc_email_inquiry_version' ), '1.0.3' ) === -1 ) {
		WCEmailInquiry\Functions::reset_products_to_global_settings();
		update_option('a3rev_wc_email_inquiry_version', '1.0.3');
	}

	// Upgrade Ultimate to 1.0.8
	if ( version_compare( get_option( 'a3rev_wc_email_inquiry_version' ), '1.0.8' ) === -1 ) {
		include( WC_EMAIL_INQUIRY_DIR. '/includes/updates/update-1.0.8.php' );
		update_option('a3rev_wc_email_inquiry_version', '1.0.8');
	}

	if ( version_compare( get_option( 'a3rev_wc_email_inquiry_version' ), '1.0.9.2' ) === -1 ) {
		include( WC_EMAIL_INQUIRY_DIR. '/includes/updates/update-1.0.9.2.php' );
		update_option('a3rev_wc_email_inquiry_version', '1.0.9.2');
	}

	if ( version_compare( get_option( 'a3rev_wc_email_inquiry_version' ), '1.2.0' ) === -1 ) {
		// Build sass
		$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'less']->plugin_build_sass();

		update_option('a3rev_wc_email_inquiry_version', '1.2.0');
	}

	if ( version_compare( get_option( 'a3rev_wc_email_inquiry_version' ), '1.2.3' ) === -1 ) {
		$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->set_default_settings();
	}

	// Upgrade to 1.3.3
	if ( version_compare(get_option('a3rev_wc_email_inquiry_version'), '1.3.3') === -1 ) {
		update_option('a3rev_wc_email_inquiry_version', '1.3.3');
		update_option('wc_email_inquiry_cart_options_style_version', time() );
	}

	// Upgrade to 1.4.0
	if(version_compare(get_option('a3rev_wc_email_inquiry_version'), '1.4.0') === -1){
		update_option('a3rev_wc_email_inquiry_version', '1.4.0');

		include( WC_EMAIL_INQUIRY_DIR. '/includes/updates/update-1.4.0.php' );
	}

	if(version_compare(get_option('a3rev_wc_email_inquiry_version'), '2.0.0') === -1){
		update_option('a3rev_wc_email_inquiry_version', '2.0.0');

		include( WC_EMAIL_INQUIRY_DIR. '/includes/updates/update-2.0.0.php' );
	}

	if(version_compare(get_option('a3rev_wc_email_inquiry_version'), '3.2.0') === -1){
		update_option('a3rev_wc_email_inquiry_version', '3.2.0');

		include( WC_EMAIL_INQUIRY_DIR. '/includes/updates/update-3.2.0.php' );
	}

	update_option('a3rev_wc_email_inquiry_version', WC_EMAIL_INQUIRY_VERSION );
	update_option('a3rev_wc_email_inquiry_ultimate_version', '1.2.6');

}

?>