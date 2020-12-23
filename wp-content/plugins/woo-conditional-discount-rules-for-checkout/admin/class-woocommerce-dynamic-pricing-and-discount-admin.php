<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 * @package    Woocommerce_Dynamic_Pricing_And_Discount_Pro
 * @subpackage Woocommerce_Dynamic_Pricing_And_Discount_Pro/admin
 * @author     Multidots <inquiry@multidots.in>
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    1.0.0
     *
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        $menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        
        if ( isset( $menu_page ) && !empty($menu_page) && ($menu_page === 'wcdrfc-rules-list' || $menu_page === 'wcdrfc-rule-add-new' || $menu_page === 'wcdrfc-page-get-started' || $menu_page === 'wcdrfc-page-information' || $menu_page === 'wcdrfc-pro-edit-fee') ) {
            wp_enqueue_style(
                $this->plugin_name . '-choose-css',
                plugin_dir_url( __FILE__ ) . 'css/chosen.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-jquery-ui-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'font-awesome',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-webkit-css',
                plugin_dir_url( __FILE__ ) . 'css/webkit.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'media-css',
                plugin_dir_url( __FILE__ ) . 'css/media.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'select2-min',
                plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
                array(),
                'all'
            );
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        $menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-accordion' );
        
        if ( isset( $menu_page ) && !empty($menu_page) && ($menu_page === 'wcdrfc-rules-list' || $menu_page === 'wcdrfc-rule-add-new' || $menu_page === 'wcdrfc-page-get-started' || $menu_page === 'wcdrfc-page-information' || $menu_page === 'wcdrfc-pro-edit-fee') ) {
            wp_enqueue_script(
                $this->plugin_name . '-tablesorter-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/woocommerce-dynamic-pricing-and-discount-admin.js',
                array(
                'jquery',
                'jquery-ui-dialog',
                'jquery-ui-accordion',
                'jquery-ui-sortable'
            ),
                $this->version,
                false
            );
            wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array(
                'jquery',
                'jquery-ui-dialog',
                'jquery-ui-accordion',
                'jquery-ui-datepicker'
            ) );
            wp_localize_script( $this->plugin_name, 'coditional_vars', array(
                'plugin_url' => plugin_dir_url( __FILE__ ),
            ) );
        }
    
    }
    
    /**
     * Set Active menu
     */
    public function wdpad_pro_active_menu()
    {
        $screen = get_current_screen();
        if ( !empty($screen) && ($screen->id === 'dotstore-plugins_page_wcdrfc-rule-add-new' || $screen->id === 'dotstore-plugins_page_wcdrfc-pro-edit-fee' || $screen->id === 'dotstore-plugins_page_wcdrfc-page-get-started' || $screen->id === 'dotstore-plugins_page_wcdrfc-page-information') ) {
            ?>
			<script type="text/javascript">
              jQuery(document).ready(function ($) {
                $('a[href="admin.php?page=wcdrfc-rules-list"]').parent().addClass('current')
                $('a[href="admin.php?page=wcdrfc-rules-list"]').addClass('current')
              })
			</script>
			<?php 
        }
    }
    
    public function dot_store_menu_conditional_dpad_pro()
    {
        
        if ( wcdrfc_fs()->is__premium_only() && wcdrfc_fs()->can_use_premium_code() ) {
            $plugin_name = WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_PLUGIN_NAME;
        } else {
            $plugin_name = WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_FREE_PLUGIN_NAME;
        }
        
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                __( 'DotStore Plugins', 'woo-conditional-discount-rules-for-checkout' ),
                'null',
                'dots_store',
                array( $this, 'dot_store_menu_page' ),
                WDPAD_PRO_PLUGIN_URL . 'admin/images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            'Get Started',
            'Get Started',
            'manage_options',
            'wcdrfc-page-get-started',
            array( $this, 'wdpad_pro_get_started_page' )
        );
        add_submenu_page(
            'dots_store',
            'Introduction',
            'Introduction',
            'manage_options',
            'wcdrfc-page-information',
            array( $this, 'wdpad_pro_information_page' )
        );
        add_submenu_page(
            'dots_store',
            $plugin_name,
            __( $plugin_name, 'woo-conditional-discount-rules-for-checkout' ),
            'manage_options',
            'wcdrfc-rules-list',
            array( $this, 'wdpad_pro_dpad_list_page' )
        );
        add_submenu_page(
            'dots_store',
            'Add New',
            'Add New',
            'manage_options',
            'wcdrfc-rule-add-new',
            array( $this, 'wdpad_pro_add_new_dpad_page' )
        );
        add_submenu_page(
            'dots_store',
            'Edit Fee',
            'Edit Fee',
            'manage_options',
            'wcdrfc-pro-edit-fee',
            array( $this, 'wdpad_pro_edit_dpad_page' )
        );
    }
    
    public function dot_store_menu_page()
    {
    }
    
    public function wdpad_pro_information_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcdrfc-pro-information-page.php';
    }
    
    public function wdpad_pro_dpad_list_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcdrfc-pro-list-page.php';
    }
    
    public function wdpad_pro_add_new_dpad_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcdrfc-pro-add-new-page.php';
    }
    
    public function wdpad_pro_edit_dpad_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcdrfc-pro-add-new-page.php';
    }
    
    public function wdpad_pro_get_started_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcdrfc-pro-get-started-page.php';
    }
    
    function pro_dpad_settings_get_meta( $value )
    {
        global  $post ;
        $field = get_post_meta( $post->ID, $value, true );
        
        if ( !empty($field) ) {
            return ( is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) ) );
        } else {
            return false;
        }
    
    }
    
    function wdpad_pro_dpad_conditions_save( $post )
    {
        if ( empty($post) ) {
            return false;
        }
        
        if ( isset( $post['post_type'] ) && $post['post_type'] === 'wc_dynamic_pricing' ) {
            
            if ( $post['dpad_post_id'] === '' ) {
                $dpad_post = array(
                    'post_title'  => $post['dpad_settings_product_dpad_title'],
                    'post_status' => 'publish',
                    'post_type'   => 'wc_dynamic_pricing',
                    'post_author' => 1,
                );
                $post_id = wp_insert_post( $dpad_post );
                update_post_meta( $post_id, 'wcpfc-pro-condition_order', '0' );
            } else {
                $dpad_post = array(
                    'ID'          => $post['dpad_post_id'],
                    'post_title'  => $post['dpad_settings_product_dpad_title'],
                    'post_status' => 'publish',
                );
                $post_id = wp_update_post( $dpad_post );
            }
            
            if ( isset( $post['dpad_settings_product_cost'] ) ) {
                update_post_meta( $post_id, 'dpad_settings_product_cost', esc_attr( $post['dpad_settings_product_cost'] ) );
            }
            /* Apply per quantity postmeta start */
            
            if ( isset( $post['dpad_chk_qty_price'] ) ) {
                update_post_meta( $post_id, 'dpad_chk_qty_price', 'on' );
            } else {
                update_post_meta( $post_id, 'dpad_chk_qty_price', 'off' );
            }
            
            if ( isset( $post['dpad_per_qty'] ) ) {
                update_post_meta( $post_id, 'dpad_per_qty', esc_attr( $post['dpad_per_qty'] ) );
            }
            if ( isset( $post['extra_product_cost'] ) ) {
                update_post_meta( $post_id, 'extra_product_cost', esc_attr( $post['extra_product_cost'] ) );
            }
            /* Apply per quantity postmeta end */
            if ( isset( $post['dpad_settings_select_dpad_type'] ) ) {
                update_post_meta( $post_id, 'dpad_settings_select_dpad_type', esc_attr( $post['dpad_settings_select_dpad_type'] ) );
            }
            if ( isset( $post['dpad_settings_start_date'] ) ) {
                update_post_meta( $post_id, 'dpad_settings_start_date', esc_attr( $post['dpad_settings_start_date'] ) );
            }
            if ( isset( $post['dpad_settings_end_date'] ) ) {
                update_post_meta( $post_id, 'dpad_settings_end_date', esc_attr( $post['dpad_settings_end_date'] ) );
            }
            
            if ( isset( $post['dpad_settings_status'] ) ) {
                update_post_meta( $post_id, 'dpad_settings_status', 'on' );
            } else {
                update_post_meta( $post_id, 'dpad_settings_status', 'off' );
            }
            
            if ( isset( $post['dpad_settings_select_taxable'] ) ) {
                update_post_meta( $post_id, 'dpad_settings_select_taxable', esc_attr( $post['dpad_settings_select_taxable'] ) );
            }
            if ( isset( $post['dpad_settings_optional_gift'] ) ) {
                update_post_meta( $post_id, 'dpad_settings_optional_gift', esc_attr( $post['dpad_settings_optional_gift'] ) );
            }
            
            if ( isset( $post['by_default_checkbox_checked'] ) ) {
                update_post_meta( $post_id, 'by_default_checkbox_checked', 'on' );
            } else {
                update_post_meta( $post_id, 'by_default_checkbox_checked', 'off' );
            }
            
            $dpadArray = array();
            $dpad = ( isset( $post['dpad'] ) ? $post['dpad'] : array() );
            $condition_key = ( isset( $post['condition_key'] ) ? $post['condition_key'] : array() );
            $dpad_conditions = $dpad['product_dpad_conditions_condition'];
            $conditions_is = $dpad['product_dpad_conditions_is'];
            $conditions_values = ( isset( $dpad['product_dpad_conditions_values'] ) && !empty($dpad['product_dpad_conditions_values']) ? $dpad['product_dpad_conditions_values'] : array() );
            $size = count( $dpad_conditions );
            foreach ( array_keys( $condition_key ) as $key ) {
                if ( !array_key_exists( $key, $conditions_values ) ) {
                    $conditions_values[$key] = array();
                }
            }
            uksort( $conditions_values, 'strnatcmp' );
            $conditionsValuesArray = [];
            foreach ( $conditions_values as $v ) {
                $conditionsValuesArray[] = $v;
            }
            $dpadArray = [];
            for ( $i = 0 ;  $i < $size ;  $i++ ) {
                $dpadArray[] = array(
                    'product_dpad_conditions_condition' => $dpad_conditions[$i],
                    'product_dpad_conditions_is'        => $conditions_is[$i],
                    'product_dpad_conditions_values'    => $conditionsValuesArray[$i],
                );
            }
            update_post_meta( $post_id, 'dynamic_pricing_metabox', $dpadArray );
            
            if ( is_network_admin() ) {
                $admin_url = admin_url();
            } else {
                $admin_url = network_admin_url();
            }
            
            $admin_urls = $admin_url . 'admin.php?page=wcdrfc-rules-list';
            wp_safe_redirect( $admin_urls );
            exit;
        }
    
    }
    
    /**
     * Product spesifict starts
     */
    function wdpad_pro_product_dpad_conditions_get_meta( $value )
    {
        global  $post ;
        $field = get_post_meta( $post->ID, $value, true );
        
        if ( isset( $field ) && !empty($field) ) {
            return ( is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) ) );
        } else {
            return false;
        }
    
    }
    
    public function wdpad_pro_product_dpad_conditions_values_ajax()
    {
        $condition = filter_input( INPUT_POST, 'condition', FILTER_SANITIZE_STRING );
        $count = filter_input( INPUT_POST, 'count', FILTER_SANITIZE_STRING );
        $condition = ( isset( $condition ) ? $condition : '' );
        $count = ( isset( $count ) ? $count : '' );
        $html = '';
        
        if ( $condition === 'country' ) {
            $html .= wp_json_encode( $this->wdpad_pro_get_country_list( $count, [], true ) );
        } elseif ( $condition === 'product' ) {
            $html .= wp_json_encode( $this->wdpad_pro_get_product_list(
                $count,
                [],
                '',
                true
            ) );
        } elseif ( $condition === 'category' ) {
            $html .= wp_json_encode( $this->wdpad_pro_get_category_list( $count, [], true ) );
        } elseif ( $condition === 'user' ) {
            $html .= wp_json_encode( $this->wdpad_pro_get_user_list( $count, [], true ) );
        } elseif ( $condition === 'cart_total' ) {
            $html .= 'input';
        } elseif ( $condition === 'quantity' ) {
            $html .= 'input';
        }
        
        echo  wp_kses( $html, allowed_html_tags() ) ;
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    
    /**
     * Function for select country list
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string
     */
    public function wdpad_pro_get_country_list( $count = '', $selected = array(), $json = false )
    {
        $countries_obj = new WC_Countries();
        $getCountries = $countries_obj->__get( 'countries' );
        if ( $json ) {
            return $this->convert_array_to_json( $getCountries );
        }
        $html = '<select name="dpad[product_dpad_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values product_discount_select product_discount_select multiselect2 product_fees_conditions_values_country" multiple="multiple">';
        if ( !empty($getCountries) ) {
            foreach ( $getCountries as $code => $country ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $code, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . $code . '" ' . $selectedVal . '>' . $country . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    public function convert_array_to_json( $arr )
    {
        $filter_data = [];
        foreach ( $arr as $key => $value ) {
            $option = [];
            $option['name'] = $value;
            $option['attributes']['value'] = $key;
            $filter_data[] = $option;
        }
        return $filter_data;
    }
    
    /**
     * Function for select product list
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string
     */
    public function wdpad_pro_get_product_list(
        $count = '',
        $selected = array(),
        $action = '',
        $json = false
    )
    {
        global  $sitepress ;
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_default_language();
        }
        $post_in = '';
        
        if ( 'edit' === $action ) {
            $post_in = $selected;
            $posts_per_page = -1;
        } else {
            $post_in = '';
            $posts_per_page = -1;
        }
        
        $product_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'post__in'       => $post_in,
            'posts_per_page' => $posts_per_page,
        );
        $get_all_products = new WP_Query( $product_args );
        $html = '<select id="product-filter-' . $count . '" rel-id="' . $count . '" name="dpad[product_dpad_conditions_values][value_' . $count . '][]" class="product_filter_select2 product_discount_select product_dpad_conditions_values multiselect2" multiple="multiple">';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                
                if ( !empty($sitepress) ) {
                    $new_product_id = apply_filters(
                        'wpml_object_id',
                        $get_all_product->ID,
                        'product',
                        true,
                        $default_lang
                    );
                } else {
                    $new_product_id = $get_all_product->ID;
                }
                
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( $selectedVal !== '' ) {
                    $html .= '<option value="' . $new_product_id . '" ' . $selectedVal . '>' . '#' . $new_product_id . ' - ' . get_the_title( $new_product_id ) . '</option>';
                }
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return [];
        }
        return $html;
    }
    
    /**
     * Function for select cat list
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string
     */
    public function wdpad_pro_get_category_list( $count = '', $selected = array(), $json = false )
    {
        $filter_categories = [];
        global  $sitepress ;
        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_default_language();
        }
        $args = array(
            'post_type'      => 'product',
            'post_status'    => $post_status,
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_categories = get_categories( $args );
        $html = '<select rel-id="' . $count . '" name="dpad[product_dpad_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values product_discount_select product_discount_select multiselect2" multiple="multiple">';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( !empty($sitepress) ) {
                    $new_cat_id = apply_filters(
                        'wpml_object_id',
                        $get_all_category->term_id,
                        'product_cat',
                        true,
                        $default_lang
                    );
                } else {
                    $new_cat_id = $get_all_category->term_id;
                }
                
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                
                if ( $category->parent > 0 ) {
                    $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . '#' . $parent_category->name . '->' . $category->name . '</option>';
                    $filter_categories[$category->term_id] = '#' . $parent_category->name . '->' . $category->name;
                } else {
                    $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . $category->name . '</option>';
                    $filter_categories[$category->term_id] = $category->name;
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->convert_array_to_json( $filter_categories );
        }
        return $html;
    }
    
    /**
     * Function for select user list
     *
     */
    public function wdpad_pro_get_user_list( $count = '', $selected = array(), $json = false )
    {
        $filter_users = [];
        $get_all_users = get_users();
        $html = '<select rel-id="' . $count . '" name="dpad[product_dpad_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values product_discount_select product_discount_select multiselect2" multiple="multiple">';
        if ( isset( $get_all_users ) && !empty($get_all_users) ) {
            foreach ( $get_all_users as $get_all_user ) {
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( (int) $get_all_user->data->ID, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . $get_all_user->data->ID . '" ' . $selectedVal . '>' . $get_all_user->data->user_login . '</option>';
                $filter_users[$get_all_user->data->ID] = $get_all_user->data->user_login;
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->convert_array_to_json( $filter_users );
        }
        return $html;
    }
    
    public function wdpad_pro_wc_multiple_delete_conditional_fee()
    {
        $allVals = filter_input(
            INPUT_POST,
            'allVals',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_ARRAY
        );
        $result = 0;
        if ( !empty($allVals) ) {
            foreach ( $allVals as $val ) {
                wp_delete_post( $val );
                $result = 1;
            }
        }
        echo  esc_html( $result ) ;
        wp_die();
    }
    
    public function multiple_disable_conditional_fee()
    {
        $allVals = filter_input(
            INPUT_POST,
            'allVals',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_ARRAY
        );
        $multiple_disable_enable_conditional_fee = filter_input( INPUT_POST, 'do_action', FILTER_SANITIZE_STRING );
        if ( !empty($allVals) && isset( $multiple_disable_enable_conditional_fee ) ) {
            foreach ( $allVals as $val ) {
                
                if ( $multiple_disable_enable_conditional_fee === 'disable-conditional-fee' ) {
                    update_post_meta( $val, 'dpad_settings_status', 'off' );
                } else {
                    if ( $multiple_disable_enable_conditional_fee === 'enable-conditional-fee' ) {
                        update_post_meta( $val, 'dpad_settings_status', 'on' );
                    }
                }
                
                $result = 1;
            }
        }
        echo  esc_html( $result ) ;
        wp_die();
    }
    
    public function wdpad_pro_wc_multiple_delete_conditional_discount()
    {
        $allVals = filter_input(
            INPUT_POST,
            'allVals',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_ARRAY
        );
        $result = 0;
        if ( !empty($allVals) ) {
            foreach ( $allVals as $val ) {
                update_post_meta( $val, '' );
                $result = 1;
            }
        }
        echo  esc_html( $result ) ;
        wp_die();
    }
    
    public function wdpad_pro_welcome_conditional_dpad_screen_do_activation_redirect()
    {
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_wdpad_pro_mode_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_wdpad_pro_mode_activation_redirect_data' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'wcdrfc-page-get-started',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    public function wdpad_pro_remove_admin_submenus()
    {
        remove_submenu_page( 'dots_store', 'wcdrfc-page-information' );
        remove_submenu_page( 'dots_store', 'wcdrfc-rule-add-new' );
        remove_submenu_page( 'dots_store', 'wcdrfc-pro-edit-fee' );
        remove_submenu_page( 'dots_store', 'wcdrfc-page-get-started' );
    }
    
    public function wdpad_pro_product_dpad_conditions_values_product_ajax()
    {
        $json = true;
        global  $sitepress ;
        $filter_product_list = [];
        $request_value = filter_input( INPUT_GET, 'search', FILTER_SANITIZE_STRING );
        $posts_per_page = 10;
        $offset = -1;
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        $posts_per_page = ( isset( $posts_per_page ) ? sanitize_text_field( $posts_per_page ) : '' );
        $offset = ( isset( $offset ) ? sanitize_text_field( $offset ) : '' );
        $baselang_product_ids = array();
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_default_language();
        }
        function wcpfc_posts_where( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( isset( $search_term ) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'offset'         => $offset,
            's'              => $post_value,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
            'show_posts'     => -1,
        );
        add_filter(
            'posts_where',
            'wcpfc_posts_where',
            10,
            2
        );
        $wp_query = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'wcpfc_posts_where',
            10,
            2
        );
        $get_all_products = $wp_query->posts;
        if ( isset( $get_all_products ) && !empty($get_all_products) ) {
            foreach ( $get_all_products as $get_all_product ) {
                
                if ( !empty($sitepress) ) {
                    $defaultlang_product_id = apply_filters(
                        'wpml_object_id',
                        $get_all_product->ID,
                        'product',
                        true,
                        $default_lang
                    );
                } else {
                    $defaultlang_product_id = $get_all_product->ID;
                }
                
                $baselang_product_ids[] = $defaultlang_product_id;
            }
        }
        $html = '';
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $_product = wc_get_product( $baselang_product_id );
                $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                
                if ( $_product->get_type() === 'simple' ) {
                    
                    if ( $_product->get_type() == 'variable' ) {
                        $vari = "(All variation)";
                    } else {
                        $vari = "";
                    }
                    
                    $filter_product['id'] = $baselang_product_id;
                    $filter_product['text'] = '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . $vari;
                    $filter_product_list['results'][] = $filter_product;
                }
            
            }
        }
        
        if ( $json ) {
            $filter_product_list['pagination'] = "more";
            $filter_product_list['placeholder'] = "Please enter 3 characters";
            echo  wp_json_encode( $filter_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, allowed_html_tags() ) ;
        wp_die();
    }
    
    public function wdpad_pro_product_dpad_conditions_varible_values_product_ajax()
    {
        $json = true;
        global  $sitepress ;
        $post_value = filter_input( INPUT_GET, 'search', FILTER_SANITIZE_STRING );
        $posts_per_page = 10;
        $offset = -1;
        $post_value = ( isset( $post_value ) ? $post_value : '' );
        $posts_per_page = ( isset( $posts_per_page ) ? $posts_per_page : '' );
        $offset = ( isset( $offset ) ? $offset : '' );
        $baselang_product_ids = array();
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_default_language();
        }
        function wcpfc_posts_wheres( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( isset( $search_term ) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_args = array(
            'post_type'        => 'product',
            'posts_per_page'   => -1,
            'offset'           => $offset,
            'search_pro_title' => $post_value,
            'post_status'      => 'publish',
            'orderby'          => 'title',
            'order'            => 'ASC',
        );
        add_filter(
            'posts_where',
            'wcpfc_posts_wheres',
            10,
            2
        );
        $get_all_products = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'wcpfc_posts_wheres',
            10,
            2
        );
        if ( !empty($get_all_products) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( $_product->is_type( 'variable' ) ) {
                    $variations = $_product->get_available_variations();
                    foreach ( $variations as $value ) {
                        
                        if ( !empty($sitepress) ) {
                            $defaultlang_product_id = apply_filters(
                                'wpml_object_id',
                                $value['variation_id'],
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_product_id = $value['variation_id'];
                        }
                        
                        $baselang_product_ids[] = $defaultlang_product_id;
                    }
                }
            
            }
        }
        $html = '';
        $filter_variable_product_list = [];
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                $filter_variable_product['id'] = $baselang_product_id;
                $filter_variable_product['text'] = '#' . $baselang_product_id . ' - ' . str_replace( '&#8211;', '-', get_the_title( $baselang_product_id ) );
                $filter_variable_product_list['results'][] = $filter_variable_product;
            }
        }
        
        if ( $json ) {
            $filter_variable_product_list['pagination'] = "more";
            $filter_variable_product_list['placeholder'] = "Please enter 3 characters";
            echo  wp_json_encode( $filter_variable_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, allowed_html_tags() ) ;
        wp_die();
    }
    
    function wdpad_pro_admin_footer_review()
    {
        echo  sprintf( wp_kses( __( 'If you like <strong>Conditional Discount Rules For WooCommerce Checkout</strong> plugin, please leave us ★★★★★ ratings on <a href="%1$s" target="_blank">DotStore</a>.', 'woo-conditional-discount-rules-for-checkout' ), array(
            'strong' => array(),
            'a'      => array(
            'href'   => array(),
            'target' => 'blank',
        ),
        ) ), esc_url( 'https://wordpress.org/support/plugin/woo-conditional-discount-rules-for-checkout/reviews/#new-post' ) ) ;
    }
    
    function conditional_discount_sorting()
    {
        
        if ( isset( $_POST['listing'], $_POST['sorting_conditional_fee'] ) && wp_verify_nonce( sanitize_text_field( $_POST['sorting_conditional_fee'] ), 'sorting_conditional_fee_action' ) ) {
            $listing = array_map( 'sanitize_text_field', wp_unslash( $_POST['listing'] ) );
            foreach ( $listing as $key => $value ) {
                update_post_meta( $value, 'wcpfc-pro-condition_order', $key );
            }
        }
    
    }

}