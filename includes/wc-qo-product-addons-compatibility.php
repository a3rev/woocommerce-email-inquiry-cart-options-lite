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

// <-- START - Don't include price to addon name from cart & checkout pages
function wc_email_inquiry_custom_hidden_addon_price() {
	return '';
}
add_filter( 'woocommerce_get_item_data', function( $other_data, $cart_item ) {
	if ( class_exists( 'WC_Product_Addons' ) && ! empty( $cart_item['addons'] ) ) {
		$hide_price = \A3Rev\WCEmailInquiry\Functions::check_hide_price( $cart_item['product_id'] );

		if ( $hide_price ) {
			add_filter( 'wc_price', 'wc_email_inquiry_custom_hidden_addon_price', 101 );
		}
	}

	return $other_data;
}, 9, 2 );

add_filter( 'woocommerce_get_item_data', function( $other_data, $cart_item ) {
	if ( class_exists( 'WC_Product_Addons' ) && ! empty( $cart_item['addons'] ) ) {
		remove_filter( 'wc_price', 'wc_email_inquiry_custom_hidden_addon_price', 101 );
	}

	return $other_data;
}, 11, 2 );
// END -->

// <--- START - Hide the price is include to addon name from email content and order received info on My account page
function wc_email_inquiry_custom_addon_price( $price ) {
	if ( stristr( $price, '+' ) === false ) {
		$price = '+' . $price;
	}
	return $price;
}

add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values ) {
	if ( class_exists( 'WC_Product_Addons' ) && ! empty( $values['addons'] ) ) {
		add_filter( 'wc_price', 'wc_email_inquiry_custom_addon_price', 102 );
	}
}, 9, 3 );

add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values ) {
	if ( class_exists( 'WC_Product_Addons' ) && ! empty( $values['addons'] ) ) {
		remove_filter( 'wc_price', 'wc_email_inquiry_custom_addon_price', 102 );
	}
}, 11, 3 );

add_filter( 'woocommerce_order_item_display_meta_key', function( $display_key, $meta, $order_item ) {
	if ( class_exists( 'WC_Product_Addons' ) && ! is_admin() ) {
		if ( stristr( $display_key, ' (+' ) ) {
			$product = is_callable( array( $order_item, 'get_product' ) ) ? $order_item->get_product() : false;
			
			if ( $product && \A3Rev\WCEmailInquiry\Functions::check_hide_price( $product->get_id() ) ) {
				$display_key = preg_replace( "/\ \(\+[^)]+\)/", '', $display_key );
			}
		}
	}

	return $display_key;
}, 10, 3 );
// END -->
