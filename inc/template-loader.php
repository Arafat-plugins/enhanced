<?php
/**
 * Theme template loader inspired by Directorist's render pattern.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_get_template_contents( $template, $args = array() ) {
	ob_start();
	enhanced_get_template( $template, $args );
	return ob_get_clean();
}

function enhanced_template_directory() {
	return ENHANCED_DIR . 'templates/';
}

function enhanced_theme_template_directory() {
	return apply_filters( 'enhanced_template_directory', 'enhanced' );
}

function enhanced_template_path( $template_name, $args = array() ) {
	$theme_template = trailingslashit( enhanced_theme_template_directory() ) . "{$template_name}.php";
	$template       = locate_template( $theme_template, false, false );

	if ( ! $template ) {
		$template = enhanced_template_directory() . "{$template_name}.php";
	}

	return apply_filters( 'enhanced_template_file_path', $template, $template_name, $args );
}

function enhanced_get_template( $template, $args = array() ) {
	if ( is_array( $args ) ) {
		extract( $args, EXTR_SKIP );
	}

	$template = apply_filters( 'enhanced_template', $template, $args );
	$file     = enhanced_template_path( $template, $args );

	do_action( 'before_enhanced_template_loaded', $template, $file, $args );

	if ( ! file_exists( $file ) ) {
		return;
	}

	$old_template_data            = isset( $GLOBALS['enhanced_template_data'] ) ? $GLOBALS['enhanced_template_data'] : null;
	$GLOBALS['enhanced_template_data'] = $args;

	include $file;

	$GLOBALS['enhanced_template_data'] = $old_template_data;
}
