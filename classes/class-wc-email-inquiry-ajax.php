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

		if ( ! check_ajax_referer( 'wc-ei-default-form', 'security', false ) ) {
			wp_send_json( $json_var );
			die();
		}

		$product_id         = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$your_name          = isset( $_POST['your_name'] ) ? sanitize_text_field( wp_unslash( $_POST['your_name'] ) ) : '';
		$your_email         = isset( $_POST['your_email'] ) ? wp_unslash( wp_strip_all_tags( $_POST['your_email'] ) ) : '';
		$your_phone         = isset( $_POST['your_phone'] ) ? wp_unslash( wp_strip_all_tags( $_POST['your_phone'] ) ) : '';
		$your_message       = isset( $_POST['your_message'] ) ? wp_unslash( wp_strip_all_tags( $_POST['your_message'] ) ) : '';
		$send_copy_yourself = isset( $_POST['send_copy'] ) ? wp_unslash( wp_strip_all_tags( $_POST['send_copy'] ) ) : '';

		if ( ! is_email( $your_email ) ) {
			wp_send_json( $json_var );
			die();
		}

		$email_result = Functions::email_inquiry( $product_id, $your_name, $your_email, $your_phone, $your_message, $send_copy_yourself );

		if ( false !== $email_result ) {
			$json_var['status']  = 'success';
			$json_var['message'] = $email_result;
		}

		wp_send_json( $json_var );

		die();
	}
}
