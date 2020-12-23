<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Dynamic_Pricing_And_Discount_Pro
 * @subpackage Woocommerce_Dynamic_Pricing_And_Discount_Pro/public
 * @author     Multidots <inquiry@multidots.in>
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woocommerce_Dynamic_Pricing_And_Discount_Pro_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-dynamic-pricing-and-discount-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-dynamic-pricing-and-discount-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	function woocommerce_locate_template_product_dpad_conditions( $template, $template_name, $template_path ) {

		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = woocommerce_conditional_discount_rules_for_checkout_pro_path() . '/woocommerce/';

		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name,
			)
		);

		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found
		return $template;
	}

	/**
	 * @param $package
	 */
	public function conditional_dpad_add_to_cart( $package ) {
		global $woocommerce, $woocommerce_wpml, $sitepress;

		if ( ! empty( $sitepress ) ) {
			$default_lang = $sitepress->get_default_language();
		}

		$dpad_args = array(
			'post_type'      => 'wc_dynamic_pricing',
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'posts_per_page' => - 1,
			'suppress_filters' => false,
			'order' => 'ASC',
            'orderby' => 'meta_value',
            'meta_key' => 'wcpfc-pro-condition_order'
		);

		$get_all_dpad = get_posts( $dpad_args ); // phpcs:ignore

		$cart_array                = $woocommerce->cart->get_cart();
		$cart_sub_total            = $woocommerce->cart->subtotal;
		$cart_final_products_array = array();
		$cart_products_subtotal    = 0;

		if ( ! empty( $get_all_dpad ) ) {
			$ij=1;
			foreach ( $get_all_dpad as $dpad ) {
				
				$is_passed = array();

				$cart_based_qty = 0;
				foreach ( $cart_array as  $woo_cart_item_for_qty ) {
					$cart_based_qty += $woo_cart_item_for_qty['quantity'];
				}

				$dpad_title          = get_the_title( $dpad->ID );
				$title               = ! empty( $dpad_title ) ? __( $dpad_title, WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ) : __( 'Fee', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
				$getFeesCostOriginal = get_post_meta( $dpad->ID, 'dpad_settings_product_cost', true );
				$getFeeType          = get_post_meta( $dpad->ID, 'dpad_settings_select_dpad_type', true );

				if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
					if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'fixed' ) {
						$getFeesCost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $getFeesCostOriginal );
					} else {
						$getFeesCost = $getFeesCostOriginal;
					}
				} else {
					$getFeesCost = $getFeesCostOriginal;
				}

				$getFeesPerQtyFlag        = get_post_meta( $dpad->ID, 'dpad_chk_qty_price', true );
				$getFeesPerQty            = get_post_meta( $dpad->ID, 'dpad_per_qty', true );
				$extraProductCostOriginal = get_post_meta( $dpad->ID, 'extra_product_cost', true );

				if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
					$extraProductCost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $extraProductCostOriginal );
				} else {
					$extraProductCost = $extraProductCostOriginal;
				}

				$getFeetaxable   = get_post_meta( $dpad->ID, 'dpad_settings_select_taxable', true );
				$getFeeStartDate = get_post_meta( $dpad->ID, 'dpad_settings_start_date', true );
				$getFeeEndDate   = get_post_meta( $dpad->ID, 'dpad_settings_end_date', true );
				$getFeeStatus    = get_post_meta( $dpad->ID, 'dpad_settings_status', true );

				if ( isset( $getFeeStatus ) && $getFeeStatus === 'off' ) {
					continue;
				}

				$get_condition_array = get_post_meta( $dpad->ID, 'dynamic_pricing_metabox', true );

				/* Percentage Logic Start */
				if ( isset( $getFeesCost ) && ! empty( $getFeesCost ) ) {

					if ( ! empty( $get_condition_array ) ) {

						$cart_products_subtotal     = 0;
						$cart_cat_products_subtotal = 0;
						$cart_tag_products_subtotal = 0;

						$product_based_percentage_subtotal = 0;
						$percentage_subtotal               = 0;

						$product_specific_flag = 0;
						$products_based_qty    = 0;

						foreach ( $get_condition_array as $key => $condition ) {
							if ( array_search( 'product', $condition,true ) ) {

								$site_product_id           = '';
								$cart_final_products_array = array();

								/* Product Condition Start */
								if ( $condition['product_dpad_conditions_is'] === 'is_equal_to' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										foreach ( $condition['product_dpad_conditions_values'] as $product_id ) {
											foreach ( $cart_array as $key => $value ) {

												if ( ! empty( $sitepress ) ) {
													$site_product_id = apply_filters( 'wpml_object_id', $value['product_id'], 'product', true, $default_lang );
												} else {
													$site_product_id = $value['product_id'];
												}

												if ( (int)$product_id === (int)$site_product_id ) {
													$cart_final_products_array[] = $value;
												}
											}
										}
									}
								} elseif ( $condition['product_dpad_conditions_is'] === 'not_in' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										foreach ( $condition['product_dpad_conditions_values'] as $product_id ) {
											foreach ( $cart_array as $key => $value ) {

												if ( ! empty( $sitepress ) ) {
													$site_product_id = apply_filters( 'wpml_object_id', $value['product_id'], 'product', true, $default_lang );
												} else {
													$site_product_id = $value['product_id'];
												}

												if ( (int)$product_id !== (int)$site_product_id ) {
													$cart_final_products_array[] = $value;
												}
											}
										}
									}
								}

								if ( ! empty( $cart_final_products_array ) ) {
									$product_specific_flag = 1;
									foreach ( $cart_final_products_array as $cart_item ) {

										$products_based_qty += $cart_item['quantity'];

										$line_item_subtotal     = $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'];
										$cart_products_subtotal += $line_item_subtotal;
									}
								}
								/* Product Condition End */
							}
							if ( array_search( 'variableproduct', $condition,true ) ) {

								$site_product_id           = '';
								$cart_final_products_array = array();
								/* Variable Product Condition Start */

								if ( $condition['product_dpad_conditions_is'] === 'is_equal_to' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										foreach ( $condition['product_dpad_conditions_values'] as $product_id ) {
											foreach ( $cart_array as $key => $value ) {

												if ( ! empty( $sitepress ) ) {
													$site_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
												} else {
													$site_product_id = $value['variation_id'];
												}

												if ( (int)$product_id === (int)$site_product_id ) {
													$cart_final_products_array[] = $value;
												}
											}
										}
									}
								} elseif ( $condition['product_dpad_conditions_is'] === 'not_in' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										foreach ( $condition['product_dpad_conditions_values'] as $product_id ) {
											foreach ( $cart_array as $key => $value ) {

												if ( ! empty( $sitepress ) ) {
													$site_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
												} else {
													$site_product_id = $value['variation_id'];
												}
												if ( (int)$product_id !== (int)$site_product_id ) {
													$cart_final_products_array[] = $value;
												}
											}
										}
									}
								}


								if ( ! empty( $cart_final_products_array ) ) {
									$product_specific_flag = 1;
									foreach ( $cart_final_products_array as $cart_item ) {

										$products_based_qty += $cart_item['quantity'];

										$line_item_subtotal     = $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'];
										$cart_products_subtotal += $line_item_subtotal;
									}
								}
								/* Variable Product Condition End */
							}
							if ( array_search( 'category', $condition,true ) ) {

								/* Category Condition Start */
								$final_cart_products_cats_ids  = array();
								$cart_final_cat_products_array = array();

								$all_cats = get_terms(
									array(
										'taxonomy' => 'product_cat',
										'fields'   => 'ids',
									)
								);

								if ( $condition['product_dpad_conditions_is'] === 'is_equal_to' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										foreach ( $condition['product_dpad_conditions_values'] as $category_id ) {
											$final_cart_products_cats_ids[] = $category_id;
										}
									}
								} elseif ( $condition['product_dpad_conditions_is'] === 'not_in' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										$final_cart_products_cats_ids = array_diff( $all_cats, $condition['product_dpad_conditions_values'] );
									}
								}

								$cat_args         = array(
									'post_type'      => 'product',
									'posts_per_page' => - 1,
									'order'          => 'ASC',
									'fields'         => 'ids',
									'suppress_filters' => false,
									'tax_query'      => array(
										array(
											'taxonomy' => 'product_cat',
											'field'    => 'term_id',
											'terms'    => $final_cart_products_cats_ids,
										),
									),
								);
								$cat_products_ids = get_posts( $cat_args ); // phpcs:ignore

								foreach ( $cart_array as $key => $value ) {
									if ( in_array( (int)$value['product_id'], convert_array_to_int($cat_products_ids),true ) ) {
										$cart_final_cat_products_array[] = $value;
									}
								}

								if ( ! empty( $cart_final_cat_products_array ) ) {
									$product_specific_flag = 1;
									foreach ( $cart_final_cat_products_array as $cart_item ) {

										$products_based_qty += $cart_item['quantity'];

										$line_item_subtotal         = $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'];
										$cart_cat_products_subtotal += $line_item_subtotal;
									}
								}
								/* Category Condition End */
							}
							if ( array_search( 'tag', $condition,true ) ) {

								/* Tag Condition Start */
								$final_cart_products_tag_ids   = array();
								$cart_final_tag_products_array = array();

								$all_tags = get_terms(
									array(
										'taxonomy' => 'product_tag',
										'fields'   => 'ids',
									)
								);

								if ( $condition['product_dpad_conditions_is'] === 'is_equal_to' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										foreach ( $condition['product_dpad_conditions_values'] as $tag_id ) {
											$final_cart_products_tag_ids[] = $tag_id;
										}
									}
								} elseif ( $condition['product_dpad_conditions_is'] === 'not_in' ) {
									if ( ! empty( $condition['product_dpad_conditions_values'] ) ) {
										$final_cart_products_tag_ids = array_diff( $all_tags, $condition['product_dpad_conditions_values'] );
									}
								}

								$tag_args         = array(
									'post_type'      => 'product',
									'posts_per_page' => - 1,
									'order'          => 'ASC',
									'fields'         => 'ids',
									'suppress_filters' => false,
									'tax_query'      => array(
										array(
											'taxonomy' => 'product_tag',
											'field'    => 'term_id',
											'terms'    => $final_cart_products_tag_ids,
										),
									),
								);
								$tag_products_ids = get_posts( $tag_args ); // phpcs:ignore

								foreach ( $cart_array as $key => $value ) {
									if ( in_array( (int)$value['product_id'], convert_array_to_int($tag_products_ids),true ) ) {
										$cart_final_tag_products_array[] = $value;
									}
								}

								if ( ! empty( $cart_final_tag_products_array ) ) {
									$product_specific_flag = 1;
									foreach ( $cart_final_tag_products_array as $cart_item ) {

										$products_based_qty += $cart_item['quantity'];

										$line_item_subtotal         = $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'];
										$cart_tag_products_subtotal += $line_item_subtotal;
									}
								}
								/* Tag Condition End */
							}
							$product_based_percentage_subtotal = $cart_products_subtotal + $cart_cat_products_subtotal + $cart_tag_products_subtotal;
						}

						if ( (int)$product_specific_flag === 1 ) {
							$percentage_subtotal = $product_based_percentage_subtotal;
						} else {
							$products_based_qty  = $cart_based_qty;
							$percentage_subtotal = $cart_sub_total;
						}
					}

					if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'percentage' ) {

						$percentage_fee = ( $percentage_subtotal * $getFeesCost ) / 100;

						if ( $getFeesPerQtyFlag === 'on' ) {
							if ( $getFeesPerQty === 'qty_cart_based' ) {
								$dpad_cost = $percentage_fee + ( ( $cart_based_qty - 1 ) * $extraProductCost );
							} else if ( $getFeesPerQty === 'qty_product_based' ) {
								$dpad_cost = $percentage_fee + ( ( $products_based_qty - 1 ) * $extraProductCost );
							}
						} else {
							$dpad_cost = $percentage_fee;
						}
					} else {
						$fixed_fee = $getFeesCost;
						if ( $getFeesPerQtyFlag === 'on' ) {
							if ( $getFeesPerQty === 'qty_cart_based' ) {
								$dpad_cost = $fixed_fee + ( ( $cart_based_qty - 1 ) * $extraProductCost );
							} else if ( $getFeesPerQty === 'qty_product_based' ) {
								$dpad_cost = $fixed_fee + ( ( $products_based_qty - 1 ) * $extraProductCost );
							}
						} else {
							$dpad_cost = $fixed_fee;
						}
					}
				} else {
					$dpad_cost = '';
				}

				if ( ! empty( $get_condition_array ) ) {
					$country_array         = array();
					$state_array           = array();
					$postcode_array        = array();
					$zone_array            = array();
					$product_array         = array();
					$variableproduct_array = array();
					$category_array        = array();
					$tag_array             = array();
					$user_array            = array();
					$user_role_array       = array();
					$cart_total_array      = array();
					$cart_totalafter_array = array();
					$quantity_array        = array();
					$weight_array          = array();
					$coupon_array          = array();
					$shipping_class_array  = array();
					$payment_gateway       = array();
					$shipping_methods      = array();
					foreach ( $get_condition_array as $key => $value ) {
						if ( array_search( 'country', $value,true ) ) {
							$country_array[ $key ] = $value;
						}
						if ( array_search( 'state', $value,true ) ) {
							$state_array[ $key ] = $value;
						}
						if ( array_search( 'postcode', $value,true ) ) {
							$postcode_array[ $key ] = $value;
						}
						if ( array_search( 'zone', $value,true ) ) {
							$zone_array[ $key ] = $value;
						}
						if ( array_search( 'product', $value,true ) ) {
							$product_array[ $key ] = $value;
						}
						if ( array_search( 'variableproduct', $value,true ) ) {
							$variableproduct_array[ $key ] = $value;
						}
						if ( array_search( 'category', $value,true ) ) {
							$category_array[ $key ] = $value;
						}
						if ( array_search( 'tag', $value,true ) ) {
							$tag_array[ $key ] = $value;
						}
						if ( array_search( 'user', $value,true ) ) {
							$user_array[ $key ] = $value;
						}
						if ( array_search( 'user_role', $value,true ) ) {
							$user_role_array[ $key ] = $value;
						}
						if ( array_search( 'cart_total', $value,true ) ) {
							$cart_total_array[ $key ] = $value;
						}
						if ( array_search( 'cart_totalafter', $value,true ) ) {
							$cart_totalafter_array[ $key ] = $value;
						}
						if ( array_search( 'quantity', $value,true ) ) {
							$quantity_array[ $key ] = $value;
						}
						if ( array_search( 'weight', $value,true ) ) {
							$weight_array[ $key ] = $value;
						}
						if ( array_search( 'coupon', $value,true ) ) {
							$coupon_array[ $key ] = $value;
						}
						if ( array_search( 'shipping_class', $value,true ) ) {
							$shipping_class_array[ $key ] = $value;
						}
						if ( array_search( 'payment', $value,true ) ) {
							$payment_gateway[ $key ] = $value;
						}
						if ( array_search( 'shipping_method', $value,true ) ) {
							$shipping_methods[ $key ] = $value;
						}
					}

					//Check if is country exist
					if ( is_array( $country_array ) && isset( $country_array ) && ! empty( $country_array ) && ! empty( $cart_array ) ) {
						$selected_country                       = $woocommerce->customer->get_shipping_country();
						$is_passed['has_dpad_based_on_country'] = '';
						$passed_country                         = array();
						$notpassed_country                      = array();
						foreach ( $country_array as $country ) {
							if ( $country['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $country['product_dpad_conditions_values'] ) ) {
									foreach ( $country['product_dpad_conditions_values'] as $country_id ) {
										$passed_country[] = $country_id;
										if ( $country_id === $selected_country ) {
											$is_passed['has_dpad_based_on_country'] = 'yes';
										}
									}
								}
							}
							if ( $country['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $country['product_dpad_conditions_values'] ) ) {
									foreach ( $country['product_dpad_conditions_values'] as $country_id ) {
										$notpassed_country[] = $country_id;
										if ( $country_id === 'all' || $country_id === $selected_country ) {
											$is_passed['has_dpad_based_on_country'] = 'no';
										}
									}
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_country'] ) && empty( $passed_country ) ) {
							$is_passed['has_dpad_based_on_country'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_country'] ) && ! empty( $passed_country ) ) {
							$is_passed['has_dpad_based_on_country'] = 'no';
						}
					}

					//Check if is state exist
					if ( is_array( $state_array ) && isset( $state_array ) && ! empty( $state_array ) && ! empty( $cart_array ) ) {
						$country                              = $woocommerce->customer->get_shipping_country();
						$state                                = $woocommerce->customer->get_shipping_state();
						$selected_state                       = $country . ':' . $state;
						$is_passed['has_dpad_based_on_state'] = '';
						$passed_state                         = array();
						$notpassed_state                      = array();
						foreach ( $state_array as $state ) {
							if ( $state['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $state['product_dpad_conditions_values'] ) ) {
									foreach ( $state['product_dpad_conditions_values'] as $state_id ) {
										$passed_state[] = $state_id;
										if ( $state_id === $selected_state ) {
											$is_passed['has_dpad_based_on_state'] = 'yes';
										}
									}
								}
							}
							if ( $state['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $state['product_dpad_conditions_values'] ) ) {
									foreach ( $state['product_dpad_conditions_values'] as $state_id ) {
										$notpassed_state[] = $state_id;
										if ( $state_id === $selected_state ) {
											$is_passed['has_dpad_based_on_state'] = 'no';
										}
									}
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_state'] ) && empty( $passed_state ) ) {
							$is_passed['has_dpad_based_on_state'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_state'] ) && ! empty( $passed_state ) ) {
							$is_passed['has_dpad_based_on_state'] = 'no';
						}
					}

					//Check if is postcode exis
					if ( is_array( $postcode_array ) && isset( $postcode_array ) && ! empty( $postcode_array ) && ! empty( $cart_array ) ) {
						$selected_postcode                       = $woocommerce->customer->get_shipping_postcode();
						$is_passed['has_dpad_based_on_postcode'] = '';
						$passed_postcode                         = array();
						$notpassed_postcode                      = array();
						foreach ( $postcode_array as $postcode ) {
							if ( $postcode['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $postcode['product_dpad_conditions_values'] ) ) {
									$postcodestr        = str_replace( PHP_EOL, "<br/>", $postcode['product_dpad_conditions_values'] );
									$postcode_val_array = explode( '<br/>', $postcodestr );
									foreach ( $postcode_val_array as $postcode_id ) {
										$postcodeId        = trim( $postcode_id );
										$passed_postcode[] = $postcodeId;
										if ( $postcodeId === $selected_postcode ) {
											$is_passed['has_dpad_based_on_postcode'] = 'yes';
										}
									}
								}
							}
							if ( $postcode['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $postcode['product_dpad_conditions_values'] ) ) {
									$postcodestr        = str_replace( PHP_EOL, "<br/>", $postcode['product_dpad_conditions_values'] );
									$postcode_val_array = explode( '<br/>', $postcodestr );
									foreach ( $postcode_val_array as $postcode_id ) {
										$postcodeId           = trim( $postcode_id );
										$notpassed_postcode[] = $postcodeId;
										if ( $postcodeId === $selected_postcode ) {
											$is_passed['has_dpad_based_on_postcode'] = 'no';
										}
									}
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_postcode'] ) && empty( $passed_postcode ) ) {
							$is_passed['has_dpad_based_on_postcode'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_postcode'] ) && ! empty( $passed_postcode ) ) {
							$is_passed['has_dpad_based_on_postcode'] = 'no';
						}
					}

					//Check if is zone exist
					if ( is_array( $zone_array ) && isset( $zone_array ) && ! empty( $zone_array ) && ! empty( $cart_array ) ) {
						$get_zonelist                        = $this->wc_get_shipping_zone();
						$is_passed['has_dpad_based_on_zone'] = '';
						foreach ( $zone_array as $zone ) {
							if ( $zone['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $zone['product_dpad_conditions_values'] ) ) {
									if ( in_array( $get_zonelist, $zone['product_dpad_conditions_values'],true ) ) {
										$is_passed['has_dpad_based_on_zone'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_zone'] = 'no';
									}
								}
							}
							if ( $zone['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $zone['product_dpad_conditions_values'] ) ) {
									if ( in_array( $get_zonelist, $zone['product_dpad_conditions_values'],true ) ) {
										$is_passed['has_dpad_based_on_zone'] = 'no';
									} else {
										$is_passed['has_dpad_based_on_zone'] = 'yes';
									}
								}
							}
						}
					}


					
					if ( is_array( $product_array ) && isset( $product_array ) && ! empty( $product_array ) && ! empty( $cart_array ) ) {

						$cart_products_array = array();
						$cart_product        = $this->dpad_array_column( $cart_array, 'product_id' );

						if ( isset( $cart_product ) && ! empty( $cart_product ) ) {
							foreach ( $cart_product as $key => $cart_product_id ) {
								if ( ! empty( $sitepress ) ) {
									$cart_products_array[] = apply_filters( 'wpml_object_id', $cart_product_id, 'product', true, $default_lang );
								} else {
									$cart_products_array[] = $cart_product_id;
								}
							}
						}

						$is_passed['has_dpad_based_on_product'] = '';
						$passed_product                         = array();
						foreach ( $product_array as $product ) {
							if ( $product['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $product['product_dpad_conditions_values'] ) ) {
									foreach ( $product['product_dpad_conditions_values'] as $product_id ) {

										$passed_product[] = $product_id;
										if ( in_array( (int)$product_id, convert_array_to_int($cart_products_array), true ) ) {
											$is_passed['has_dpad_based_on_product'] = 'yes';
										}
									}
								}
							}
							if ( $product['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $product['product_dpad_conditions_values'] ) ) {
									foreach ( $product['product_dpad_conditions_values'] as $product_id ) {
										if ( in_array( (int)$product_id, convert_array_to_int($cart_product),true ) ) {
											$is_passed['has_dpad_based_on_product'] = 'no';
										}
									}
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_product'] ) && empty( $passed_product ) ) {
							$is_passed['has_dpad_based_on_product'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_product'] ) && ! empty( $passed_product ) ) {
							$is_passed['has_dpad_based_on_product'] = 'no';
						}
					}

					//Check if is variable product exist
					if ( is_array( $variableproduct_array ) && isset( $variableproduct_array ) && ! empty( $variableproduct_array ) && ! empty( $cart_array ) ) {

						$cart_products_array = array();
						$cart_product        = $this->dpad_array_column( $cart_array, 'variation_id' );
						if ( isset( $cart_product ) && ! empty( $cart_product ) ) {

							foreach ( $cart_product as $key => $cart_product_id ) {

								if ( ! empty( $sitepress ) ) {
									$cart_products_array[] = apply_filters( 'wpml_object_id', $cart_product_id, 'product', true, $default_lang );
								} else {
									$cart_products_array[] = $cart_product_id;
								}
							}
						}
						$is_passed['has_dpad_based_on_product'] = '';
						$passed_product                         = array();
						foreach ( $variableproduct_array as $product ) {
							if ( $product['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $product['product_dpad_conditions_values'] ) ) {
									foreach ( $product['product_dpad_conditions_values'] as $product_id ) {

										$passed_product[] = $product_id;
										if ( in_array( (int)$product_id, convert_array_to_int($cart_products_array),true ) ) {
											$is_passed['has_dpad_based_on_product'] = 'yes';
										}
									}
								}
							}
							if ( $product['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $product['product_dpad_conditions_values'] ) ) {
									foreach ( $product['product_dpad_conditions_values'] as $product_id ) {
										if ( in_array( (int)$product_id, convert_array_to_int($cart_product ),true ) ) {
											$is_passed['has_dpad_based_on_product'] = 'no';
										}
									}
								}
							}
						}

						if ( empty( $is_passed['has_dpad_based_on_product'] ) && empty( $passed_product ) ) {
							$is_passed['has_dpad_based_on_product'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_product'] ) && ! empty( $passed_product ) ) {
							$is_passed['has_dpad_based_on_product'] = 'no';
						}
					}


					//Check if is Category exist
					if ( is_array( $category_array ) && isset( $category_array ) && ! empty( $category_array ) && ! empty( $cart_array ) ) {
						$cart_product           = $this->dpad_array_column( $cart_array, 'product_id' );
						$cart_category_id_array = array();
						$cart_products_array    = array();

						if ( isset( $cart_product ) && ! empty( $cart_product ) ) {
							foreach ( $cart_product as $key => $cart_product_id ) {
								if ( ! empty( $sitepress ) ) {
									$cart_products_array[] = apply_filters( 'wpml_object_id', $cart_product_id, 'product', true, $default_lang );
								} else {
									$cart_products_array[] = $cart_product_id;
								}
							}
						}

						if ( ! empty( $cart_products_array ) ) {
							foreach ( $cart_products_array as $product ) {
								$cart_product_category = wp_get_post_terms( $product, 'product_cat', array( 'fields' => 'ids' ) );
								if ( isset( $cart_product_category ) && ! empty( $cart_product_category ) && is_array( $cart_product_category ) ) {
									$cart_category_id_array[] = $cart_product_category;
								}
							}

							$get_cat_all                             = array_unique( $this->array_flatten( $cart_category_id_array ) );
						

							$is_passed['has_dpad_based_on_category'] = '';
							$passed_category                         = array();
							$notpassed_category                      = array();
							foreach ( $category_array as $category ) {
								if ( $category['product_dpad_conditions_is'] === 'is_equal_to' ) {
									if ( ! empty( $category['product_dpad_conditions_values'] ) ) {
										foreach ( $category['product_dpad_conditions_values'] as $category_id ) {
											$passed_category[] = $category_id;

											if ( in_array( (int)$category_id, convert_array_to_int($get_cat_all),true ) ) {
												$is_passed['has_dpad_based_on_category'] = 'yes';
											}
										}
									}
								}
								if ( $category['product_dpad_conditions_is'] === 'not_in' ) {
									if ( ! empty( $category['product_dpad_conditions_values'] ) ) {
										foreach ( $category['product_dpad_conditions_values'] as $category_id ) {
											$notpassed_category[] = $category_id;
											if ( in_array( (int)$category_id, convert_array_to_int($get_cat_all),true ) ) {
												$is_passed['has_dpad_based_on_category'] = 'no';
											}
										}
									}
								}
							}
							if ( empty( $is_passed['has_dpad_based_on_category'] ) && empty( $passed_category ) ) {
								$is_passed['has_dpad_based_on_category'] = 'yes';
							} elseif ( empty( $is_passed['has_dpad_based_on_category'] ) && ! empty( $passed_category ) ) {
								$is_passed['has_dpad_based_on_category'] = 'no';
							}
						}
					}

					//Check if is tag exist
					if ( is_array( $tag_array ) && isset( $tag_array ) && ! empty( $tag_array ) && ! empty( $cart_array ) ) {
						$cart_product                       = $this->dpad_array_column( $cart_array, 'product_id' );
						$tagid                              = array();
						$is_passed['has_dpad_based_on_tag'] = '';
						$passed_tag                         = array();
						$notpassed_tag                      = array();
						$cart_products_array                = array();

						if ( isset( $cart_product ) && ! empty( $cart_product ) ) {
							foreach ( $cart_product as $key => $cart_product_id ) {
								if ( ! empty( $sitepress ) ) {
									$cart_products_array[] = apply_filters( 'wpml_object_id', $cart_product_id, 'product', true, $default_lang );
								} else {
									$cart_products_array[] = $cart_product_id;
								}
							}
						}

						foreach ( $cart_products_array as $product ) {

							$cart_product_tag = wp_get_post_terms( $product, 'product_tag', array( 'fields' => 'ids' ) );
							if ( isset( $cart_product_tag ) && ! empty( $cart_product_tag ) && is_array( $cart_product_tag ) ) {
								$tagid[] = $cart_product_tag;
							}
						}

						$get_tag_all = array_unique( $this->array_flatten( $tagid ) );
						foreach ( $tag_array as $tag ) {
							if ( $tag['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $tag['product_dpad_conditions_values'] ) ) {
									foreach ( $tag['product_dpad_conditions_values'] as $tag_id ) {
										$passed_tag[] = $tag_id;
										if ( in_array( (int)$tag_id, convert_array_to_int($get_tag_all),true ) ) {
											$is_passed['has_dpad_based_on_tag'] = 'yes';
										}
									}
								}
							}
							if ( $tag['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $tag['product_dpad_conditions_values'] ) ) {
									foreach ( $tag['product_dpad_conditions_values'] as $tag_id ) {
										$notpassed_tag[] = $tag_id;
										if ( in_array( (int)$tag_id, convert_array_to_int($get_tag_all),true ) ) {
											$is_passed['has_dpad_based_on_tag'] = 'no';
										}
									}
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_tag'] ) && empty( $passed_tag ) ) {
							$is_passed['has_dpad_based_on_tag'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_tag'] ) && ! empty( $passed_tag ) ) {
							$is_passed['has_dpad_based_on_tag'] = 'no';
						}
					}

					//Check if is user exist
					if ( is_array( $user_array ) && isset( $user_array ) && ! empty( $user_array ) && ! empty( $cart_array ) && is_user_logged_in()) {
						
						$current_user_id = get_current_user_id();
						$passed_users=[];
						foreach ( $user_array as $user ) {
							$passed_users=array_merge($passed_users,$user['product_dpad_conditions_values']);
							if ( $user['product_dpad_conditions_is'] === 'is_equal_to' ) {
							
								if ( in_array( (int)$current_user_id, convert_array_to_int($user['product_dpad_conditions_values']),true ) ) {
									$is_passed['has_dpad_based_on_user'] = 'yes';
								}
							}
							if ( $user['product_dpad_conditions_is'] === 'not_in' ) {
								if ( in_array( (int)$current_user_id, convert_array_to_int($user['product_dpad_conditions_values']),true ) ) {
									$is_passed['has_dpad_based_on_user'] = 'no';
								} else {
									$is_passed['has_dpad_based_on_user'] = 'yes';
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_user'] ) && empty( $passed_users ) ) {
							$is_passed['has_dpad_based_on_user'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_user'] ) && ! empty( $passed_users ) ) {
							$is_passed['has_dpad_based_on_user'] = 'no';
						}
					}

					//Check if is user role exist
					if ( is_array( $user_role_array ) && !empty($user_role_array) && isset( $user_role_array ) && ! empty( $user_role_array ) && ! empty( $cart_array ) ) {
						$passed_user_role=[];

						/**
						 * check user loggedin or not
						 */
						global $current_user;
						if ( is_user_logged_in() ) {
							$current_user_role = $current_user->roles;
						} else {
							$current_user_role = ['guest'];
						}

						$passed_user_role    = array();
						$notpassed_user_role = array();



						if ( is_array( $current_user_role ) && isset( $current_user_role ) && ! empty( $current_user_role ) && ! empty( $cart_array ) ) {
							foreach ( $current_user_role as $current_user_all_role ) {
								foreach ( $user_role_array as $user_role ) {

									if ( $user_role['product_dpad_conditions_is'] === 'is_equal_to' ) {
										$passed_user_role[]   = $current_user_all_role;

										if ( in_array( $current_user_all_role, $user_role['product_dpad_conditions_values'],true ) ) {
											
											$is_passed['has_dpad_based_on_user_role'] = 'yes';
										}
									}
									if ( $user_role['product_dpad_conditions_is'] === 'not_in' ) {
										$notpassed_user_role[]   = $current_user_all_role;
										
										
										if ( in_array( $current_user_all_role, $user_role['product_dpad_conditions_values'],true ) ) {
											
											$is_passed['has_dpad_based_on_user_role'] = 'no';
										}
									}
								}
							}
						}
					
						if ( empty( $is_passed['has_dpad_based_on_user_role'] ) &&  empty( $passed_user_role ) ) {
							$is_passed['has_dpad_based_on_user_role'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_user_role'] ) && ! empty( $passed_user_role ) ) {
							$is_passed['has_dpad_based_on_user_role'] = 'no';
						}
					}
					//Check if is coupon exist
					if ( is_array( $coupon_array ) && isset( $coupon_array ) && ! empty( $coupon_array ) && ! empty( $cart_array ) ) {
						$all_coupon      = $woocommerce->cart->coupons;
						$cart_coupon     = isset( $all_coupon ) && ! empty( $all_coupon ) ? $all_coupon : array();
						$couponId        = array();
						$wc_curr_version = $this->wcpf_get_woo_version_number();
						if ( ! empty( $cart_coupon ) ) {
							foreach ( $cart_coupon as $cartCoupon ) {
								if ( $cartCoupon->is_valid() && isset( $cartCoupon ) && ! empty( $cartCoupon ) ) {
									if ( $wc_curr_version >= 3.0 ) {
										$couponId[] = $cartCoupon->get_id();
									} else {
										$couponId[] = $cartCoupon->id;
									}
								}
							}
						}
						$is_passed['has_dpad_based_on_coupon'] = '';
						$passed_coupon                         = array();
						$notpassed_coupon                      = array();
						foreach ( $coupon_array as $coupon ) {
							if ( $coupon['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $coupon['product_dpad_conditions_values'] ) ) {
									foreach ( $coupon['product_dpad_conditions_values'] as $coupon_id ) {
										$passed_coupon[] = $coupon_id;
										if ( in_array( (int)$coupon_id, convert_array_to_int($couponId),true ) ) {
											$is_passed['has_dpad_based_on_coupon'] = 'yes';
										}
									}
								}
							}
							if ( $coupon['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $coupon['product_dpad_conditions_values'] ) ) {
									foreach ( $coupon['product_dpad_conditions_values'] as $coupon_id ) {
										$notpassed_coupon[] = $coupon_id;
										if ( in_array( (int)$coupon_id, convert_array_to_int($couponId),true ) ) {
											$is_passed['has_dpad_based_on_coupon'] = 'no';
										}
									}
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_coupon'] ) && empty( $passed_coupon ) ) {
							$is_passed['has_dpad_based_on_coupon'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_coupon'] ) && ! empty( $passed_coupon ) ) {
							$is_passed['has_dpad_based_on_coupon'] = 'no';
						}
					}
					//Check if is Cart Subtotal (Before Discount) exist
					if ( is_array( $cart_total_array ) && isset( $cart_total_array ) && ! empty( $cart_total_array ) && ! empty( $cart_array ) ) {
						$total = $woocommerce->cart->subtotal;
						if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
							$new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
						} else {
							$new_total = $total;
						}

						foreach ( $cart_total_array as $cart_total ) {
							if ( $cart_total['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $cart_total['product_dpad_conditions_values'] ) ) {
									if ( (int)$cart_total['product_dpad_conditions_values'] === (int)$new_total ) {
										$is_passed['has_dpad_based_on_cart_total'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_total'] = 'no';
										break;
									}
								}
							}
							if ( $cart_total['product_dpad_conditions_is'] === 'less_equal_to' ) {
								if ( ! empty( $cart_total['product_dpad_conditions_values'] ) ) {
									if ( $cart_total['product_dpad_conditions_values'] >= $new_total ) {
										$is_passed['has_dpad_based_on_cart_total'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_total'] = 'no';
										break;
									}
								}
							}
							if ( $cart_total['product_dpad_conditions_is'] === 'less_then' ) {
								if ( ! empty( $cart_total['product_dpad_conditions_values'] ) ) {
									if ( $cart_total['product_dpad_conditions_values'] > $new_total ) {
										$is_passed['has_dpad_based_on_cart_total'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_total'] = 'no';
										break;
									}
								}
							}
							if ( $cart_total['product_dpad_conditions_is'] === 'greater_equal_to' ) {
								if ( ! empty( $cart_total['product_dpad_conditions_values'] ) ) {
									if ( $cart_total['product_dpad_conditions_values'] <= $new_total ) {
										$is_passed['has_dpad_based_on_cart_total'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_total'] = 'no';
										break;
									}
								}
							}
							if ( $cart_total['product_dpad_conditions_is'] === 'greater_then' ) {
								if ( ! empty( $cart_total['product_dpad_conditions_values'] ) ) {
									if ( $cart_total['product_dpad_conditions_values'] < $new_total ) {
										$is_passed['has_dpad_based_on_cart_total'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_total'] = 'no';
										break;
									}
								}
							}
							if ( $cart_total['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $cart_total['product_dpad_conditions_values'] ) ) {
									if ( (int)$new_total === (int)$cart_total['product_dpad_conditions_values'] ) {
										$is_passed['has_dpad_based_on_cart_total'] = 'no';
										break;
									} else {
										$is_passed['has_dpad_based_on_cart_total'] = 'yes';
									}
								}
							}
						}
					}

					//Check if is Cart Subtotal (After Discount) exist
					if ( is_array( $cart_totalafter_array ) && isset( $cart_totalafter_array ) && ! empty( $cart_totalafter_array ) && ! empty( $cart_array ) ) {
						$totalprice  = $this->remove_currency( $woocommerce->cart->get_cart_subtotal() );
						$totaldisc   = $this->remove_currency( $woocommerce->cart->get_total_discount() );
						$resultprice = $totalprice - $totaldisc;
						if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
							$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
						} else {
							$new_resultprice = $resultprice;
						}

						foreach ( $cart_totalafter_array as $cart_totalafter ) {
							if ( $cart_totalafter['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $cart_totalafter['product_dpad_conditions_values'] ) ) {
									if ( (int)$cart_totalafter['product_dpad_conditions_values'] === (int)$new_resultprice ) {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'no';
										break;
									}
								}
							}
							if ( $cart_totalafter['product_dpad_conditions_is'] === 'less_equal_to' ) {
								if ( ! empty( $cart_totalafter['product_dpad_conditions_values'] ) ) {
									if ( $cart_totalafter['product_dpad_conditions_values'] >= $new_resultprice ) {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'no';
										break;
									}
								}
							}
							if ( $cart_totalafter['product_dpad_conditions_is'] === 'less_then' ) {
								if ( ! empty( $cart_totalafter['product_dpad_conditions_values'] ) ) {
									if ( $cart_totalafter['product_dpad_conditions_values'] > $new_resultprice ) {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'no';
										break;
									}
								}
							}
							if ( $cart_totalafter['product_dpad_conditions_is'] === 'greater_equal_to' ) {
								if ( ! empty( $cart_totalafter['product_dpad_conditions_values'] ) ) {
									if ( $cart_totalafter['product_dpad_conditions_values'] <= $new_resultprice ) {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'no';
										break;
									}
								}
							}
							if ( $cart_totalafter['product_dpad_conditions_is'] === 'greater_then' ) {
								if ( ! empty( $cart_totalafter['product_dpad_conditions_values'] ) ) {
									if ( $cart_totalafter['product_dpad_conditions_values'] < $new_resultprice ) {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'no';
										break;
									}
								}
							}
							if ( $cart_totalafter['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $cart_totalafter['product_dpad_conditions_values'] ) ) {
									if ( (int)$new_resultprice === (int)$cart_totalafter['product_dpad_conditions_values'] ) {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'no';
										break;
									} else {
										$is_passed['has_dpad_based_on_cart_totalafter'] = 'yes';
									}
								}
							}
						}
					}
					//Check if is quantity exist
					if ( is_array( $quantity_array ) && isset( $quantity_array ) && ! empty( $quantity_array ) && ! empty( $cart_array ) ) {
						$woo_cart_array = array();
						$woo_cart_array = WC()->cart->get_cart();
						$quantity_total = 0;

						foreach ( $woo_cart_array as  $woo_cart_item ) {
							$quantity_total += $woo_cart_item['quantity'];
						}
						foreach ( $quantity_array as $quantity ) {
							if ( $quantity['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $quantity['product_dpad_conditions_values'] ) ) {
									if ( (int)$quantity_total === (int)$quantity['product_dpad_conditions_values'] ) {
										$is_passed['has_dpad_based_on_quantity'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_quantity'] = 'no';
										break;
									}
								}
							}
							if ( $quantity['product_dpad_conditions_is'] === 'less_equal_to' ) {
								if ( ! empty( $quantity['product_dpad_conditions_values'] ) ) {
									if ( $quantity['product_dpad_conditions_values'] >= $quantity_total ) {
										$is_passed['has_dpad_based_on_quantity'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_quantity'] = 'no';
										break;
									}
								}
							}
							if ( $quantity['product_dpad_conditions_is'] === 'less_then' ) {
								if ( ! empty( $quantity['product_dpad_conditions_values'] ) ) {
									if ( $quantity['product_dpad_conditions_values'] > $quantity_total ) {
										$is_passed['has_dpad_based_on_quantity'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_quantity'] = 'no';
										break;
									}
								}
							}
							if ( $quantity['product_dpad_conditions_is'] === 'greater_equal_to' ) {
								if ( ! empty( $quantity['product_dpad_conditions_values'] ) ) {
									if ( $quantity['product_dpad_conditions_values'] <= $quantity_total ) {
										$is_passed['has_dpad_based_on_quantity'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_quantity'] = 'no';
										break;
									}
								}
							}
							if ( $quantity['product_dpad_conditions_is'] === 'greater_then' ) {
								if ( ! empty( $quantity['product_dpad_conditions_values'] ) ) {
									if ( $quantity['product_dpad_conditions_values'] < $quantity_total ) {
										$is_passed['has_dpad_based_on_quantity'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_quantity'] = 'no';
										break;
									}
								}
							}
							if ( $quantity['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $quantity['product_dpad_conditions_values'] ) ) {
									if ( (int)$quantity_total === (int)$quantity['product_dpad_conditions_values'] ) {
										$is_passed['has_dpad_based_on_quantity'] = 'no';
										break;
									} else {
										$is_passed['has_dpad_based_on_quantity'] = 'yes';
									}
								}
							}
						}
					}
					//Check if is weight exist
					if ( is_array( $weight_array ) && isset( $weight_array ) && ! empty( $weight_array ) && ! empty( $cart_array ) ) {
						$woo_cart_array         = array();
						$woo_cart_array         = WC()->cart->get_cart();
						$woo_cart_item_quantity = 0;
						$weight_total           = 0;

						foreach ( $woo_cart_array as  $woo_cart_item ) {
							$product_weight = $woo_cart_item['data']->get_weight();
							if ( $product_weight !== 0 ) {
								$woo_cart_item_quantity = $woo_cart_item['quantity'];
								$weight_total           += floatval( $product_weight ) * intval( $woo_cart_item_quantity );
							}
						}
						foreach ( $weight_array as $weight ) {
							if ( $weight['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $weight['product_dpad_conditions_values'] ) ) {
									if ( (int)$weight_total === (int)$weight['product_dpad_conditions_values'] ) {
										$is_passed['has_dpad_based_on_weight'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_weight'] = 'no';
										break;
									}
								}
							}
							if ( $weight['product_dpad_conditions_is'] === 'less_equal_to' ) {
								if ( ! empty( $weight['product_dpad_conditions_values'] ) ) {
									if ( $weight['product_dpad_conditions_values'] >= $weight_total ) {
										$is_passed['has_dpad_based_on_weight'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_weight'] = 'no';
										break;
									}
								}
							}
							if ( $weight['product_dpad_conditions_is'] === 'less_then' ) {
								if ( ! empty( $weight['product_dpad_conditions_values'] ) ) {
									if ( $weight['product_dpad_conditions_values'] > $weight_total ) {
										$is_passed['has_dpad_based_on_weight'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_weight'] = 'no';
										break;
									}
								}
							}
							if ( $weight['product_dpad_conditions_is'] === 'greater_equal_to' ) {
								if ( ! empty( $weight['product_dpad_conditions_values'] ) ) {
									if ( $weight['product_dpad_conditions_values'] <= $weight_total ) {
										$is_passed['has_dpad_based_on_weight'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_weight'] = 'no';
										break;
									}
								}
							}
							if ( $weight['product_dpad_conditions_is'] === 'greater_then' ) {
								if ( ! empty( $weight['product_dpad_conditions_values'] ) ) {
									if ( $weight['product_dpad_conditions_values'] < $weight_total ) {
										$is_passed['has_dpad_based_on_weight'] = 'yes';
									} else {
										$is_passed['has_dpad_based_on_weight'] = 'no';
										break;
									}
								}
							}
							if ( $weight['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $weight['product_dpad_conditions_values'] ) ) {
									if ( (int)$weight_total === (int)$weight['product_dpad_conditions_values'] ) {
										$is_passed['has_dpad_based_on_weight'] = 'no';
										break;
									} else {
										$is_passed['has_dpad_based_on_weight'] = 'yes';
									}
								}
							}
						}
					}
					//Check if is shipping class exist
					if ( is_array( $shipping_class_array ) && isset( $shipping_class_array ) && ! empty( $shipping_class_array ) && ! empty( $cart_array ) ) {
						$_shippingclass           = array();
						$passed_shipping_class    = array();
						$notpassed_shipping_class = array();
						foreach ( $woocommerce->cart->get_cart() as  $values ) {
							$_product     = $values['product_id'];
							$product      = wc_get_product( $_product );
							if ( $product->is_type( 'variable' ) ) {
								$_product = $values['variation_id'];
							} else {
								$_product = $values['product_id'];
							}
							$terms = get_the_terms( $_product, 'product_shipping_class' );
							if ( $terms ) {
								foreach ( $terms as $term ) {
									if ( ! empty( $sitepress ) ) {
										$_shippingclass[] = apply_filters( 'wpml_object_id', $term->term_id, 'product_shipping_class', true, $default_lang );
									} else {
										$_shippingclass[] = $term->term_id;
									}
								}
							}
						}
						$get_shipping_class_all = array_unique( $this->array_flatten( $_shippingclass ) );
						foreach ( $shipping_class_array as $shipping_class ) {
							if ( $shipping_class['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( ! empty( $shipping_class['product_dpad_conditions_values'] ) ) {
									foreach ( $shipping_class['product_dpad_conditions_values'] as $shipping_class_id ) {
										$passed_shipping_class[] = $shipping_class_id;
										if ( in_array( (int)$shipping_class_id, convert_array_to_int($get_shipping_class_all),true ) ) {
											$is_passed['has_dpad_based_on_shipping_class'] = 'yes';
										}
									}
								}
							}
							if ( $shipping_class['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! empty( $shipping_class['product_dpad_conditions_values'] ) ) {
									foreach ( $shipping_class['product_dpad_conditions_values'] as $shipping_class_id ) {
										$notpassed_shipping_class[] = $shipping_class_id;
										if ( in_array( (int)$shipping_class_id, convert_array_to_int($get_shipping_class_all),true ) ) {
											$is_passed['has_dpad_based_on_shipping_class'] = 'no';
										}
									}
								}
							}
						}
						if ( empty( $is_passed['has_dpad_based_on_shipping_class'] ) && empty( $passed_shipping_class ) ) {
							$is_passed['has_dpad_based_on_shipping_class'] = 'yes';
						} elseif ( empty( $is_passed['has_dpad_based_on_shipping_class'] ) && ! empty( $passed_shipping_class ) ) {
							$is_passed['has_dpad_based_on_shipping_class'] = 'no';
						}
					}

					//Check if is payment gateway exist
					if ( is_array( $payment_gateway ) && isset( $payment_gateway ) && ! empty( $payment_gateway ) && ! empty( $cart_array ) ) {

						$is_passed['has_dpad_based_on_payment'] = '';
						$chosen_payment_method                  = $woocommerce->session->chosen_payment_method;

						foreach ( $payment_gateway as $payment ) {
							if ( $payment['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( in_array( $chosen_payment_method, $payment['product_dpad_conditions_values'],true ) ) {
									$is_passed['has_dpad_based_on_payment'] = 'yes';
								} else {
									$is_passed['has_dpad_based_on_payment'] = 'no';
								}
							}
							if ( $payment['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! in_array( $chosen_payment_method, $payment['product_dpad_conditions_values'],true ) ) {
									$is_passed['has_dpad_based_on_payment'] = 'yes';
								} else {
									$is_passed['has_dpad_based_on_payment'] = 'no';
								}
							}
						}
					}

					//Check if is shipping method exist
					if ( is_array( $shipping_methods ) && isset( $shipping_methods ) && ! empty( $shipping_methods ) && ! empty( $cart_array ) ) {
						$is_passed['has_dpad_based_on_shipping_method'] = '';
						$chosen_shipping_methods                        = $woocommerce->session->chosen_shipping_methods[0];
						$chosen_shipping_methods_explode                = explode( ':', $chosen_shipping_methods );
						foreach ( $shipping_methods as $method ) {
							if ( $method['product_dpad_conditions_is'] === 'is_equal_to' ) {
								if ( in_array( $chosen_shipping_methods_explode[0], $method['product_dpad_conditions_values'],true ) ) {
									$is_passed['has_dpad_based_on_shipping_method'] = 'yes';
								} else {
									$is_passed['has_dpad_based_on_shipping_method'] = 'no';
								}
							}
							if ( $method['product_dpad_conditions_is'] === 'not_in' ) {
								if ( ! in_array( $chosen_shipping_methods_explode[0], $method['product_dpad_conditions_values'],true ) ) {
									$is_passed['has_dpad_based_on_shipping_method'] = 'yes';
								} else {
									$is_passed['has_dpad_based_on_shipping_method'] = 'no';
								}
							}
						}
					}
				}
				if ( isset( $is_passed ) && ! empty( $is_passed ) && is_array( $is_passed ) ) {
					if ( ! in_array( 'no', $is_passed,true ) ) {

						$texable      = ( isset( $getFeetaxable ) && ! empty( $getFeetaxable ) && $getFeetaxable === 'yes' ) ? true : false;
						$currentDate  = strtotime( date( 'd-m-Y' ) );
						$feeStartDate = isset( $getFeeStartDate ) && $getFeeStartDate !== '' ? strtotime( $getFeeStartDate ) : '';
						$feeEndDate   = isset( $getFeeEndDate ) && $getFeeEndDate !== '' ? strtotime( $getFeeEndDate ) : '';
						if ( ( $currentDate >= $feeStartDate || $feeStartDate === '' ) && ( $currentDate <= $feeEndDate || $feeEndDate === '' ) ) {
							$woocommerce->cart->add_fee( $title, ( - 1 * $dpad_cost ), $texable, '' ); //'Reduced rate',
							$ij++;
						}
					}
				}
			}
		}
	}

	/**
	 * Find a matching zone for a given package.
	 *
	 * @since  2.6.0
	 * @uses   wc_make_numeric_postcode()
	 * @return WC_Shipping_Zone
	 */
    public function wc_get_shipping_zone()
    {
        global $wpdb, $woocommerce;

        $country = strtoupper(wc_clean($woocommerce->customer->get_shipping_country()));
        $state = strtoupper(wc_clean($woocommerce->customer->get_shipping_state()));
        $continent = strtoupper(wc_clean(WC()->countries->get_continent_code_for_country($country)));
        $postcode = wc_normalize_postcode(wc_clean($woocommerce->customer->get_shipping_postcode()));
        $cache_key = WC_Cache_Helper::get_cache_prefix('shipping_zones') . 'wc_shipping_zone_' . md5(sprintf('%s+%s+%s', $country, $state, $postcode));
        $matching_zone_id = wp_cache_get($cache_key, 'shipping_zones');

        if (false === $matching_zone_id) {


            // Postcode range and wildcard matching
            $postcode_locations=array();
            $zones = WC_Shipping_Zones::get_zones();
            if(!empty($zones)){
                foreach ($zones as  $zone) {
                    if(!empty($zone['zone_locations'])){
                        foreach ($zone['zone_locations'] as $zone_location) {
                            $location=new stdClass();
                            if('postcode' === $zone_location->type){
                                $location->zone_id=$zone['zone_id'];
                                $location->location_code=$zone_location->code;
                                $postcode_locations[]= $location;   
                            }                        
                        }
                    }
                }                    
            }

            if ($postcode_locations) {
                $zone_ids_with_postcode_rules = array_map('absint', wp_list_pluck($postcode_locations, 'zone_id'));
                $matches = wc_postcode_location_matcher($postcode, $postcode_locations, 'zone_id', 'location_code', $country);
                $do_not_match = array_unique(array_diff($zone_ids_with_postcode_rules, array_keys($matches)));

                if (!empty($do_not_match)) {
                    $criteria =$do_not_match;
                }
            }
            // Get matching zones
            if(!empty($criteria)){
                $matching_zone_id = $wpdb->get_var($wpdb->prepare("
                    SELECT zones.zone_id FROM {$wpdb->prefix}woocommerce_shipping_zones as zones
                    LEFT OUTER JOIN {$wpdb->prefix}woocommerce_shipping_zone_locations as locations ON zones.zone_id = locations.zone_id AND location_type != 'postcode'
                    WHERE ( ( location_type = 'country' AND location_code = %s )
                    OR ( location_type = 'state' AND location_code = %s )
                    OR ( location_type = 'continent' AND location_code = %s )
                    OR ( location_type IS NULL ) )
                    AND zones.zone_id NOT IN (%s)
                    ORDER BY zone_order ASC LIMIT 1
                ",$country,$country . ':' . $state,$continent,implode(',', $do_not_match)));                
            } else {
                $matching_zone_id = $wpdb->get_var($wpdb->prepare("
                    SELECT zones.zone_id FROM {$wpdb->prefix}woocommerce_shipping_zones as zones
                    LEFT OUTER JOIN {$wpdb->prefix}woocommerce_shipping_zone_locations as locations ON zones.zone_id = locations.zone_id AND location_type != 'postcode'
                    WHERE ( ( location_type = 'country' AND location_code = %s )
                    OR ( location_type = 'state' AND location_code = %s )
                    OR ( location_type = 'continent' AND location_code = %s )
                    OR ( location_type IS NULL ) )
                    ORDER BY zone_order ASC LIMIT 1
                   
                ",$country,$country . ':' . $state,$continent));
            }


            wp_cache_set($cache_key, $matching_zone_id, 'shipping_zones');
        }   

        return $matching_zone_id ? $matching_zone_id : 0;
    }


	public function dpad_array_column( array $input, $columnKey, $indexKey = null ) {
		$array = array();
		foreach ( $input as $value ) {
			if ( ! isset( $value[ $columnKey ] ) ) {

				return false;
			}
			if ( is_null( $indexKey ) ) {
				$array[] = $value[ $columnKey ];
			} else {
				if ( ! isset( $value[ $indexKey ] ) ) {
					
					return false;
				}
				if ( ! is_scalar( $value[ $indexKey ] ) ) {
					
					return false;
				}
				$array[ $value[ $indexKey ] ] = $value[ $columnKey ];
			}
		}

		return $array;
	}

	public function array_flatten( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = array_merge( $result, $this->array_flatten( $value ) );
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}

	function wcpf_get_woo_version_number() {
		// If get_plugins() isn't available, require it
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		// Create the plugins folder and file variables
		$plugin_folder = get_plugins( '/' . 'woocommerce' );
		$plugin_file   = 'woocommerce.php';

		// If the plugin version number is set, return it
		if ( isset( $plugin_folder[ $plugin_file ]['Version'] ) ) {
			return $plugin_folder[ $plugin_file ]['Version'];
		} else {
			return null;
		}
	}

	/*
     * Get WooCommerce version number
     */

	public function remove_currency( $price ) {
		$wc_currency_symbol = get_woocommerce_currency_symbol();
		$new_price          = str_replace( $wc_currency_symbol, '', $price );
		$new_price2         = (double) preg_replace( '/[^.\d]/', '', $new_price );

		return $new_price2;
	}

}
