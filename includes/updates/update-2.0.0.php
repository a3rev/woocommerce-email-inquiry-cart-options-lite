<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->set_default_settings();

// Build sass
$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'less']->plugin_build_sass();