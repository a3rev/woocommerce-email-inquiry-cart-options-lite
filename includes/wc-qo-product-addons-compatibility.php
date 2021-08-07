<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * WC Product Addons compatibility
 * Plugin URI https://woocommerce.com/products/product-add-ons/
 */

// Remove total container of this addon
add_action( 'woocommerce_product_addons_start', function( $post_id ) {

	$hide_price = \A3Rev\WCEmailInquiry\Functions::check_hide_price( $post_id );

	if ( $hide_price ) {
		remove_all_actions( 'woocommerce_product_addons_end', 10 );
	}
} );

// Hide Price of addon
add_filter( 'woocommerce_product_addons_option_price', function( $price_for_display, $option ) {
	global $product;

	$hide_price = \A3Rev\WCEmailInquiry\Functions::check_hide_price( $product->get_id() );

	if ( $hide_price ) {
		return '';
	}

	return $price_for_display;

}, 1001, 2 );

// Hide price of addon from cart & checkout page
add_action( 'woocommerce_before_cart', function() {
	$hide_price = \A3Rev\WCEmailInquiry\Functions::check_hide_price(0);

	if ( $hide_price ) {
		echo '<style>.woocommerce-Price-amount{ display:none; }</style>';
	}
} );

add_action( 'woocommerce_before_checkout_form', function() {
	$hide_price = \A3Rev\WCEmailInquiry\Functions::check_hide_price(0);

	if ( $hide_price ) {
		echo '<style>.woocommerce-Price-amount{ display:none; }</style>';
	}
} );
