<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$image_url = WDPAD_PRO_PLUGIN_URL . 'admin/images/right_click.png';
?>
<div class="dotstore_plugin_sidebar">

<?php 
$review_url = '';
$plugin_at = '';
$review_url = esc_url( 'https://wordpress.org/plugins/woo-conditional-discount-rules-for-checkout/#reviews' );
$plugin_at = 'WP.org';
?>
    <div class="dotstore-important-link">
        <div class="image_box">
            <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/rate-us.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Rate us', 'size-chart-for-woocommerce' );
?> ">
        </div>
        <div class="content_box">
            <h3><?php 
esc_html_e( 'Like This Plugin?', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></h3>
            <p><?php 
esc_html_e( 'Your Review is very important to us as it helps us to grow more.', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></p>
            <a class="btn_style" href="<?php 
echo  $review_url ;
?>" target="_blank"><?php 
esc_html_e( 'Review Us on ', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
echo  $plugin_at ;
?></a>
        </div>
    </div>
	<div class="dotstore-important-link">
		<h2><span class="dotstore-important-link-title"><?php 
esc_html_e( 'Important link', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></span></h2>
		<div class="video-detail important-link">
			<ul>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="https://docs.thedotstore.com/collection/318-conditional-discount-rules-for-woocommerce-checkout"><?php 
esc_html_e( 'Plugin documentation', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></a>
				</li>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="https://www.thedotstore.com/support/"><?php 
esc_html_e( 'Support platform', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></a>
				</li>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="https://www.thedotstore.com/suggest-a-feature/"><?php 
esc_html_e( 'Suggest A Feature', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></a>
				</li>
				<li>
					<img src="<?php 
echo  esc_url( $image_url ) ;
?>">
					<a target="_blank"
					   href="https://www.thedotstore.com/woocommerce-conditional-discount-rules-for-checkout#tab-change-log"><?php 
esc_html_e( 'Changelog', WOOCOMMERCE_CONDITIONAL_DISCOUNT_FOR_CHECKOUT_PRO_TEXT_DOMAIN );
?></a>
				</li>
			</ul>
		</div>
	</div>

	<!-- html for popular plugin !-->
	<div class="dotstore-important-link">
        <h2>
            <span class="dotstore-important-link-title">
                <?php 
esc_html_e( 'Our Popular plugins', 'size-chart-for-woocommerce' );
?>
            </span>
        </h2>
        <div class="video-detail important-link">
            <ul>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Advanced-Flat-Rate-Shipping-Method.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Advanced Flat Rate Shipping Method', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/flat-rate-shipping-plugin-for-woocommerce/" ) ;
?>">
						<?php 
esc_html_e( 'Advanced Flat Rate Shipping Method', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Conditional-Product-Fees-For-WooCommerce-Checkout.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Conditional Product Fees For WooCommerce Checkout', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/product/woocommerce-extra-fees-plugin/" ) ;
?>">
						<?php 
esc_html_e( 'Conditional Product Fees For WooCommerce Checkout', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Advance-Menu-Manager-For-WordPress.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Advance Menu Manager For WordPress', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/advance-menu-manager-wordpress/" ) ;
?>">
						<?php 
esc_html_e( 'Advance Menu Manager For WordPress', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Enhanced-Ecommerce-Google-Analytics-For-WooCommerce.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Enhanced Ecommerce Google Analytics for WooCommerce', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking" ) ;
?>">
						<?php 
esc_html_e( 'Enhanced Ecommerce Google Analytics for WooCommerce', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/WooCommerce-Blocker-Prevent-Fake-Orders.png' ) ;
?>" alt="<?php 
esc_attr_e( 'WooCommerce Blocker – Prevent Fake Orders', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-anti-fraud" ) ;
?>">
						<?php 
esc_html_e( 'WooCommerce Blocker – Prevent Fake Orders', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="view-button">
            <a class="view_button_dotstore" href="<?php 
echo  esc_url( "http://www.thedotstore.com/plugins/" ) ;
?>"  target="_blank"><?php 
esc_html_e( 'View All', 'size-chart-for-woocommerce' );
?></a>
        </div>
    </div>
	<!-- html end for popular plugin !-->
</div>
</div>

</body>
</html>