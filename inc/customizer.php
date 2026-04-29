<?php
/**
 * Front-end theme-mod CSS output.
 *
 * The theme settings UI now lives in Enhanced -> Settings, so the old
 * Customizer control registration was removed to avoid loading unused code.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_customizer_css() {
	$accent  = sanitize_hex_color( get_theme_mod( 'enhanced_accent_color', '#cc2222' ) );
	$util_bg = sanitize_hex_color( get_theme_mod( 'enhanced_utility_bg', '#111111' ) );
	$foot_bg = sanitize_hex_color( get_theme_mod( 'enhanced_footer_bg', '#1a1a1a' ) );

	if ( ! $accent ) {
		$accent = '#cc2222';
	}

	if ( ! $util_bg ) {
		$util_bg = '#111111';
	}

	if ( ! $foot_bg ) {
		$foot_bg = '#1a1a1a';
	}
	?>
	<style id="enhanced-custom-css">
		:root {
			--en-accent: <?php echo esc_attr( $accent ); ?>;
			--en-util-bg: <?php echo esc_attr( $util_bg ); ?>;
			--en-footer-bg: <?php echo esc_attr( $foot_bg ); ?>;
			--lx-sale: <?php echo esc_attr( $accent ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'enhanced_customizer_css' );
