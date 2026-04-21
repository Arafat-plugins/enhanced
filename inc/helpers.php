<?php
/**
 * Helper functions.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_is_woo() {
	return class_exists( 'WooCommerce' );
}

function enhanced_get_option( $key, $default = '' ) {
	return get_theme_mod( 'enhanced_' . $key, $default );
}

function enhanced_cart_count() {
	if ( enhanced_is_woo() && function_exists( 'WC' ) && WC()->cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}
	return 0;
}

function enhanced_cart_url() {
	return enhanced_is_woo() ? wc_get_cart_url() : home_url( '/cart/' );
}

function enhanced_account_url() {
	return enhanced_is_woo() ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
}

/**
 * WooCommerce cart fragment for AJAX count update.
 */
function enhanced_cart_fragment( $fragments ) {
	$count = enhanced_cart_count();
	ob_start();
	?>
	<span class="header-cart__count" data-cart-count><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['span.header-cart__count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'enhanced_cart_fragment' );
