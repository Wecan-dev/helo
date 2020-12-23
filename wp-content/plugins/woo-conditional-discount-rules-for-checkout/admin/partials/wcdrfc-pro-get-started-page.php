<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once(plugin_dir_path( __FILE__ ).'header/plugin-header.php' );
global $wpdb;
?>

	<div class="wdpad-main-table res-cl">
		<h2><?php esc_html_e( 'Thanks For Installing WooCommerce Conditional Discount Rules For Checkout', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></h2>
		<table class="table-outer">
			<tbody>
			<tr>
				<td class="fr-2">
					<p class="block gettingstarted"><strong><?php esc_html_e( 'Getting Started', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?> </strong></p>
					<p class="block textgetting">
						<?php esc_html_e( 'Store owners can create Conditional Discount Rules in your WooCommerce store easily. you can set different discount rules as per below:', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
					</p>
					<ul class="block textgetting">
						  <?php echo esc_html( '- Bulk discounts',WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN). '<br>'. 
							esc_html('- Country Discount',WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN). 
							'<br>'.
							esc_html('- Cart Discount',WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN). 
							'<br>'.
							 esc_html('- Special offers',WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN).
							'<br>'.
							 esc_html('- Category Discount',WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN).
							'<br>'.
							esc_html('- User role-based discounts and more.',WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN);
							

							 ?>
					</ul>
					<p class="block textgetting">
						<?php esc_html_e( 'It is a valuable tool for store owners for set dynamic product new price and special discount offer for the customer.', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
					</p>
					<p class="block textgetting">
						<strong><?php esc_html_e( 'Step 1', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
							:</strong> <?php esc_html_e( 'Add Conditional Discount Rules', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
					</p>
					<p class="block textgetting"><?php esc_html_e( 'Add Conditional Discount Rules title, discount value and set Conditional Discount Rules as per your requirement.', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
					</p>
					<span class="gettingstarted">
                        <img style="border: 2px solid #e9e9e9;margin-top: 1%;margin-bottom: 2%;" src="<?php echo esc_url(WDPAD_PRO_PLUGIN_URL) . 'admin/images/Getting_Started_01.png'; ?>">
                    </span>
					<p class="block gettingstarted textgetting">
						<strong><?php esc_html_e( 'Step 2', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
							:</strong> <?php esc_html_e( 'All Conditional Discount Rules display here. As per below screenshot', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
						<span class="gettingstarted">
                            <img style="border: 2px solid #e9e9e9;margin-top: 2%;margin-bottom: 2%;"
                                 src="<?php echo esc_url(WDPAD_PRO_PLUGIN_URL) . 'admin/images/Getting_Started_02.png'; ?>">
                        </span>
					</p>
					<p class="block gettingstarted textgetting">
						<strong><?php esc_html_e( 'Step 3', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
							:</strong> <?php esc_html_e( 'View Conditional Discount Ruless applied on the checkout page as per below.', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
						<span class="gettingstarted">
                            <img style="border: 2px solid #e9e9e9;margin-top: 3%;" src="<?php echo esc_url(WDPAD_PRO_PLUGIN_URL) . 'admin/images/Getting_Started_03.png'; ?>">
                        </span>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
<?php require_once(plugin_dir_path( __FILE__ ).'header/plugin-sidebar.php' ); ?>