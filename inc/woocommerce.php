<?php
/**
 * WooCommerce integration.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

// Remove default WooCommerce wrappers — we use our own.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// 3 columns on archive.
add_filter( 'loop_shop_columns', function() { return 3; } );

// 12 products per page.
add_filter( 'loop_shop_per_page', function() { return 12; } );

// Remove default breadcrumbs — rendered in template.
add_filter( 'woocommerce_show_page_title', '__return_false' );

// Dequeue WooCommerce's bundled CSS — our woocommerce.css handles everything.
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
