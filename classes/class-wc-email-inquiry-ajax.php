<?php

namespace A3Rev\WCEmailInquiry;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax
{

	public function __construct() {
		$this->add_ajax_events();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public function add_ajax_events() {
		$ajax_events = array(
			'submit_form'     => true,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_wc_email_inquiry_' . $ajax_event, array( $this, str_replace( '-', '_', $ajax_event ) . '_ajax' ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_wc_email_inquiry_' . $ajax_event, array( $this, str_replace( '-', '_', $ajax_event ) . '_ajax' ) );
			}
		}
	}

	public function submit_form_ajax() {

		$json_var = array(
			'status'  => 'error',
			'message' => __( "Sorry, this product don't enable email inquiry.", 'woocommerce-email-inquiry-cart-options' ),
		);

		$product_id         = absint( $_POST['product_id'] );
		$your_name          = wp_unslash( wp_strip_all_tags( $_POST['your_name'] ) );
		$your_email         = wp_unslash( wp_strip_all_tags( $_POST['your_email'] ) );
		$your_phone         = wp_unslash( wp_strip_all_tags( $_POST['your_phone'] ) );
		$your_message       = wp_unslash( wp_strip_all_tags( $_POST['your_message'] ) );
		$send_copy_yourself = wp_unslash( wp_strip_all_tags( $_POST['send_copy'] ) );
		
		$email_result = Functions::email_inquiry( $product_id, $your_name, $your_email, $your_phone, $your_message, $send_copy_yourself );

		if ( false !== $email_result ) {
			$json_var['status']  = 'success';
			$json_var['message'] = $email_result;
		}

		wp_send_json( $json_var );

		die();
	}
}
