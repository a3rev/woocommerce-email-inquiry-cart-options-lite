<?php
/**
 * WC Email Inquiry Hook Filter
 *
 * Table Of Contents
 *
 * shop_before_hide_add_to_cart_button()
 * shop_after_hide_add_to_cart_button()
 * details_before_hide_add_to_cart_button()
 * details_after_hide_add_to_cart_button()
 * global_hide_price()
 * hide_price_from_mini_cart()
 * remove_x_character_mini_cart()
 * shop_before_hide_price()
 * shop_after_hide_price()
 * add_email_inquiry_button()
 * shop_add_email_inquiry_button_above()
 * shop_add_email_inquiry_button_below()
 * details_add_email_inquiry_button_above()
 * details_add_email_inquiry_button_below()
 * wc_email_inquiry_popup()
 * wc_email_inquiry_action()
 * add_style_header()
 * footer_print_scripts()
 * script_contact_popup()
 * a3_wp_admin()
 * admin_sidebar_menu_css()
 * plugin_extra_links()
 */

namespace A3Rev\WCEmailInquiry;

class Hook_Filter
{
		
	public static function shop_before_hide_add_to_cart_button($template_name, $template_path, $located, $args ) {
		global $post;
		global $product;
		if ($template_name == 'loop/add-to-cart.php') {
			$product_id = $product->get_id();
			
			if (Functions::check_hide_add_cart_button($product_id))
				ob_start();
		}
	}

	public static function product_grid_block( $content, $data, $product ) {

		$product_id = $product->get_id();

		$have_hide_add_cart = false;

		if ( Functions::check_hide_add_cart_button( $product_id ) ) {
			$have_hide_add_cart = true;
		}

		if ( ! $have_hide_add_cart ) {
			return $content;
		}

		$custom_button = $data->button;
		if ( $have_hide_add_cart ) {
			$custom_button = '';
		}

		$content = "<li class=\"wc-block-grid__product\">
				<a href=\"{$data->permalink}\" class=\"wc-block-grid__product-link\">
					{$data->image}
					{$data->title}
				</a>
				{$data->badge}
				{$data->price}
				{$data->rating}
				{$custom_button}
			</li>";

		return $content;
	}
	
	public static function shop_after_hide_add_to_cart_button($template_name, $template_path, $located, $args ) {
		global $post;
		global $product;
		if ($template_name == 'loop/add-to-cart.php') {
			$product_id = $product->get_id();
			
			if (Functions::check_hide_add_cart_button($product_id))
				ob_end_clean();
		}
	}
	
	public static function details_before_hide_add_to_cart_button() {
		global $post, $product;
		$product_id = $product->get_id();
		
		if (Functions::check_hide_add_cart_button($product_id) ) {
			ob_start();
		}
	}
	
	public static function details_after_hide_add_to_cart_button() {
		global $post, $product;
		$product_id = $product->get_id();
		
		if (Functions::check_hide_add_cart_button($product_id)){
			ob_end_clean();
		}
	}
	
	public static function grouped_product_hide_add_to_cart_style() {
		global $product;
		$product_id = $product->get_id();
		
		if ( $product->is_type('grouped') && Functions::check_hide_add_cart_button( $product_id ) ){
			echo '<style>body table.group_table a.button, body table.group_table a.single_add_to_cart_button, body table.group_table .quantity, table.group_table a.button, table.group_table a.single_add_to_cart_button, table.group_table .quantity { display:none !important; } </style>';
		}
	}
	
	public static function grouped_product_hide_add_to_cart( $add_to_cart='', $product_type='' ) {
		global $product;
		$product_id = $product->get_id();
		
		if ( Functions::check_hide_add_cart_button( $product_id ) ){
			$add_to_cart = '';
		}
		
		return $add_to_cart;
	}
	
	public static function before_grouped_product_hide_quatity_control( $template_name, $template_path, $located, $args ) {
		global $product;
		if ( $template_name == 'single-product/add-to-cart/quantity.php' ) {
			$product_id = $product->get_id();
			
			if ( Functions::check_hide_add_cart_button( $product_id ) ) {
				ob_start();
			}
		}
	}
	
	public static function after_grouped_product_hide_quatity_control( $template_name, $template_path, $located, $args ) {
		global $product;
		if ( $template_name == 'single-product/add-to-cart/quantity.php' ) {
			$product_id = $product->get_id();
			
			if ( Functions::check_hide_add_cart_button( $product_id ) ) {
				ob_end_clean();
			}
		}
	}
	
	public static function global_hide_price( $price ) {
		$product_id = 0;

		if ( ( in_array( basename ($_SERVER['PHP_SELF']), array('admin-ajax.php') ) || !is_admin() ) && Functions::check_hide_price($product_id)) return '';
		
		return $price;
	}
	
	public static function hide_price_from_mini_cart($price) {
		$product_id = 0;
		if (Functions::check_hide_price($product_id)) return '';
		
		return $price;
	}
	
	public static function remove_x_character_mini_cart($text_quantity='') {
		$product_id = 0;
		if (Functions::check_hide_price($product_id)) $text_quantity = str_replace('&times;', '', $text_quantity);
		
		return $text_quantity;
	}
	
	public static function hide_cart_product_subtotal( $product_subtotal ) {
		$product_id = 0;
		if ( Functions::check_hide_price( $product_id ) ) return '';
		
		return $product_subtotal;
	}
	
	public static function shop_before_hide_price($template_name, $template_path, $located, $args) {
		if ($template_name == 'loop/price.php' || $template_name == 'single-product/price.php' || 'single-product/bundled-item-price.php' == $template_name ) {
			$product_id = 0;
			
			if (Functions::check_hide_price($product_id))
				ob_start();
		}
	}
	
	public static function shop_after_hide_price($template_name, $template_path, $located, $args) {
		if ($template_name == 'loop/price.php' || $template_name == 'single-product/price.php' || 'single-product/bundled-item-price.php' == $template_name ) {
			$product_id = 0;
			
			if (Functions::check_hide_price($product_id))
				ob_end_clean();
		}
	}

	public static function hide_mini_cart_subtotal( $cart_subtotal='' ) {
		$product_id = 0;
		if ( Functions::check_hide_price( $product_id ) ) return '';
		
		return $cart_subtotal;
	}
	
	public static function hide_mini_cart_contents_total( $cart_contents_total ) {
		$product_id = 0;
		if ( Functions::check_hide_price( $product_id ) ) return '';
		
		return $cart_contents_total;
	}

	public static function render_block_core_shortcode( $content, $parsed_block, $block ) {
		if ( has_shortcode( $content, 'wc_email_inquiry_bt' ) ) {
			$content = do_shortcode( $content );
		}
	    return $content;
	}
	
	public static function add_email_inquiry_button( $product_id, $custom_button = '' ) {
		global $post;
		global $wc_email_inquiry_contact_form_settings;
		global $wc_email_inquiry_customize_email_button;

		$product_name = esc_attr( get_the_title( $product_id ) );
		
		$expand_text = '';
		$inner_form = '';
		$email_inquiry_page_link    = '';
		$target_link                = '';
		$button_attributes          = 'data-toggle="modal" data-target="#wc_email_inquiry_modal"';
		$product_attributes         = 'data-product_name="'.$product_name.'"';
		$email_inquiry_button_class = 'wc_email_inquiry_button';

		$button_attributes .= ' data-form_type="default"';
				
		$wc_email_inquiry_button_type = $wc_email_inquiry_customize_email_button['inquiry_button_type'];
		
		$wc_email_inquiry_text_before = $wc_email_inquiry_customize_email_button['inquiry_text_before'];
		
		$wc_email_inquiry_hyperlink_text = $wc_email_inquiry_customize_email_button['inquiry_hyperlink_text'];
		
		if (trim( $wc_email_inquiry_hyperlink_text ) == '') $wc_email_inquiry_hyperlink_text = __( 'Click Here', 'woocommerce-email-inquiry-cart-options' );
		
		$wc_email_inquiry_trailing_text = $wc_email_inquiry_customize_email_button['inquiry_trailing_text'];
		
		$wc_email_inquiry_button_title = $wc_email_inquiry_customize_email_button['inquiry_button_title'];
		
		if (trim( $wc_email_inquiry_button_title ) == '') $wc_email_inquiry_button_title = __( 'Product Enquiry', 'woocommerce-email-inquiry-cart-options' );
		
		$wc_email_inquiry_button_position = $wc_email_inquiry_customize_email_button['inquiry_button_position'];

		if ( empty( $custom_button ) ) {
				
			$button_link = '';

			if ( trim( $wc_email_inquiry_text_before ) != '' ) {

				$button_link .= '<span class="wc_email_inquiry_text_before wc_email_inquiry_text_before_'.$product_id.'">'.trim($wc_email_inquiry_text_before).'</span> ';
			}

			$button_link .= '<a '.$email_inquiry_page_link. $target_link .' class="wc_email_inquiry_hyperlink_text wc_email_inquiry_hyperlink_text_'.$product_id.' '.$email_inquiry_button_class.'" id="wc_email_inquiry_button_'.$product_id.'" '. $button_attributes .' data-product_id="'.$product_id.'" '. $product_attributes .' form_action="hide">'.$wc_email_inquiry_hyperlink_text.$expand_text.'</a>';

			if ( trim( $wc_email_inquiry_trailing_text ) != '' ) {

				$button_link .= ' <span class="wc_email_inquiry_trailing_text wc_email_inquiry_trailing_text_'.$product_id.'">'.trim($wc_email_inquiry_trailing_text).'</span>';
			}
			
			$button_button = '<a '.$email_inquiry_page_link. $target_link .' class="wc_email_inquiry_email_button wc_email_inquiry_button_'.$product_id.' '.$email_inquiry_button_class.'" id="wc_email_inquiry_button_'.$product_id.'" '. $button_attributes .' data-product_id="'.$product_id.'" '. $product_attributes .' form_action="hide">'.$wc_email_inquiry_button_title.$expand_text.'</a>';

			$button_ouput = '<span class="wc_email_inquiry_button_container">';

			if ($wc_email_inquiry_button_type == 'link') {

				$button_ouput .= $button_link;
			} else {

				$button_ouput .= $button_button;
			}
				
			$button_ouput .= '</span>';
		} else {
			$button_ouput = sprintf( $custom_button, $email_inquiry_page_link.' '.$target_link.' '.$button_attributes.' '.$product_attributes, $email_inquiry_button_class, $expand_text  );
		}

		add_action( 'wp_footer', array( __CLASS__, 'footer_modal_scripts' ) );
		add_action( 'wp_footer', array( __CLASS__, 'footer_default_form_scripts' ) );
			
		return $button_ouput . $inner_form;
	}
	
	public static function shop_add_email_inquiry_button_above( $template_name, $template_path, $located, $args ) {
		global $post;
		global $product;
		if ( $template_name == 'loop/add-to-cart.php' ) {
			$product_id = $product->get_id();
			
			if ( ( $post->post_type == 'product' || $post->post_type == 'product_variation' ) && Functions::check_add_email_inquiry_button( $product_id ) ) {
				echo self::add_email_inquiry_button( $product_id );
			}
		}
	}
	
	public static function shop_add_email_inquiry_button_below() {
		global $post;
		global $product;
		global $wc_email_inquiry_customize_email_button_settings;
		$product_id = $product->get_id();
		
		if ( $wc_email_inquiry_customize_email_button_settings['inquiry_button_position'] == 'above' ) return;
		 
		if ( ( $post->post_type == 'product' || $post->post_type == 'product_variation' ) && Functions::check_add_email_inquiry_button( $product_id ) ) {
			echo self::add_email_inquiry_button( $product_id );
		}
	}

	public static function details_add_email_inquiry_button_above($template_name, $template_path, $located, $args) {
		global $post;
		global $product;

		$addtocart_templates = apply_filters( 'wc_ei_addtocart_templates', array(
			'simple'                => 'single-product/add-to-cart/simple.php',
			'grouped'               => 'single-product/add-to-cart/grouped.php',
			'external'              => 'single-product/add-to-cart/external.php',
			'variable'              => 'single-product/add-to-cart/variable.php',
			'subscription'          => 'single-product/add-to-cart/subscription.php',
			'variable-subscription' => 'single-product/add-to-cart/variable-subscription.php',
		) );

		if ( in_array( $template_name, $addtocart_templates ) || ( is_a( $product, 'WC_Product' ) && $product->is_type( 'bundle' ) && $template_name == 'single-product/short-description.php' ) ) {
			$product_id = $product->get_id();

			if ( ( $post->post_type == 'product' || $post->post_type == 'product_variation' ) && Functions::check_add_email_inquiry_button( $product_id ) ) {
				echo self::add_email_inquiry_button( $product_id );

			}
		}
	}

	public static function details_add_email_inquiry_button_below($template_name, $template_path, $located, $args){
		global $post;
		global $product;

		$addtocart_templates = apply_filters( 'wc_ei_addtocart_templates', array(
			'simple'                => 'single-product/add-to-cart/simple.php',
			'grouped'               => 'single-product/add-to-cart/grouped.php',
			'external'              => 'single-product/add-to-cart/external.php',
			'variable'              => 'single-product/add-to-cart/variable.php',
			'subscription'          => 'single-product/add-to-cart/subscription.php',
			'variable-subscription' => 'single-product/add-to-cart/variable-subscription.php',
		) );

		if ( in_array( $template_name, $addtocart_templates ) || ( is_a( $product, 'WC_Product' ) && $product->is_type( 'bundle' ) && $template_name == 'single-product/short-description.php' ) ) {
			$product_id = $product->get_id();

			if ( ( $post->post_type == 'product' || $post->post_type == 'product_variation' ) && Functions::check_add_email_inquiry_button( $product_id ) ) {
				echo self::add_email_inquiry_button( $product_id );

			}
		}
	}

	public static function wc_email_inquiry_modal_popup() {
		
		wc_ei_load_modal_popup();
	}
		
	public static function add_style_header() {
		wp_enqueue_style('a3_wc_email_inquiry_style', WC_EMAIL_INQUIRY_CSS_URL . '/wc_email_inquiry_style.css', array(), WC_EMAIL_INQUIRY_VERSION );
	}
	
	public static function footer_modal_scripts() {
		
		Functions::enqueue_modal_scripts();

	}

	public static function footer_default_form_scripts() {


		Functions::enqueue_default_form_scripts();
	}
	
	public static function add_google_fonts() {
		global $wc_email_inquiry_global_settings;
		global $wc_email_inquiry_customize_email_button;
		$google_fonts = array( 
							$wc_email_inquiry_global_settings['inquiry_contact_popup_text']['face'], 
							$wc_email_inquiry_customize_email_button['inquiry_button_font']['face'], 
							$wc_email_inquiry_customize_email_button['inquiry_hyperlink_font']['face'],
						);
		
		$google_fonts = apply_filters( 'wc_ei_google_fonts', $google_fonts );
		
		$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'fonts_face']->generate_google_webfonts( $google_fonts );
	}
	
	public static function change_order_item_display_meta_value( $meta_value = '' ) {
		if ( stristr( $meta_value, 'http://' ) !== false || stristr( $meta_value, 'https://' ) !== false ) {
			$meta_value = strip_tags( $meta_value );
			$meta_file_name = basename( $meta_value );
			$meta_value = '<a href="'.$meta_value.'">'.$meta_file_name.'</a>';
		}
		
		return $meta_value;
	}
	
	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', WC_EMAIL_INQUIRY_CSS_URL . '/a3_wp_admin.css' );
	}
	
	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-wc-ei-admin-sidebar-menu-style', WC_EMAIL_INQUIRY_CSS_URL . '/admin_sidebar_menu.css' );
	}

	public static function plugin_extension_box( $boxes = array() ) {

		$support_box = '<a href="'.$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->support_url.'" target="_blank" alt="'.__('Go to Support Forum', 'woocommerce-email-inquiry-cart-options' ).'"><img src="'.WC_EMAIL_INQUIRY_IMAGES_URL.'/go-to-support-forum.png" /></a>';
		$boxes[] = array(
			'content' => $support_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$ultimate_box = '<a href="'.$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->ultimate_plugin_page_url.'" target="_blank" alt="'.__('Go to Support Forum', 'woocommerce-email-inquiry-cart-options' ).'"><img src="'.WC_EMAIL_INQUIRY_IMAGES_URL.'/woocommerce-email-inquiry-ultimate.jpg" /></a>';
		$boxes[] = array(
			'content' => $ultimate_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$connect_box = '<div style="margin-bottom: 5px;">' . __('Connect with us via','woocommerce-email-inquiry-cart-options' ) . '</div>';
		$connect_box .= '<a href="https://www.facebook.com/a3rev" target="_blank" alt="'.__('a3rev Facebook', 'woocommerce-email-inquiry-cart-options' ).'" style="margin-right: 5px;"><img src="'.WC_EMAIL_INQUIRY_IMAGES_URL.'/follow-facebook.png" /></a> ';
		$connect_box .= '<a href="https://twitter.com/a3rev" target="_blank" alt="'.__('a3rev Twitter', 'woocommerce-email-inquiry-cart-options' ).'"><img src="'.WC_EMAIL_INQUIRY_IMAGES_URL.'/follow-twitter.png" /></a>';

		$boxes[] = array(
			'content' => $connect_box,
			'css' => 'border-color: #3a5795;'
		);

		return $boxes;
	}

	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WC_EMAIL_INQUIRY_NAME) {
			return $links;
		}

		$links[] = '<a href="'.$GLOBALS[WC_EMAIL_INQUIRY_PREFIX.'admin_init']->support_url.'" target="_blank">'.__('Support', 'woocommerce-email-inquiry-cart-options' ).'</a>';
		return $links;
	}

	public static function settings_plugin_links($actions) {
		$actions = array_merge( array( 'settings' => '<a href="admin.php?page=email-cart-options">' . __( 'Settings', 'woocommerce-email-inquiry-cart-options' ) . '</a>' ), $actions );

		return $actions;
	}
}
