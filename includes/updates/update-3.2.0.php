<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$wc_email_inquiry_global_settings = get_option( 'wc_email_inquiry_global_settings', array('inquiry_single_only' => 'no') );
$inquiry_single_only = $wc_email_inquiry_global_settings['inquiry_single_only'];
if ( 'no' !== $inquiry_single_only ) {
	$wc_email_inquiry_global_settings['inquiry_card_type'] = 'shortcode';
}

update_option( 'wc_email_inquiry_global_settings', $wc_email_inquiry_global_settings );