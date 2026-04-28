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
			$normalized_name                         = sanitize_title( (string) $color_name );
			$variation_colors[ $normalized_name ]    = (string) $color_value;
			$variation_colors[ strtolower( (string) $color_name ) ] = (string) $color_value;
		}
	}

	/* ── Fonts ───────────────────────────────────────────────── */
	wp_enqueue_style(
		'enhanced-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);

	/* ── Swiper (carousel library) ───────────────────────────── */
	/* ── Main theme styles ───────────────────────────────────── */
	wp_enqueue_style(
		'enhanced-main',
		ENHANCED_URI . 'assets/css/main.css',
		array( 'enhanced-fonts' ),
		ENHANCED_VERSION
	);

	/* ── WooCommerce styles ──────────────────────────────────── */
	if ( enhanced_should_load_woo_assets() ) {
		wp_enqueue_style(
			'enhanced-woo',
			ENHANCED_URI . 'assets/css/woocommerce.css',
			array( 'enhanced-main' ),
			ENHANCED_VERSION
		);
	}

	/* ── Front page styles ───────────────────────────────────── */
	if ( is_front_page() ) {
		wp_enqueue_style(
			'enhanced-front-page-fonts',
			'https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,300;0,400;0,600;0,700;0,800;0,900;1,400;1,700&family=DM+Sans:wght@300;400;500;600&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'enhanced-front-page',
			ENHANCED_URI . 'assets/css/front-page.css',
			array( 'enhanced-main', 'enhanced-front-page-fonts' ),
			ENHANCED_VERSION
		);
	}

	/* ── LUXINA overrides (header/footer apply site-wide) ────── */
	wp_enqueue_style(
		'enhanced-luxina',
		ENHANCED_URI . 'assets/css/luxina-overrides.css',
		array( 'enhanced-main' ),
		ENHANCED_VERSION
	);

	/* ── Main theme JS ───────────────────────────────────────── */
	wp_enqueue_script(
		'enhanced-main',
		ENHANCED_URI . 'assets/js/main.js',
		array(),
		ENHANCED_VERSION,
		true
	);

	/* ── Front page JS ───────────────────────────────────────── */
	if ( is_front_page() ) {
		wp_enqueue_script(
			'enhanced-front-page',
			ENHANCED_URI . 'assets/js/front-page.js',
			array(),
			ENHANCED_VERSION,
			true
		);
	}

	/* ── Localize ────────────────────────────────────────────── */
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

/**
 * Add preconnect hints for remote font providers used by the theme.
 *
 * @param array  $urls          Existing resource hint URLs.
 * @param string $relation_type Hint relation type.
 * @return array
 */
function enhanced_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = 'https://fonts.googleapis.com';
	$urls[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $urls;
}
add_filter( 'wp_resource_hints', 'enhanced_resource_hints', 10, 2 );
