<?php

/**
 * The public-specific functionality of the plugin.
 *
 * @link       www.redefiningtheweb.com
 * @since      1.0.0
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/public
 */

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/public
 * @author     RedefiningTheWeb <developer@redefiningtheweb.com>
 */
class Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rtwwdpdl_plugin_name    The ID of this plugin.
	 */
	private $rtwwdpdl_plugin_name;
	public $rtwwdpdl_modules = array();
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rtwwdpdl_version    The current version of this plugin.
	 */
	private $rtwwdpdl_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $rtwwdpdl_plugin_name       The name of this plugin.
	 * @param      string    $rtwwdpdl_version    The version of this plugin.
	 */
	public function __construct( $rtwwdpdl_plugin_name, $rtwwdpdl_version ) {

		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/modules/rtwwdpdl-class-module-base.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/modules/rtwwdpdl-class-adv-base.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/modules/rtwwdpdl-class-adv-total.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/modules/rtwwdpdl-class-simple-base.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/modules/rtwwdpdl-class-simple-product.php';

		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/rtwwdpdl-cart-query.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/rtwwdpdl-class-adj-set.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/rtwwdpdl-class-adj-set-product.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/rtwwdpdl-class-adj-set-category.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/rtwwdpdl-class-adj-set-total.php';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/rtwwdpdl-class-compatibility.php';

		$this->rtwwdpdl_plugin_name = $rtwwdpdl_plugin_name;
		$this->rtwwdpdl_version = $rtwwdpdl_version;

		$rtwwdpdl_modules['advanced_totals'] = RTWWDPDL_Advance_Total::rtwwdpdl_instance();
		$this->rtwwdpdl_modules = $rtwwdpdl_modules;

	}

	/**
	 * Register the stylesheets for the public area.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the rtwwdpdl_run() function
		 * defined in Woo_Dynamic_Pricing_Discounts_With_Ai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Dynamic_Pricing_Discounts_With_Ai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->rtwwdpdl_plugin_name, plugin_dir_url( __FILE__ ) . 'css/rtwwdpdl-woo-dynamic-pricing-discounts-lite-public.css', array(), $this->rtwwdpdl_version, 'all' );
		wp_enqueue_style( 'OwlCarousel', RTWWDPDL_URL. 'assets/OwlCarousel/dist/assets/owl.carousel.min.css', array(), $this->rtwwdpdl_version, 'all'  );
		wp_enqueue_style( 'OwlCarouseltheme', RTWWDPDL_URL. 'assets/OwlCarousel/dist/assets/owl.theme.default.css', array(), $this->rtwwdpdl_version, 'all'  );
	}

	/**
	 * Register the JavaScript for the public area.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the rtwwdpdl_run() function
		 * defined in Woo_Dynamic_Pricing_Discounts_With_Ai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Dynamic_Pricing_Discounts_With_Ai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( $this->rtwwdpdl_plugin_name, plugin_dir_url( __FILE__ ) . 'js/rtwwdpdl-woo-dynamic-pricing-discounts-lite-public.js', array( 'jquery' ), $this->rtwwdpdl_version, false );

		wp_enqueue_script( 'OwlCarousel', RTWWDPDL_URL. 'assets/OwlCarousel/dist/owl.carousel.min.js', array(), $this->rtwwdpdl_version, 'all'  );
	}

	/**
	 * Calculating discount on payment method change.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_discnt_on_pay_select(){
		global $woocommerce;
	
		WC()->cart;
	}

	/**
	 * Function to add offer list on product page before add to cart button.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_on_product_page(){
		global $post;
		$rtwwdpdl_offers = get_option('rtwwdpdl_setting_priority');
		$rtwwdpdl_priority = array();
		$rtwwdpdl_select_offer = '';
		$rtwwdpdl_rule_per_page = '';
		$rtwwdpdl_i = 1;
		/////// to get product category ///////
		$rtwwdpdl_terms = get_the_terms( $post->ID, 'product_cat' );
		if(is_array($rtwwdpdl_terms) && !empty($rtwwdpdl_terms))
		{
			foreach ($rtwwdpdl_terms  as $term  ) {
				$rtwwdpdl_product_cat_id = $term->term_id;
				$rtwwdpdl_product_cat_name = $term->name;
				break;
			}
		}
		
        //////// to get product tag /////////
		if(has_term('', 'product_tag'))
		{
			$rtwwdpdl_nterms = get_the_terms( $post->ID, 'product_tag' );
			foreach ($rtwwdpdl_nterms  as $term  ) {
				$rtwwdpdl_product_tag_id = $term->term_id;
				$rtwwdpdl_product_tag_name = $term->name;
				break;
			}
		}

		$rtwwdpdl_today_date = current_time('Y-m-d');

        ////// get category name thorugh category id ///////
		$rtwwdpdl_cat = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		$rtwwdpdl_products = array();
		if(is_array($rtwwdpdl_cat) && !empty($rtwwdpdl_cat))
		{
			foreach ($rtwwdpdl_cat as $value) {
				$rtwwdpdl_products[$value->term_id] = $value->name;
			}
		}
		$rtwwdpdl_product = wc_get_product();
		$rtwwdpdl_prod_id = $rtwwdpdl_product->get_id();
		if(is_array($rtwwdpdl_offers) && !empty($rtwwdpdl_offers))
		{
			foreach ($rtwwdpdl_offers as $key => $value) {
				if($key == 'pro_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'bogo_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'tier_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'pro_com_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'cat_com_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'tier_cat_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}	
				elseif($key == 'var_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'cat_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'bogo_cat_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'attr_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'prod_tag_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif ($key == 'rtw_offer_select') {
					$rtwwdpdl_select_offer = $value;
				}
				elseif ($key == 'rtwwdpdl_rule_per_page') {
					$rtwwdpdl_rule_per_page = $value;
				}
			}
		}
		
        ///////////////// applying rule settings //////////////////
		if($rtwwdpdl_select_offer == 'rtw_first_match')
		{	
			include( RTWWDPDL_DIR . 'public/partials/rtwwdpdl_applied_method/rtwwdpdl_all_match_rule.php' );
		}
	}

	/**
	 * Function to add offer list on cart page.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_on_cart_page(){
		$rtwwdpdl_offers = get_option('rtwwdpdl_setting_priority');
		$rtwwdpdl_today_date = current_time('Y-m-d');
		
		if( isset($rtwwdpdl_offers['cart_rule']) || (  isset( $rtwwdpdl_offers['rtw_tier_offer_on_cart'] ) && $rtwwdpdl_offers['rtw_tier_offer_on_cart'] == 'rtw_price_yes' ))
		{
			include( RTWWDPDL_DIR . 'public/partials/rtwwdpdl_applied_method/rtwwdpdl_cart_setting.php' );	
		}
	}

	/**
	 * Function to display discounted price on cart page.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_on_display_cart_item_price_html($rtwwdpdl_html, $rtwwdpdl_cart_item, $rtwwdpdl_cart_item_key )
	{
		if ( $this->rtwwdpdl_is_cart_item_discounted( $rtwwdpdl_cart_item ) ) {
			$_product = $rtwwdpdl_cart_item['data'];

			if ( function_exists( 'get_product' ) ) {
				if (isset($rtwwdpdl_cart_item['is_deposit']) && $rtwwdpdl_cart_item['is_deposit']) {
					$rtwwdpdl_price_to_calculate = isset( $rtwwdpdl_cart_item['discounts'] ) ? $rtwwdpdl_cart_item['discounts']['price_adjusted'] : $rtwwdpdl_cart_item['data']->get_price();
				} else {
					$rtwwdpdl_price_to_calculate = $rtwwdpdl_cart_item['data']->get_price();
				}

				$rtwwdpdl_price_adjusted = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? wc_get_price_excluding_tax($_product, array('price' => $rtwwdpdl_price_to_calculate, 'qty' => 1)) : wc_get_price_including_tax($_product, array('price' => $rtwwdpdl_price_to_calculate, 'qty' => 1));
				$rtwwdpdl_price_base = $rtwwdpdl_cart_item['discounts']['display_price'];

			} else {
				if ( get_option( 'rtwwdpdl_display_cart_prices_excluding_tax' ) == 'yes' ) :
					$rtwwdpdl_price_adjusted = wc_get_price_excluding_tax($rtwwdpdl_cart_item['data']);
					$rtwwdpdl_price_base = $rtwwdpdl_cart_item['discounts']['display_price'];
				else :
					$rtwwdpdl_price_adjusted = $rtwwdpdl_cart_item['data']->get_price();
					$rtwwdpdl_price_base = $rtwwdpdl_cart_item['discounts']['display_price'];
				endif;
			}

			if($rtwwdpdl_price_adjusted != $rtwwdpdl_price_base){

				if ( !empty( $rtwwdpdl_price_adjusted ) || $rtwwdpdl_price_adjusted === 0 || $rtwwdpdl_price_adjusted === 0.00 ) {
					if ( apply_filters( 'rtwwdpdl_use_discount_format', true ) ) {
						$rtwwdpdl_html = '<del>' . RTWWDPDL_Compatibility::rtw_wc_price( $rtwwdpdl_price_base ) . '</del><ins> ' . RTWWDPDL_Compatibility::rtw_wc_price( $rtwwdpdl_price_adjusted ) . '</ins>';
					} else {
						$rtwwdpdl_html = '<span class="amount">' . RTWWDPDL_Compatibility::rtw_wc_price( $rtwwdpdl_price_adjusted ) . '</span>';
					}
				}
			}
		}
		return $rtwwdpdl_html;
	}
	
	/**
	 * Function to check if product is already discounted.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_is_cart_item_discounted( $rtwwdpdl_cart_item ) {
		return isset( $rtwwdpdl_cart_item['discounts'] );
	}

	/**
	 * Function to calculate discounts on cart page.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_cart_loaded_from_session($cart){
		
		global $woocommerce;
		global $wpdb;
		$rtwwdpdl_sorted_cart = array();
		if ( sizeof( $cart->cart_contents ) > 0 ) {
			foreach ( $cart->cart_contents as $cart_item_key => &$values ) {
				if ( $values === null ) {
					continue;
				}

				if ( isset( $cart->cart_contents[ $cart_item_key ]['discounts'] ) ) {
					unset( $cart->cart_contents[ $cart_item_key ]['discounts'] );
				}
				$rtwwdpdl_sorted_cart[ $cart_item_key ] = &$values;
			}
		}

		if ( empty( $rtwwdpdl_sorted_cart ) ) {
			return;
		}
		//Sort the cart so that the lowest priced item is discounted when using block rules.
		uasort( $rtwwdpdl_sorted_cart, 'RTWWDPDL_Cart_Query::rtw_sort_by_price' );
		$rtwwdpdl_modules = apply_filters( 'rtwwdpdl_load_modules', $this->rtwwdpdl_modules );
		
		foreach ( $rtwwdpdl_modules as $module ) {
			$module->rtwwdpdl_adjust_cart( $rtwwdpdl_sorted_cart );
		}
	}

	/**
	 * Function to calculate discount for other discount rule.
	 *
	 * @since    1.0.0
	 */
	public static function rtw_product_rule_adj($cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_adjusted_price, $module, $set_id ){
		
		if ( $rtwwdpdl_adjusted_price === false ) {
			return;
		}
		$rtwwdpdl_setting_pri = get_option('rtwwdpdl_setting_priority');
		if ( isset( WC()->cart->cart_contents[ $cart_item_key ] ) && ! empty( WC()->cart->cart_contents[ $cart_item_key ] ) ) {


			$_product = WC()->cart->cart_contents[ $cart_item_key ]['data'];
			$rtwwdpdl_display_price = wc_get_price_including_tax( $_product );
			if( isset( $rtwwdpdl_setting_pri['rtw_dscnt_on'] ) && $rtwwdpdl_setting_pri['rtw_dscnt_on'] == 'rtw_sale_price')
			{
				$rtwwdpdl_display_price = $_product->get_price();
			}
			else{
				$rtwwdpdl_display_price = $_product->get_regular_price();
			}
			
			WC()->cart->cart_contents[ $cart_item_key ]['data']->set_price( $rtwwdpdl_adjusted_price );

			if ( $_product->get_type() == 'composite' ) {
				WC()->cart->cart_contents[ $cart_item_key ]['data']->base_price = $rtwwdpdl_adjusted_price;
			}

			if ( ! isset( WC()->cart->cart_contents[ $cart_item_key ]['discounts'] ) ) {

				$rtwwdpdl_discount_data                                           = array(
					'by'                => array( $module ),
					'set_id'            => $set_id,
					'price_base'        => $rtwwdpdl_original_price,
					'display_price'     => $rtwwdpdl_display_price,
					'price_adjusted'    => $rtwwdpdl_adjusted_price,
					'applied_discounts' => array(
						array(
							'by'             => $module,
							'set_id'         => $set_id,
							'price_base'     => $rtwwdpdl_original_price,
							'price_adjusted' => $rtwwdpdl_adjusted_price
						)
					)
				);
				WC()->cart->cart_contents[ $cart_item_key ]['discounts'] = $rtwwdpdl_discount_data;
				
			} else {

				$rtwwdpdl_existing = WC()->cart->cart_contents[ $cart_item_key ]['discounts'];

				$rtwwdpdl_discount_data = array(
					'by'             => $rtwwdpdl_existing['by'],
					'set_id'         => $set_id,
					'price_base'     => $rtwwdpdl_original_price,
					'display_price'  => $rtwwdpdl_existing['display_price'],
					'price_adjusted' => $rtwwdpdl_adjusted_price
				);

				WC()->cart->cart_contents[ $cart_item_key ]['discounts'] = $rtwwdpdl_discount_data;

				$history = array(
					'by'             => $rtwwdpdl_existing['by'],
					'set_id'         => $rtwwdpdl_existing['set_id'],
					'price_base'     => $rtwwdpdl_existing['price_base'],
					'price_adjusted' => $rtwwdpdl_existing['price_adjusted']
				);

				array_push( WC()->cart->cart_contents[ $cart_item_key ]['discounts']['by'], $module );
				
				WC()->cart->cart_contents[ $cart_item_key ]['discounts']['applied_discounts'][] = $history;
			}
		}
	}

	/**
	 * Function to calculate discount for cart discount rule.
	 *
	 * @since    1.0.0
	 */
	public static function rtw_apply_cart_item_adjustment( $cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_adjusted_price, $module, $set_id ) {
		$rtwwdpdl_setting_pri = get_option('rtwwdpdl_setting_priority');
		do_action( 'rtwwdpdl_memberships_discounts_disable_price_adjustments' );
		$rtwwdpdl_adjusted_price = apply_filters( 'rtwwdpdl_dynamic_pricing_apply_cart_item_adjustment', $rtwwdpdl_adjusted_price, $cart_item_key, $rtwwdpdl_original_price, $module );

		//Allow extensions to stop processing of applying the discount.  Added for subscriptions signup fee compatibility
		if ( $rtwwdpdl_adjusted_price === false ) {
			return;
		}

		if ( isset( WC()->cart->cart_contents[ $cart_item_key ] ) && ! empty( WC()->cart->cart_contents[ $cart_item_key ] ) ) {


			$_product = WC()->cart->cart_contents[ $cart_item_key ]['data'];
			
			if ( apply_filters( 'rtwwdpdl_dynamic_pricing_get_use_sale_price', true, $_product ) ) {
				$rtwwdpdl_display_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? wc_get_price_excluding_tax( $_product ) : wc_get_price_including_tax( $_product );
			} else {
				$rtwwdpdl_display_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? wc_get_price_excluding_tax( $_product, array( 'price' => $rtwwdpdl_original_price ) ) : wc_get_price_including_tax( $_product, array( 'price' => $rtwwdpdl_original_price ) );
			}
			if( isset( $rtwwdpdl_setting_pri['rtw_dscnt_on'] ) && $rtwwdpdl_setting_pri['rtw_dscnt_on'] == 'rtw_sale_price')
			{
				$rtwwdpdl_display_price = $_product->get_price();
			}
			else{
				$rtwwdpdl_display_price = $_product->get_regular_price();
			}
			
			WC()->cart->cart_contents[ $cart_item_key ]['data']->set_price( $rtwwdpdl_adjusted_price );

			if ( $_product->get_type() == 'composite' ) {
				WC()->cart->cart_contents[ $cart_item_key ]['data']->base_price = $rtwwdpdl_adjusted_price;
			}

			if ( ! isset( WC()->cart->cart_contents[ $cart_item_key ]['discounts'] ) ) {

				$rtwwdpdl_discount_data                                           = array(
					'by'                => array( $module ),
					'set_id'            => $set_id,
					'price_base'        => $rtwwdpdl_original_price,
					'display_price'     => $rtwwdpdl_display_price,
					'price_adjusted'    => $rtwwdpdl_adjusted_price,
					'applied_discounts' => array(
						array(
							'by'             => $module,
							'set_id'         => $set_id,
							'price_base'     => $rtwwdpdl_original_price,
							'price_adjusted' => $rtwwdpdl_adjusted_price
						)
					)
				);
				WC()->cart->cart_contents[ $cart_item_key ]['discounts'] = $rtwwdpdl_discount_data;
			} else {

				$rtwwdpdl_existing = WC()->cart->cart_contents[ $cart_item_key ]['discounts'];

				$rtwwdpdl_discount_data = array(
					'by'             => $rtwwdpdl_existing['by'],
					'set_id'         => $set_id,
					'price_base'     => $rtwwdpdl_original_price,
					'display_price'  => $rtwwdpdl_existing['display_price'],
					'price_adjusted' => $rtwwdpdl_adjusted_price
				);

				WC()->cart->cart_contents[ $cart_item_key ]['discounts'] = $rtwwdpdl_discount_data;

				$history = array(
					'by'             => $rtwwdpdl_existing['by'],
					'set_id'         => $rtwwdpdl_existing['set_id'],
					'price_base'     => $rtwwdpdl_existing['price_base'],
					'price_adjusted' => $rtwwdpdl_existing['price_adjusted']
				);
				array_push( WC()->cart->cart_contents[ $cart_item_key ]['discounts']['by'], $module );
				WC()->cart->cart_contents[ $cart_item_key ]['discounts']['applied_discounts'][] = $history;
			}
		}
		do_action( 'rtwwdpdl_memberships_discounts_enable_price_adjustments' );
		do_action( 'rtwwdpdl_dynamic_pricing_apply_cartitem_adjustment', $cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_adjusted_price, $module, $set_id );
	}

	/**
	 * Function to calculate discounts.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_before_calculate_totals($cart){
		global $woocommerce;
		global $wpdb;

		$rtwwdpdl_today_date = current_time('Y-m-d');
		$rtwwdpdl_get_option = get_option('rtwwdpdl_bogo_rule');
		$rtwwdpdl_get_settings = get_option('rtwwdpdl_setting_priority');
		$rtwwdpdl_cat_ids = array();
		$ii = 0;
		if(is_array($cart->cart_contents) && !empty($cart->cart_contents))
		{
			foreach ($cart->cart_contents as $cart_item_key => $cart_item) {
				$rtwwdpdl_cat_ids[] = $cart_item['data']->get_category_ids();
				if(isset($rtwwdpdl_get_settings['bogo_rule']) && $rtwwdpdl_get_settings['bogo_rule'] == 1)
				{
					if(is_array($rtwwdpdl_get_option) && !empty($rtwwdpdl_get_option))
					{
						foreach ($rtwwdpdl_get_option as $key => $value) {
							$rtwwdpdl_free_p_id = $value['rtwbogo'][$ii];
							$rtwwdpdl_p_quant = $value['combi_quant'][$ii];
							$rtwwdpdl_pro_id = $value['product_id'][$ii];
							$rtwwdpdl_free_qunt = $value['bogo_quant_free'][$ii];

							if($rtwwdpdl_pro_id == $cart_item['product_id'] && $rtwwdpdl_p_quant <= $cart_item['quantity'])
							{
								if ( sizeof( WC()->cart->get_cart() ) > 0 ) 
								{
									if($rtwwdpdl_get_settings['rtw_auto_add_bogo'] == 'rtw_yes')
									{
										foreach ( WC()->cart->get_cart() as $cart_item_k => $val ) {
											$_product = $val['data'];
											if ( $_product->get_id() == $rtwwdpdl_free_p_id )
											{
												$found = true;
												$__price = $val['data']->get_price();
												$quantt = $val['quantity'];

												$pos = stripos($cart_item_k, 'rtw_free_prod');
												if($quantt == $rtwwdpdl_free_qunt)
												{
													if($pos !== false)
													{
														$this->rtw_product_rule_bogo( $cart_item_k, $__price, 0, 'bogo', '', $quantt, $key );
													}
												}
												else{
													if($pos !== false)
													{
														$this->rtw_product_rule_bogo( $cart_item_k, $__price, 0, 'bogo', '', $quantt, $key );
													}
												}
											}
										}
									}
								} 
							}
						}
					}
				}
			}
		}
		
		$rtwwdpdl_today_date = current_time('Y-m-d');
		$rtwwdpdl_get_settings = get_option('rtwwdpdl_setting_priority');
		$rtwwdpdl_i = 0;
		$rtwwdpdl_priority = array();
		if(is_array($rtwwdpdl_get_settings) && !empty($rtwwdpdl_get_settings)){
			foreach ($rtwwdpdl_get_settings as $key => $value) {
				if($key == 'cart_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'pro_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'bogo_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'tier_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif($key == 'cat_rule_row')
				{
					$rtwwdpdl_priority[$rtwwdpdl_i] = $key;
					$rtwwdpdl_i++;
				}
				elseif ($key == 'rtw_offer_select') {
					$rtwwdpdl_select_offer = $value;
				}
				elseif ($key == 'rtwwdpdl_rule_per_page') {
					$rtwwdpdl_rule_per_page = $value;
				}
			}
		}
		$this->rtwwdpdl_set_rules = $rtwwdpdl_priority;
	}

	/**
	 * Function to calculate discount for bogo discount rule.
	 *
	 * @since    1.0.0
	 */
	public function rtw_product_rule_bogo( $cart_item_key, $rtwwdpdl_original_price, $rtwwdpdl_adjusted_price, $module, $set_id, $quant, $key ){

		if ( $rtwwdpdl_adjusted_price === false ) {
			return;
		}
		
		if ( isset( WC()->cart->cart_contents[ $cart_item_key ] ) && ! empty( WC()->cart->cart_contents[ $cart_item_key ] ) ) {


			$_product = WC()->cart->cart_contents[ $cart_item_key ]['data'];
			$rtwwdpdl_display_price = wc_get_price_including_tax( $_product );
			
			WC()->cart->cart_contents[ $cart_item_key ]['data']->set_price( $rtwwdpdl_adjusted_price);

			if ( $_product->get_type() == 'composite' ) {
				WC()->cart->cart_contents[ $cart_item_key ]['data']->base_price = $rtwwdpdl_adjusted_price;
			}

			if ( ! isset( WC()->cart->cart_contents[ $cart_item_key ]['discounts'] ) ) {

				$rtwwdpdl_discount_data                                           = array(
					'by'                => array( $module ),
					'set_id'            => $set_id,
					'price_base'        => $rtwwdpdl_original_price,
					'display_price'     => $rtwwdpdl_display_price,
					'price_adjusted'    => $rtwwdpdl_adjusted_price,
					'applied_discounts' => array(
						array(
							'by'             => $module,
							'set_id'         => $set_id,
							'price_base'     => $rtwwdpdl_original_price,
							'price_adjusted' => $rtwwdpdl_adjusted_price
						)
					)
				);
				WC()->cart->cart_contents[ $cart_item_key ]['discounts'] = $rtwwdpdl_discount_data;
				
			} else {

				$rtwwdpdl_existing = WC()->cart->cart_contents[ $cart_item_key ]['discounts'];

				$rtwwdpdl_discount_data = array(
					'by'             => $rtwwdpdl_existing['by'],
					'set_id'         => $set_id,
					'price_base'     => $rtwwdpdl_original_price,
					'display_price'  => $rtwwdpdl_existing['display_price'],
					'price_adjusted' => $rtwwdpdl_adjusted_price
				);

				WC()->cart->cart_contents[ $cart_item_key ]['discounts'] = $rtwwdpdl_discount_data;

				$history = array(
					'by'             => $rtwwdpdl_existing['by'],
					'set_id'         => $rtwwdpdl_existing['set_id'],
					'price_base'     => $rtwwdpdl_existing['price_base'],
					'price_adjusted' => $rtwwdpdl_existing['price_adjusted']
				);

				array_push( WC()->cart->cart_contents[ $cart_item_key ]['discounts']['by'], $module );
				
				WC()->cart->cart_contents[ $cart_item_key ]['discounts']['applied_discounts'][] = $history;
			}
		}
	}

	/**
	 * Function to give discount based on cart rule.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_sale_custom_price($cart_object) {
		// Calculate discount amount and return $discount
		global $woocommerce;
		$cart_object_main = $cart_object;
		$rtwwdpdl_cart_total = $woocommerce->cart->get_subtotal();
		$cart_object = $cart_object->cart_contents;
		
		$rtwwdpdl_total_weig = 0;
		$rtwwdpdl_prod_count = 0;
		$rtwwdpdl_temp_cart = $cart_object;
		if( is_array($rtwwdpdl_temp_cart) && !empty($rtwwdpdl_temp_cart) )
		{
			foreach ( $rtwwdpdl_temp_cart as $cart_item_key => $values ) {
				if( isset($values['discounts']) && isset($values['discounts']['by']) )
				{
					if(in_array( 'advanced_totals', $values['discounts']['by']))
					{
						return;
					}
				}

				if( isset( $values['quantity'] ) &&  $values['quantity'] != '' )
				{
					$rtwwdpdl_prod_count += $values['quantity'];
				}
				if( $values['data']->get_weight() != '' )
				{
					$rtwwdpdl_total_weig += $values['data']->get_weight();
				}
			}
		}
		if( is_array($cart_object) && !empty($cart_object) )
		{
			$rtwwdpdl_setting_pri = get_option('rtwwdpdl_setting_priority');
			if( isset( $rtwwdpdl_setting_pri['cart_rule'] ) && $rtwwdpdl_setting_pri['cart_rule'] == 1 )
			{
				$rtwwdpdl_today_date = current_time('Y-m-d');	
				$rtwwdpdl_user = wp_get_current_user();
				$rtwwdpdl_car_rul = get_option('rtwwdpdl_cart_rule');

				$rtwwdpdl_dis_array_fixed = array();
				$rtwwdpdl_dis_val = 0;
				if(!is_array($rtwwdpdl_car_rul) || empty($rtwwdpdl_car_rul))
				{
					return;
				}		
				foreach ($rtwwdpdl_car_rul as $car => $rul) 
				{
					if($rul['rtwwdpdl_from_date'] > $rtwwdpdl_today_date || $rul['rtwwdpdl_to_date'] < $rtwwdpdl_today_date)
					{
						continue 1;
					}
					
					if($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_quantity')
					{
						if($rtwwdpdl_prod_count < $rul['rtwwdpdl_min'])
						{
							continue 1;
						}
						if(isset($rul['rtwwdpdl_max']) && $rul['rtwwdpdl_max'] != '')
						{
							if($rul['rtwwdpdl_max'] < $rtwwdpdl_prod_count)
							{
								continue 1;
							}
						}
					}
					elseif($rul['rtwwdpdl_check_for'] == 'rtwwdpdl_price')
					{
						if($rtwwdpdl_cart_total < $rul['rtwwdpdl_min'])
						{
							continue 1;
						}
						if(isset($rul['rtwwdpdl_max']) && $rul['rtwwdpdl_max_cat'] != '')
						{
							if($rul['rtwwdpdl_max'] < $rtwwdpdl_cart_total)
							{
								continue 1;
							}
						}
					}
					else{
						if(isset($rul['rtwwdpdl_min']) && $rul['rtwwdpdl_min'] != '')
						{
							if($rtwwdpdl_total_weig < $rul['rtwwdpdl_min'])
							{
								continue 1;
							}
						}
						if(isset($rul['rtwwdpdl_max']) && $rul['rtwwdpdl_max'] != '')
						{
							if($rul['rtwwdpdl_max'] < $rtwwdpdl_total_weig)
							{
								continue 1;
							}
						}
					}


					$rtwwdpdl_amount = $rul['rtwwdpdl_discount_value'];
					if( $rul['rtwwdpdl_discount_type'] != 'rtwwdpdl_discount_percentage' )
					{
						$cart_object_main->add_fee('Discount', -$rtwwdpdl_amount, true, '');
					}
					else{
						$discount_value = ( ( $rtwwdpdl_amount / 100 ) * $rtwwdpdl_cart_total );

						$cart_object_main->add_fee('Discount', -$discount_value, true, '');
					}
				}
			}
		}
	}

	/**
	 * Function to show custom message to logged out users.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_offers_message()
	{
		if( !is_user_logged_in() )
		{
			$message_settings = get_option('rtwwdpdl_message_settings', array());
			if( isset( $message_settings['rtwwdpdl_message_text'] ) && !empty( $message_settings['rtwwdpdl_message_text'] ) )
			{
				echo stripcslashes($message_settings['rtwwdpdl_message_text']);
			}
		}
	}
}
