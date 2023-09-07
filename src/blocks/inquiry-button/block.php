<?php
/**
 * Server-side rendering of the `core/post-title` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-title` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the filtered post title for the current post wrapped inside "h1" tags.
 */
function render_block_a3_wc_ei_button( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		$product_id = $block->context['postId'];
	} else {
		$product_id = \A3Rev\WCEmailInquiry\Functions::get_current_product_id( 0 );
	}

	if ( empty( $product_id ) ) return '';

	if ( ! \A3Rev\WCEmailInquiry\Functions::check_add_email_inquiry_button( $product_id ) ) return '';

	$container_classes = '';
	$container_classes .= isset( $attributes['width'] ) ? ' has-custom-width wp-block-button__width-' . $attributes['width'] : '';
	$container_classes .= isset( $attributes['textAlign'] ) ? ' has-text-align-' . $attributes['textAlign'] : '';
    
    $classes = 'wp-block-button__link wp-element-button %s';
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classes ) );

    $btText = ! empty( $attributes['content'] ) ? wp_kses_post( $attributes['content'] ) : __( 'Product Enquiry' );

    $buttonOutput = '<div class="wp-block-button '.$container_classes.'"><a %s '.$wrapper_attributes.' data-product_id="'.$product_id.'" form_action="hide">'.$btText.'%s</a></div>';

    $page_type = '';
    if ( is_product() ) {
    	$page_type = 'single';
    }

    $output = \A3Rev\WCEmailInquiry\Hook_Filter::add_email_inquiry_button( $product_id, $buttonOutput );

	return $output;
}

/**
 * Registers the `core/post-title` block on the server.
 */
function register_block_a3_wc_ei_button() {
	register_block_type(
		__DIR__ . '/block.json',
		array(
			'render_callback' => 'render_block_a3_wc_ei_button',
		)
	);
}
add_action( 'init', 'register_block_a3_wc_ei_button' );
