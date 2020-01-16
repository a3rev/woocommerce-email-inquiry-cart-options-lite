<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global ${WC_EMAIL_INQUIRY_PREFIX.'admin_init'};
${WC_EMAIL_INQUIRY_PREFIX.'admin_init'}->set_default_settings();

// Build sass
global ${WC_EMAIL_INQUIRY_PREFIX.'less'};
${WC_EMAIL_INQUIRY_PREFIX.'less'}->plugin_build_sass();