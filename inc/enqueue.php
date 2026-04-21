<?php
/**
 * Asset enqueue.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_enqueue() {
	$variation_colors = array();

	if ( enhanced_should_load_woo_assets() && function_exists( 'enhanced_get_color_options' ) ) {
		foreach ( enhanced_get_color_options() as $color_name => $color_value ) {
			$normalized_name                        = sanitize_title( (string) $color_name );
			$variation_colors[ $normalized_name ]   = (string) $color_value;
			$variation_colors[ strtolower( (string) $color_name ) ] = (string) $color_value;
		}
	}

	wp_enqueue_style( 'enhanced-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@300;400;500;600;700&display=swap',
		array(), null
	);

	wp_enqueue_style( 'enhanced-main',
		ENHANCED_URI . 'assets/css/main.css',
		array( 'enhanced-fonts' ),
		ENHANCED_VERSION
	);

	if ( enhanced_should_load_woo_assets() ) {
		wp_enqueue_style( 'enhanced-woo',
			ENHANCED_URI . 'assets/css/woocommerce.css',
			array( 'enhanced-main' ),
			ENHANCED_VERSION
		);
	}

	wp_enqueue_script( 'enhanced-main',
		ENHANCED_URI . 'assets/js/main.js',
		array(), ENHANCED_VERSION, true
	);

	wp_localize_script( 'enhanced-main', 'EnhancedSettings', array(
		'mobileBreakpoint' => 960,
		'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
		'variationColors'  => $variation_colors,
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'enhanced_enqueue' );
