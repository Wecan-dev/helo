<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once(plugin_dir_path( __FILE__ ).'header/plugin-header.php' );
if ( is_network_admin() ) {
	$admin_url = admin_url();
} else {
	$admin_url = network_admin_url();
}
$paction=filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
$rid=filter_input(INPUT_GET,'id',FILTER_SANITIZE_STRING);
if ( isset( $paction ) && $paction === 'delete' ) {
	$rid    = $rid;
	$admin_url = esc_url($admin_url) . 'admin.php?page=wcdrfc-rules-list';
	wp_delete_post( $rid );
	wp_redirect( $admin_url );
	exit;
}


$get_alldpad_args = array(
	'post_type'      => 'wc_dynamic_pricing',
	'post_status'    => 'publish',
	'posts_per_page' => - 1,
	'suppress_filters' => false,
	'order' => 'Asc',
	'orderby' => 'meta_value',
    'meta_key' => 'wcpfc-pro-condition_order'
); 
$get_all_dpad     = get_posts( $get_alldpad_args ); // phpcs:ignore

?>
	<div class="wdpad-main-table res-cl">
		<?php	wp_nonce_field('sorting_conditional_fee_action','sorting_conditional_fee'); ?>
		<div class="product_header_title">
			<h2>
				<?php esc_html_e( 'Discount Rules For Checkout', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?>
				<a class="add-new-btn"
				   href="<?php echo esc_url($admin_url) . 'admin.php?page=wcdrfc-rule-add-new'; ?>"><?php esc_html_e( 'Add Discount Rules For Checkout', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></a>
				<?php if(! empty( $get_all_dpad )){ ?>
				<a id="detete-conditional-fee" class="detete-conditional-fee"><?php esc_html_e( 'Delete (Selected)', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></a>
				<a id="disable-conditional-fee" class="add-new-btn disable-enable-conditional-fee"><?php esc_html_e('Disable', 'woocommerce-conditional-product-fees-for-checkout'); ?></a>
                <a id="enable-conditional-fee" class="add-new-btn disable-enable-conditional-fee"><?php esc_html_e('Enable', 'woocommerce-conditional-product-fees-for-checkout'); ?></a>
				<?php } ?>

			</h2>
		</div>
		<table id="conditional-fee-listing" class="table-outer form-table conditional-fee-listing tablesorter">
			<thead>
			<tr class="wdpad-head">
				<th><input type="checkbox" name="check_all" class="condition-check-all"></th>
				<th><?php esc_html_e( 'Name', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></th>
				<th><?php esc_html_e( 'Amount', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></th>
				<th><?php esc_html_e( 'Status', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></th>
				<th><?php esc_html_e( 'Action', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			if ( ! empty( $get_all_dpad ) ) {
				$i = 1;
				foreach ( $get_all_dpad as $dpad ) {
					$rtitle         = get_the_title( $dpad->ID ) ? get_the_title( $dpad->ID ) : 'Fee';
					$get_dpad_type = get_post_meta( $dpad->ID, 'dpad_settings_select_dpad_type', true );
					$get_dpad_type = ( isset( $get_dpad_type ) && ! empty( $get_dpad_type ) ) ? $get_dpad_type : '';
					$getFeesCost   = get_post_meta( $dpad->ID, 'dpad_settings_product_cost', true );
					$getFeesStatus = get_post_meta( $dpad->ID, 'dpad_settings_status', true );
					?>
					<tr>
						<td width="10%">
							<input type="checkbox" name="multiple_delete_fee[]" class="multiple_delete_fee" value="<?php echo esc_attr($dpad->ID) ?>">
							
						</td>
						<td>
							<a href="<?php echo esc_url($admin_url) . 'admin.php?page=wcdrfc-pro-edit-fee&id=' . esc_attr($dpad->ID) . '&action=edit'; ?>"><?php esc_html_e( $rtitle, WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></a>
						</td>
						<td>
							<?php
							if ( $get_dpad_type === 'percentage' ) {
								echo esc_html($getFeesCost) . ' %';
							} else {
								echo  esc_html(get_woocommerce_currency_symbol()) . '&nbsp;' . esc_html($getFeesCost);
							}
							?>
						</td>
						<td>
							<?php echo ( isset( $getFeesStatus ) && $getFeesStatus === 'on' ) ? '<span class="active-status">' . esc_html_e( 'Enabled', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ) . '</span>' : '<span class="inactive-status">' . esc_html_e( 'Disabled', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ) . '</span>'; ?>
						</td>
						<td>
							<a class="fee-action-button button-primary"
							   href="<?php echo esc_url($admin_url) . 'admin.php?page=wcdrfc-pro-edit-fee&id=' . esc_attr($dpad->ID) . '&action=edit'; ?>"><?php esc_html_e( 'Edit', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></a>
							<a class="fee-action-button button-primary"
							   href="<?php echo esc_url($admin_url) . 'admin.php?page=wcdrfc-rules-list&id=' . esc_attr($dpad->ID) . '&action=delete'; ?>"><?php esc_html_e( 'Delete', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN ); ?></a>
						</td>
					</tr>
					<?php
					$i ++;
				}
			}
			?>
			</tbody>
		</table>
	</div>
<?php require_once(plugin_dir_path( __FILE__ ).'header/plugin-sidebar.php' ); ?>