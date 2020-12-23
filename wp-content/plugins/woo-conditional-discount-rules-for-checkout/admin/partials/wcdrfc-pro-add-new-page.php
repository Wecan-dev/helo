<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
$submitFee = filter_input( INPUT_POST, 'submitFee', FILTER_SANITIZE_STRING );

if ( isset( $submitFee ) && !empty($submitFee) ) {
    $post_data = filter_input_array( INPUT_POST, array(
        'post_type'                        => FILTER_SANITIZE_STRING,
        'dpad_post_id'                     => FILTER_SANITIZE_STRING,
        'dpad_settings_product_dpad_title' => FILTER_SANITIZE_STRING,
        'dpad_settings_select_dpad_type'   => FILTER_SANITIZE_STRING,
        'dpad_settings_product_cost'       => FILTER_SANITIZE_STRING,
        'dpad_chk_qty_price'               => FILTER_SANITIZE_STRING,
        'dpad_per_qty'                     => FILTER_SANITIZE_STRING,
        'extra_product_cost'               => FILTER_SANITIZE_STRING,
        'dpad_settings_start_date'         => FILTER_SANITIZE_STRING,
        'dpad_settings_end_date'           => FILTER_SANITIZE_STRING,
        'dpad_settings_status'             => FILTER_SANITIZE_STRING,
        'total_row'                        => FILTER_SANITIZE_STRING,
        'submitFee'                        => FILTER_SANITIZE_STRING,
    ) );
    $post_data['dpad'] = filter_input(
        INPUT_POST,
        'dpad',
        FILTER_SANITIZE_STRING,
        FILTER_REQUIRE_ARRAY
    );
    $post_data['condition_key'] = filter_input(
        INPUT_POST,
        'condition_key',
        FILTER_SANITIZE_STRING,
        FILTER_REQUIRE_ARRAY
    );
    $this->wdpad_pro_dpad_conditions_save( $post_data );
}

$paction = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
$paction_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

if ( isset( $paction ) && $paction === 'edit' ) {
    $btnValue = __( 'Update', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
    $dpad_title = __( get_the_title( $paction_id ), WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
    $getFeesCost = __( get_post_meta( $paction_id, 'dpad_settings_product_cost', true ), WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
    $getFeesPerQtyFlag = get_post_meta( $paction_id, 'dpad_chk_qty_price', true );
    $getFeesPerQty = get_post_meta( $paction_id, 'dpad_per_qty', true );
    $extraProductCost = get_post_meta( $paction_id, 'extra_product_cost', true );
    $getFeesType = __( get_post_meta( $paction_id, 'dpad_settings_select_dpad_type', true ), WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
    $getFeesStartDate = get_post_meta( $paction_id, 'dpad_settings_start_date', true );
    $getFeesEndDate = get_post_meta( $paction_id, 'dpad_settings_end_date', true );
    $getFeesStatus = get_post_meta( $paction_id, 'dpad_settings_status', true );
    $productFeesArray = get_post_meta( $paction_id, 'dynamic_pricing_metabox', true );
    $getFeesOptional = __( get_post_meta( $paction_id, 'dpad_settings_optional_gift', true ), WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
    $byDefaultChecked = get_post_meta( $paction_id, 'by_default_checkbox_checked', true );
} else {
    $paction_id = '';
    $btnValue = __( 'Submit', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
    $dpad_title = '';
    $getFeesCost = '';
    $getFeesPerQtyFlag = '';
    $getFeesPerQty = '';
    $extraProductCost = '';
    $getFeesType = '';
    $getFeesStartDate = '';
    $getFeesEndDate = '';
    $getFeesStatus = '';
    $productFeesArray = array();
    $getFeesOptional = '';
    $byDefaultChecked = '';
}


if ( $getFeesOptional === 'yes' ) {
    $style_display = 'display:block;';
} else {
    $style_display = 'display:none;';
}

?>
<div class="text-condtion-is" style="display:none;">
	<select class="text-condition">
		<option value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
		<option value="less_equal_to"><?php 
esc_html_e( 'Less or Equal to ( <= )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
		<option value="less_then"><?php 
esc_html_e( 'Less than ( < )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
		<option value="greater_equal_to"><?php 
esc_html_e( 'Greater or Equal to ( >= )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
		<option value="greater_then"><?php 
esc_html_e( 'Greater than ( > )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
		<option value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
	</select>
	<select class="select-condition">
		<option value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
		<option value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
	</select>
</div>
<div class="default-country-box" style="display:none;">
	<?php 
echo  wp_kses( $this->wdpad_pro_get_country_list(), allowed_html_tags() ) ;
?>
</div>
<div class="wdpad-main-table res-cl">
	<h2><?php 
esc_html_e( 'Fee Configuration', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></h2>
	<form method="POST" name="dpadfrm" action="">
		<input type="hidden" name="post_type" value="wc_dynamic_pricing">
		<input type="hidden" name="dpad_post_id" value="<?php 
echo  esc_attr( $paction_id ) ;
?>">
		<table class="form-table table-outer product-fee-table">
			<tbody>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="dpad_settings_product_dpad_title"><?php 
esc_html_e( 'Discount Rule Title For Checkout', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?>
						<span class="required-star">*</span></label></th>
				<td class="forminp">
					<input type="text" name="dpad_settings_product_dpad_title" class="text-class" id="dpad_settings_product_dpad_title"
					       value="<?php 
echo  ( isset( $dpad_title ) ? esc_attr( $dpad_title ) : '' ) ;
?>" required="1"
					       placeholder="<?php 
esc_html_e( 'Enter product discount title', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?>">
					<span class="woocommerce_conditional_product_dpad_checkout_tab_descirtion"></span>
					<p class="description"
					   style="display:none;"><?php 
esc_html_e( 'This discount rule title is visible to the customer at the time of checkout.', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></p>
				</td>

			</tr>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="dpad_settings_select_dpad_type"><?php 
esc_html_e( 'Select Discount Type', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></label>
				</th>
				<td class="forminp">
					<select name="dpad_settings_select_dpad_type" id="dpad_settings_select_dpad_type" class="">
						<option value="fixed" <?php 
echo  ( isset( $getFeesType ) && $getFeesType === 'fixed' ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'Fixed', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
						<option value="percentage" <?php 
echo  ( isset( $getFeesType ) && $getFeesType === 'percentage' ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'Percentage', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></option>
					</select>
					<span class="woocommerce_conditional_product_dpad_checkout_tab_descirtion"></span>
					<p class="description"
					   style="display:none;"><?php 
esc_html_e( 'You can give discount on fixed price or percentage based.', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></p>
				</td>
			</tr>
			<tr valign="top">
				<th class="titledesc" scope="row"><label
						for="dpad_settings_product_cost"><?php 
esc_html_e( 'Discount value', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?>
						<span
							class="required-star">*</span></label></th>
				<td class="forminp">
					<div class="product_cost_left_div">
						<input type="text" id="dpad_settings_product_cost" name="dpad_settings_product_cost" required="1" class="text-class" id="dpad_settings_product_cost"
						       value="<?php 
echo  ( isset( $getFeesCost ) ? esc_attr( $getFeesCost ) : '' ) ;
?>" placeholder="<?php 
echo  esc_attr( get_woocommerce_currency_symbol() ) ;
?>">
						<span class="woocommerce_conditional_product_dpad_checkout_tab_descirtion"></span>
						<p class="description" style="display:none;">
							<?php 
esc_html_e( 'If you select fixed discount type then : you have add fixed discount value. (Eg. 10, 20)' );
echo  '<br/>' ;
esc_html_e( 'If you select percentage based discount type then : you have add percentage of discount (Eg. 10, 15.20)', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?>
						</p>
					</div>
					<?php 
?>
				</td>
			</tr>
			<tr valign="top">
				<th class="titledesc" scope="row"><label
						for="dpad_settings_start_date"><?php 
esc_html_e( 'Start Date', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></label>
				</th>
				<td class="forminp">
					<input type="text" name="dpad_settings_start_date" class="text-class" id="dpad_settings_start_date"
					       value="<?php 
echo  ( isset( $getFeesStartDate ) ? esc_attr( $getFeesStartDate ) : '' ) ;
?>"
					       placeholder="<?php 
esc_html_e( 'Select start date', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?>">
					<span class="woocommerce_conditional_product_dpad_checkout_tab_descirtion"></span>
					<p class="description"
					   style="display:none;"><?php 
esc_html_e( 'Select start date which date product discount rules will enable on website.', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></p>
				</td>
			</tr>
			<tr valign="top">
				<th class="titledesc" scope="row"><label
						for="dpad_settings_end_date"><?php 
esc_html_e( 'End Date', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></label>
				</th>
				<td class="forminp">
					<input type="text" name="dpad_settings_end_date" class="text-class" id="dpad_settings_end_date"
					       value="<?php 
echo  ( isset( $getFeesEndDate ) ? esc_attr( $getFeesEndDate ) : '' ) ;
?>"
					       placeholder="<?php 
esc_html_e( 'Select end date', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?>">
					<span class="woocommerce_conditional_product_dpad_checkout_tab_descirtion"></span>
					<p class="description"
					   style="display:none;"><?php 
esc_html_e( 'Select ending date which date product discount rules will disable on website', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></p>
				</td>
			</tr>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="onoffswitch"><?php 
esc_html_e( 'Status', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></label>
				</th>
				<td class="forminp">
					<label class="switch">
						<input type="checkbox" name="dpad_settings_status" value="on" <?php 
echo  ( isset( $getFeesStatus ) && $getFeesStatus === 'off' ? '' : 'checked' ) ;
?>>
						<div class="slider round"></div>
					</label>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="sub-title">
			<h2><?php 
esc_html_e( 'Discount Rules for checkout', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></h2>
			<div class="tap"><a id="fee-add-field" class="button button-primary button-large"
			                    href="javascript:;"><?php 
esc_html_e( '+ Add Row', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></a>
			</div>
		</div>
		<div class="tap">
			<table id="tbl-product-fee" class="tbl_product_fee table-outer tap-cas form-table product-fee-table">
				<tbody>
				<?php 

if ( isset( $productFeesArray ) && !empty($productFeesArray) ) {
    $i = 2;
    foreach ( $productFeesArray as $key => $productdpad ) {
        $dpad_conditions = ( isset( $productdpad['product_dpad_conditions_condition'] ) ? $productdpad['product_dpad_conditions_condition'] : '' );
        $condition_is = ( isset( $productdpad['product_dpad_conditions_is'] ) ? $productdpad['product_dpad_conditions_is'] : '' );
        $condtion_value = ( isset( $productdpad['product_dpad_conditions_values'] ) ? $productdpad['product_dpad_conditions_values'] : array() );
        ?>
							<tr id="row_<?php 
        echo  esc_attr( $i ) ;
        ?>" valign="top">
								<th class="titledesc th_product_dpad_conditions_condition" scope="row">
									<select rel-id="<?php 
        echo  esc_attr( $i ) ;
        ?>" id="product_dpad_conditions_condition_<?php 
        echo  esc_attr( $i ) ;
        ?>" name="dpad[product_dpad_conditions_condition][]"
									        id="product_dpad_conditions_condition" class="product_dpad_conditions_condition">
										<optgroup label="<?php 
        esc_html_e( 'Location Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
											<option value="country" <?php 
        echo  ( $dpad_conditions === 'country' ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Country', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
										</optgroup>
										<optgroup label="<?php 
        esc_html_e( 'Product Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
											<option value="product" <?php 
        echo  ( $dpad_conditions === 'product' ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Product', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
											<option value="category" <?php 
        echo  ( $dpad_conditions === 'category' ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Category', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
										</optgroup>
										<optgroup label="<?php 
        esc_html_e( 'User Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
											<option value="user" <?php 
        echo  ( $dpad_conditions === 'user' ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'User', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
										</optgroup>
										<optgroup label="<?php 
        esc_html_e( 'Cart Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
											<?php 
        $currency_symbol = get_woocommerce_currency_symbol();
        $currency_symbol = ( !empty($currency_symbol) ? '(' . $currency_symbol . ')' : '' );
        ?>
											<option value="cart_total" <?php 
        echo  ( $dpad_conditions === 'cart_total' ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart Subtotal', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
											<option value="quantity" <?php 
        echo  ( $dpad_conditions === 'quantity' ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Quantity', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
										</optgroup>
									</select>
								</th>
								<td class="select_condition_for_in_notin">
									<?php 
        
        if ( 'cart_total' === $dpad_conditions || 'cart_totalafter' === $dpad_conditions || 'quantity' === $dpad_conditions || 'weight' === $dpad_conditions ) {
            ?>
										<select name="dpad[product_dpad_conditions_is][]" class="product_dpad_conditions_is_<?php 
            echo  esc_attr( $i ) ;
            ?>">
											<option value="is_equal_to" <?php 
            echo  ( 'is_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
											<option value="less_equal_to" <?php 
            echo  ( 'less_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Less or Equal to ( <= )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
											<option value="less_then" <?php 
            echo  ( 'less_then' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Less than ( < )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
											<option value="greater_equal_to" <?php 
            echo  ( 'greater_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Greater or Equal to ( >= )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
											<option value="greater_then" <?php 
            echo  ( 'greater_then' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Greater than ( > )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
											<option value="not_in" <?php 
            echo  ( 'not_in' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
										</select>
									<?php 
        } else {
            ?>
										<select name="dpad[product_dpad_conditions_is][]" class="product_dpad_conditions_is_<?php 
            echo  esc_attr( $i ) ;
            ?>">
											<option value="is_equal_to" <?php 
            echo  ( 'is_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
											<option value="not_in" <?php 
            echo  ( 'not_in' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
            ?> </option>
										</select>
									<?php 
        }
        
        ?>
								</td>
								<td class="condition-value" id="column_<?php 
        echo  esc_attr( $i ) ;
        ?>">
									<?php 
        $html = '';
        
        if ( 'country' === $dpad_conditions ) {
            $html .= $this->wdpad_pro_get_country_list( $i, $condtion_value );
        } elseif ( 'product' === $dpad_conditions ) {
            $html .= $this->wdpad_pro_get_product_list( $i, $condtion_value, 'edit' );
        } elseif ( 'category' === $dpad_conditions ) {
            $html .= $this->wdpad_pro_get_category_list( $i, $condtion_value );
        } elseif ( 'user' === $dpad_conditions ) {
            $html .= $this->wdpad_pro_get_user_list( $i, $condtion_value );
        } elseif ( 'cart_total' === $dpad_conditions ) {
            $html .= '<input type = "text" name = "dpad[product_dpad_conditions_values][value_' . $i . ']" id = "product_dpad_conditions_values" class = "product_dpad_conditions_values price-class" value = "' . $condtion_value . '">';
        } elseif ( 'quantity' === $dpad_conditions ) {
            $html .= '<input type = "text" name = "dpad[product_dpad_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_dpad_conditions_values" class = "product_dpad_conditions_values qty-class" value = "' . $condtion_value . '">';
        }
        
        echo  wp_kses( $html, allowed_html_tags() ) ;
        ?>
									<input type="hidden" name="condition_key[<?php 
        echo  'value_' . esc_attr( $i ) ;
        ?>]" value="">
								</td>
								<td>
									<a id="fee-delete-field" rel-id="<?php 
        echo  esc_attr( $i ) ;
        ?>" class="delete-row" href="javascript:;" title="Delete"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
						<?php 
        $i++;
    }
    ?>
					<?php 
} else {
    $i = 1;
    ?>
						<tr id="row_1" valign="top">
							<th class="titledesc th_product_dpad_conditions_condition" scope="row">
								<select rel-id="1" id="product_dpad_conditions_condition_1" name="dpad[product_dpad_conditions_condition][]"
								        id="product_dpad_conditions_condition"
								        class="product_dpad_conditions_condition">
									<optgroup label="<?php 
    esc_html_e( 'Location Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
										<option value="country"><?php 
    esc_html_e( 'Country', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
									</optgroup>
									<optgroup label="<?php 
    esc_html_e( 'Product Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
										<option value="product"><?php 
    esc_html_e( 'Product', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
										<option value="category"><?php 
    esc_html_e( 'Category', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
									</optgroup>
									<optgroup label="<?php 
    esc_html_e( 'User Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
										<option value="user"><?php 
    esc_html_e( 'User', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
									</optgroup>
									<optgroup label="<?php 
    esc_html_e( 'Cart Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
										<?php 
    $currency_symbol = get_woocommerce_currency_symbol();
    $currency_symbol = ( !empty($currency_symbol) ? '(' . $currency_symbol . ')' : '' );
    ?>
										<option value="cart_total"><?php 
    esc_html_e( 'Cart Subtotal', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
										<option value="quantity"><?php 
    esc_html_e( 'Quantity', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
									</optgroup>
								</select>
							</td>
							<td class="select_condition_for_in_notin">
								<select name="dpad[product_dpad_conditions_is][]" class="product_dpad_conditions_is product_dpad_conditions_is_1">
									<option value="is_equal_to"><?php 
    esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
									<option value="not_in"><?php 
    esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
								</select>
							</td>
							<td id="column_1" class="condition-value">
								<?php 
    echo  wp_kses( $this->wdpad_pro_get_country_list( 1 ), allowed_html_tags() ) ;
    ?>
								<input type="hidden" name="condition_key[value_1][]" value="">
							</td>
						</tr>
					<?php 
}

?>
				</tbody>
			</table>
			<input type="hidden" name="total_row" id="total_row" value="<?php 
echo  esc_attr( $i ) ;
?>">
		</div>
		<p class="submit">
			<input type="submit" name="submitFee" class="submitFee button button-primary button-large" value="<?php 
echo  esc_attr( $btnValue ) ;
?>">
		</p>
	</form>
</div>
<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php';