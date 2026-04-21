<?php
/**
 * Theme setup, supports, menus, widget areas.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_setup() {
	load_theme_textdomain( 'enhanced', ENHANCED_DIR . 'languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// WooCommerce.
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 900,
		'product_grid'          => array(
			'default_columns' => 3,
			'min_columns'     => 1,
			'max_columns'     => 4,
			'default_rows'    => 4,
			'min_rows'        => 1,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'enhanced' ),
		'utility' => __( 'Utility Bar Menu', 'enhanced' ),
		'footer'  => __( 'Footer Menu', 'enhanced' ),
	) );

	add_image_size( 'enhanced-card',   560,  700, true );
	add_image_size( 'enhanced-hero',  1600, 700, true );
	add_image_size( 'enhanced-thumb',  120,  150, true );
}
add_action( 'after_setup_theme', 'enhanced_setup' );

function enhanced_widgets_init() {
	$args = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget__title">',
		'after_title'   => '</h4>',
	);

	register_sidebar( array_merge( $args, array(
		'name'        => __( 'Shop Sidebar', 'enhanced' ),
		'id'          => 'shop-sidebar',
		'description' => __( 'Filters shown on shop and category archive pages.', 'enhanced' ),
	) ) );

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array_merge( $args, array(
			'name' => sprintf( __( 'Footer Column %d', 'enhanced' ), $i ),
			'id'   => 'footer-' . $i,
		) ) );
	}
}
add_action( 'widgets_init', 'enhanced_widgets_init' );
