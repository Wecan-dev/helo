<?php 

add_action( 'wp_enqueue_scripts', 'wi_child_enqueue_styles');
function wi_child_enqueue_styles() {
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_filter( 'woocommerce_sale_flash', 'dinapyme_wc_modificar_texto_oferta' );

function dinapyme_wc_modificar_texto_oferta( $texto ) {
    //cabia el valor del texto original 'Sale!' de WooCommerce por el texto '¡Promoción!'
    return str_replace( __( 'Sale!', 'woocommerce' ), __( 'Compra', 'woocommerce' ), $texto );
}


add_filter('gettext',  'translate_text');
add_filter('ngettext',  'translate_text');
function translate_text($translated) {

	$translated = str_ireplace('Quick',  'Vista',  $translated);
	$translated = str_ireplace('View',  'Rapida',  $translated);
	$translated = str_ireplace('Search',  'Buscar',  $translated);
	$translated = str_ireplace('results for',  '
Resultados de búsqueda de',  $translated);
	$translated = str_ireplace('More',  'Mas',  $translated);
	$translated = str_ireplace('Your cart is empty',  'Tu carrito esta vacio',  $translated);
		
return $translated;

}

add_filter ('woocommerce_order_button_text', function () {
     return 'Finalizar Compra';
});
?>