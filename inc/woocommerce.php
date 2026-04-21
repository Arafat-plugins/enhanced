<?php
/**
 * WooCommerce integration.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_woocommerce_setup_hooks() {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
}
add_action( 'wp', 'enhanced_woocommerce_setup_hooks' );

// Remove default WooCommerce wrappers. Archive and single-product templates provide custom structure.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_filter( 'loop_shop_columns', function() {
	return 3;
} );

add_filter( 'loop_shop_per_page', function() {
	return 12;
} );

add_filter( 'woocommerce_show_page_title', '__return_false' );
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

function enhanced_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'enhanced_related_products_args' );

function enhanced_product_tabs( $tabs ) {
	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['priority'] = 5;
	}

	if ( isset( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['priority'] = 10;
	}

	if ( isset( $tabs['reviews'] ) ) {
		$tabs['reviews']['priority'] = 15;
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'enhanced_product_tabs', 20 );
