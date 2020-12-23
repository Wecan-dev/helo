<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Dynamic_Pricing_And_Discount_Pro
 * @subpackage Woocommerce_Dynamic_Pricing_And_Discount_Pro/includes
 * @author     Multidots <inquiry@multidots.in>
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woocommerce_Dynamic_Pricing_And_Discount_Pro {

	const WDPAD_VERSION = WDPAD_PRO_PLUGIN_VERSION;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'woocommerce-dynamic-pricing-and-discount';
		$this->version     = WDPAD_PRO_PLUGIN_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$prefix = is_network_admin() ? 'network_admin_' : '';
		add_filter( "{$prefix}plugin_action_links_" . WDPAD_PRO_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ), 10, 4 );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_i18n. Defines internationalization functionality.
	 * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-dynamic-pricing-and-discount-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-dynamic-pricing-and-discount-public.php';

		/**
		 * User review admin side notice
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-user-feedback.php';

		$this->loader = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Dynamic_Pricing_And_Discount_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_i18n();

		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'dot_store_menu_conditional_dpad_pro' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'wdpad_pro_active_menu' );
		$this->loader->add_action( 'wp_ajax_wdpad_pro_product_dpad_conditions_values_ajax', $plugin_admin, 'wdpad_pro_product_dpad_conditions_values_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_wdpad_pro_product_dpad_conditions_values_ajax', $plugin_admin, 'wdpad_pro_product_dpad_conditions_values_ajax' );
		

		$this->loader->add_action( 'wp_ajax_wdpad_pro_product_dpad_conditions_values_product_ajax', $plugin_admin, 'wdpad_pro_product_dpad_conditions_values_product_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_wdpad_pro_product_dpad_conditions_values_product_ajax', $plugin_admin, 'wdpad_pro_product_dpad_conditions_values_product_ajax' );

		$this->loader->add_action( 'wp_ajax_wdpad_pro_product_dpad_conditions_varible_values_product_ajax', $plugin_admin, 'wdpad_pro_product_dpad_conditions_varible_values_product_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_wdpad_pro_product_dpad_conditions_varible_values_product_ajax', $plugin_admin, 'wdpad_pro_product_dpad_conditions_varible_values_product_ajax' );
		
		$this->loader->add_action( 'wp_ajax_wdpad_pro_wc_multiple_delete_conditional_fee', $plugin_admin, 'wdpad_pro_wc_multiple_delete_conditional_fee' );


		$this->loader->add_action('wp_ajax_nopriv_wdpad_pro_wc_disable_conditional_fee', $plugin_admin, 'multiple_disable_conditional_fee');
		$this->loader->add_action('wp_ajax_wdpad_pro_wc_disable_conditional_fee', $plugin_admin, 'multiple_disable_conditional_fee');

		$this->loader->add_action('wp_ajax_wdpad_pro_product_discount_conditions_sorting', $plugin_admin, 'conditional_discount_sorting');

		$this->loader->add_action( 'wp_ajax_nopriv_wdpad_pro_wc_multiple_delete_conditional_fee', $plugin_admin, 'wdpad_pro_wc_multiple_delete_conditional_fee' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'wdpad_pro_remove_admin_submenus' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wdpad_pro_welcome_conditional_dpad_screen_do_activation_redirect' );
		$page_menu=filter_input(INPUT_POST,'page',FILTER_SANITIZE_STRING);
		if ( ! empty( $page_menu ) && ( ( $page_menu === 'wcdrfc-rules-list' ) || ( $page_menu === 'wcdrfc-rule-add-new' ) || ( $page_menu === 'wcdrfc-pro-edit-fee' ) ||
		                                   ( $page_menu === 'wcdrfc-page-get-started' ) || ( $page_menu === 'wcdrfc-page-information' ) ) ) {
			$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'wdpad_pro_admin_footer_review' );
		}
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'conditional_dpad_add_to_cart' );
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'woocommerce_locate_template_product_dpad_conditions', 1, 3 );
	}

	/**
	 * Return the plugin action links.  This will only be called if the plugin
	 * is active.
	 *
	 * @since 1.0.0
	 *
	 * @param array $actions associative array of action names to anchor tags
	 *
	 * @return array associative array of plugin action links
	 */
	public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
		$custom_actions = array(
			'configure' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wcdrfc-page-get-started' ), __( 'Settings', $this->plugin_name ) ),
			'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'store.multidots.com/docs/plugin/woocommerce-conditional-discount-rules-for-checkout' ), __( 'Docs', $this->plugin_name ) ),
			'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'store.multidots.com/support' ), __( 'Support', $this->plugin_name ) ),
		);

		// add the links to the front of the actions list
		return array_merge( $custom_actions, $actions );
	}
	

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
