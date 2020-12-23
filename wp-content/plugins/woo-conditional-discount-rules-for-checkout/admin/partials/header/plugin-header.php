<?php

$plugin_version = WDPAD_PRO_PLUGIN_VERSION;
$plugin_name = WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_FREE_PLUGIN_NAME;
$version_name = WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_FREE_VERSION_NAME;
?>
<div id="dotsstoremain">
	<div class="all-pad">
		<header class="dots-header">
			<div class="dots-logo-main">
				<img src="<?php 
echo  esc_url( WDPAD_PRO_PLUGIN_URL ) . 'admin/images/wc-conditional-product-dpad.png' ;
?>">
			</div>
			<div class="dots-header-right">
				<div class="logo-detail">
					<strong>
						<?php 
esc_html_e( $plugin_name, 'woo-conditional-discount-rules-for-checkout' );
?>
					</strong>
					<span><?php 
esc_html_e( $version_name, 'woo-conditional-discount-rules-for-checkout' );
?>
						<?php 
echo  esc_html( $plugin_version ) ;
?>
					</span>
				</div>

				<div class="button-group">
					<?php 
?>
						<div class="button-dots-left">
                                <span class="support_dotstore_image"><a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-conditional-discount-rules-for-checkout" ) ;
?>">
                                        <img src="<?php 
echo  esc_url( WDPAD_PRO_PLUGIN_URL ) . 'admin/images/upgrade_new.png' ;
?>"></a>
                                </span>
						</div>
						<?php 
?>
					<div class="button-dots">
                        <span class="support_dotstore_image"><a target="_blank" href="https://www.thedotstore.com/support/">
                                <img src="<?php 
echo  esc_url( WDPAD_PRO_PLUGIN_URL ) . 'admin/images/support_new.png' ;
?>"></a>
                        </span>
					</div>
				</div>
			</div>

			<?php 
$menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
$dpad_list = ( isset( $menu_page ) && $menu_page === 'wcdrfc-rules-list' ? 'active' : '' );
$dpad_add = ( isset( $menu_page ) && $menu_page === 'wcdrfc-rule-add-new' ? 'active' : '' );
$dpad_getting_started = ( isset( $menu_page ) && $menu_page === 'wcdrfc-page-get-started' ? 'active' : '' );
$dpad_information = ( isset( $menu_page ) && $menu_page === 'wcdrfc-page-information' ? 'active' : '' );

if ( isset( $menu_page ) && $menu_page === 'wcdrfc-page-information' || isset( $menu_page ) && $menu_page === 'wcdrfc-page-get-started' ) {
    $dpad_about = 'active';
} else {
    $dpad_about = '';
}

$menu_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
if ( !empty($menu_action) ) {
    if ( $menu_action === 'add' || $menu_action === 'edit' ) {
        $dpad_add = 'active';
    }
}

if ( is_network_admin() ) {
    $admin_url = admin_url();
} else {
    $admin_url = network_admin_url();
}

?>
			<div class="dots-menu-main">
				<nav>
					<ul>
						<li>
							<a class="dotstore_plugin <?php 
echo  esc_attr( $dpad_list ) ;
?>"
							   href="<?php 
echo  esc_url( $admin_url ) . 'admin.php?page=wcdrfc-rules-list' ;
?>"><?php 
esc_html_e( 'Discount Rules For Checkout', 'woo-conditional-discount-rules-for-checkout' );
?></a>
						</li>
						<li>
							<a class="dotstore_plugin <?php 
echo  esc_attr( $dpad_add ) ;
?>"
							   href="<?php 
echo  esc_url( $admin_url ) . 'admin.php?page=wcdrfc-rule-add-new' ;
?>"> <?php 
esc_html_e( 'Add Discount Rules For Checkout', 'woo-conditional-discount-rules-for-checkout' );
?></a>
						</li>
						<li>
							<a class="dotstore_plugin <?php 
echo  esc_attr( $dpad_about ) ;
?>"
							   href="<?php 
echo  esc_url( $admin_url ) . 'admin.php?page=wcdrfc-page-get-started' ;
?>"><?php 
esc_html_e( 'About Plugin', 'woo-conditional-discount-rules-for-checkout' );
?></a>
							<ul class="sub-menu">
								<li><a class="dotstore_plugin <?php 
echo  esc_attr( $dpad_getting_started ) ;
?>"
								       href="<?php 
echo  esc_url( $admin_url ) . 'admin.php?page=wcdrfc-page-get-started' ;
?>"><?php 
esc_html_e( 'Getting Started', 'woo-conditional-discount-rules-for-checkout' );
?></a>
								</li>
								<li><a class="dotstore_plugin <?php 
echo  esc_attr( $dpad_information ) ;
?>"
								       href="<?php 
echo  esc_url( $admin_url ) . 'admin.php?page=wcdrfc-page-information' ;
?>"><?php 
esc_html_e( 'Quick info', 'woo-conditional-discount-rules-for-checkout' );
?></a>
								</li>
							</ul>
						</li>
						<li>
							<a class="dotstore_plugin"><?php 
esc_html_e( 'Dotstore', 'woo-conditional-discount-rules-for-checkout' );
?></a>
							<ul class="sub-menu">
								<li><a target="_blank"
								       href="https://www.thedotstore.com/woocommerce-plugins/"><?php 
esc_html_e( 'WooCommerce Plugins', 'woo-conditional-discount-rules-for-checkout' );
?></a>
								</li>
								<li><a target="_blank"
								       href="https://www.thedotstore.com/wordpress-plugins/"><?php 
esc_html_e( 'Wordpress Plugins', 'woo-conditional-discount-rules-for-checkout' );
?></a>
								</li>
								<li><a target="_blank"
								       href="https://www.thedotstore.com/support/"><?php 
esc_html_e( 'Contact Support', 'woo-conditional-discount-rules-for-checkout' );
?></a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</header>