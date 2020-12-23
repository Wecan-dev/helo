<?php

/**
 * Plugin Name: Conditional Discount Rules For WooCommerce Checkout
 * Plugin URI:        https://www.thedotstore.com/
 * Description:       With this plugin, you can create and manage complex fee rules in WooCommerce store without the help of a developer.
 * Version:           2.0.5
 * Author:            theDotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-conditional-discount-rules-for-checkout
 * Domain Path:       /languages
 *
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wcdrfc_fs' ) ) {
    wcdrfc_fs()->set_basename( false, __FILE__ );
    return;
}

add_action( 'deactivated_plugin', 'detect_plugin_deactivation' );
function detect_plugin_deactivation( $plugin )
{
    if ( $plugin == "woocommerce/woocommerce.php" ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
}

function allowed_html_tags( $tags = array() )
{
    $allowed_tags = array(
        'a'        => array(
        'href'  => array(),
        'title' => array(),
        'class' => array(),
    ),
        'ul'       => array(
        'class' => array(),
    ),
        'li'       => array(
        'class' => array(),
    ),
        'div'      => array(
        'class' => array(),
        'id'    => array(),
    ),
        'select'   => array(
        'id'       => array(),
        'name'     => array(),
        'class'    => array(),
        'multiple' => array(),
        'style'    => array(),
    ),
        'input'    => array(
        'id'    => array(),
        'value' => array(),
        'min'   => array(),
        'max'   => array(),
        'name'  => array(),
        'class' => array(),
        'type'  => array(),
    ),
        'textarea' => array(
        'id'    => array(),
        'name'  => array(),
        'class' => array(),
    ),
        'option'   => array(
        'id'       => array(),
        'selected' => array(),
        'name'     => array(),
        'value'    => array(),
    ),
        'br'       => array(),
        'em'       => array(),
        'strong'   => array(),
    );
    if ( !empty($tags) ) {
        foreach ( $tags as $key => $value ) {
            $allowed_tags[$key] = $value;
        }
    }
    return $allowed_tags;
}

add_action( 'plugins_loaded', 'wcdrcp_initialize_plugin' );
$wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
if ( true === $wc_active ) {
    
    if ( !function_exists( 'wcdrfc_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wcdrfc_fs()
        {
            global  $wcdrfc_fs ;
            
            if ( !isset( $wcdrfc_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wcdrfc_fs = fs_dynamic_init( array(
                    'id'               => '3790',
                    'slug'             => 'woocommerce-conditional-discount-rules-for-checkout',
                    'type'             => 'plugin',
                    'public_key'       => 'pk_25ead80d772c8e17b872aa4b62cb8',
                    'is_premium'       => false,
                    'premium_suffix'   => 'Premium',
                    'has_addons'       => false,
                    'has_paid_plans'   => true,
                    'is_org_compliant' => false,
                    'menu'             => array(
                    'slug'       => 'wcdrfc-page-get-started',
                    'first-path' => 'admin.php?page=wcdrfc-page-get-started',
                    'contact'    => false,
                    'support'    => false,
                ),
                    'is_live'          => true,
                ) );
            }
            
            return $wcdrfc_fs;
        }
        
        // Init Freemius.
        wcdrfc_fs();
        wcdrfc_fs()->add_action( 'after_uninstall', 'wcdrfc_fs_uninstall_cleanup' );
        // Signal that SDK was initiated.
        do_action( 'wcdrfc_fs_loaded' );
    }

}
if ( !defined( 'WDPAD_PRO_PLUGIN_VERSION' ) ) {
    define( 'WDPAD_PRO_PLUGIN_VERSION', '2.0.5' );
}
if ( !defined( 'WDPAD_PRO_PLUGIN_URL' ) ) {
    define( 'WDPAD_PRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'WDPAD_PLUGIN_DIR' ) ) {
    define( 'WDPAD_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'WDPAD_PRO_PLUGIN_DIR_PATH' ) ) {
    define( 'WDPAD_PRO_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'WDPAD_PRO_SLUG' ) ) {
    define( 'WDPAD_PRO_SLUG', 'woo-conditional-discount-rules-for-checkout' );
}
if ( !defined( 'WDPAD_PRO_PLUGIN_BASENAME' ) ) {
    define( 'WDPAD_PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-dynamic-pricing-and-discount-activator.php
 */
function activate_woocommerce_conditional_discount_rules_for_checkout_pro()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-activator.php';
    Woocommerce_Dynamic_Pricing_And_Discount_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-dynamic-pricing-and-discount-deactivator.php
 */
function deactivate_woocommerce_conditional_discount_rules_for_checkout_pro()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-deactivator.php';
    Woocommerce_Dynamic_Pricing_And_Discount_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_conditional_discount_rules_for_checkout_pro' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_conditional_discount_rules_for_checkout_pro' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-dynamic-pricing-and-discount.php';
/**
 * The core plugin include constant file for set constant.
 */
require plugin_dir_path( __FILE__ ) . 'includes/constant.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_conditional_discount_rules_for_checkout_pro()
{
    $plugin = new Woocommerce_Dynamic_Pricing_And_Discount_Pro();
    $plugin->run();
}

function wcdrcp_initialize_plugin()
{
    $wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
    
    if ( current_user_can( 'activate_plugins' ) && $wc_active !== true || $wc_active !== true ) {
        add_action( 'admin_notices', 'wcdrcp_plugin_admin_notice' );
    } else {
        run_woocommerce_conditional_discount_rules_for_checkout_pro();
    }

}

function wcdrcp_plugin_admin_notice()
{
    $vpe_plugin = esc_html__( 'Conditional Discount Rules For WooCommerce Checkout ', 'woo-checkout-for-digital-goods' );
    $wc_plugin = esc_html__( 'WooCommerce', 'woo-checkout-for-digital-goods' );
    ?>
    <div class="error">
        <p>
            <?php 
    echo  sprintf( esc_html__( '%1$s requires %2$s to be installed & activated!', 'woocommerce-product-attachment' ), '<strong>' . esc_html( $vpe_plugin ) . '</strong>', '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank"><strong>' . esc_html( $wc_plugin ) . '</strong></a>' ) ;
    ?>
        </p>
    </div>
    <?php 
}

function woocommerce_conditional_discount_rules_for_checkout_pro_path()
{
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
}

if ( !function_exists( 'convert_array_to_int' ) ) {
    function convert_array_to_int( $arr )
    {
        foreach ( $arr as $key => $value ) {
            $arr[$key] = (int) $value;
        }
        return $arr;
    }

}