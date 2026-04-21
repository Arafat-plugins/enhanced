<?php
/**
 * WordPress Customizer settings.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_customizer( $wp_customize ) {

	// ── Panel ─────────────────────────────────────────────────
	$wp_customize->add_panel( 'enhanced_panel', array(
		'title'    => __( 'Enhanced Theme', 'enhanced' ),
		'priority' => 10,
	) );

	// ── Header ────────────────────────────────────────────────
	$wp_customize->add_section( 'enhanced_header', array(
		'title' => __( 'Header', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$controls = array(
		'utility_text'  => array( 'label' => __( 'Utility bar text', 'enhanced' ),  'default' => __( 'Free shipping on orders over $100', 'enhanced' ), 'type' => 'text' ),
		'utility_link'  => array( 'label' => __( 'Utility bar link URL', 'enhanced' ), 'default' => '', 'type' => 'url' ),
	);

	foreach ( $controls as $id => $args ) {
		$wp_customize->add_setting( 'enhanced_' . $id, array( 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_control( 'enhanced_' . $id, array( 'label' => $args['label'], 'section' => 'enhanced_header', 'type' => $args['type'] ) );
	}

	// ── Colors ────────────────────────────────────────────────
	$wp_customize->add_section( 'enhanced_colors', array(
		'title' => __( 'Colors', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$colors = array(
		'accent_color'  => array( 'label' => __( 'Accent / Price color', 'enhanced' ), 'default' => '#e53e3e' ),
		'utility_bg'    => array( 'label' => __( 'Utility bar background', 'enhanced' ), 'default' => '#111111' ),
		'footer_bg'     => array( 'label' => __( 'Footer background', 'enhanced' ), 'default' => '#1a1a1a' ),
	);

	foreach ( $colors as $id => $args ) {
		$wp_customize->add_setting( 'enhanced_' . $id, array( 'default' => $args['default'], 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'enhanced_' . $id, array( 'label' => $args['label'], 'section' => 'enhanced_colors' ) ) );
	}

	// ── Shop ──────────────────────────────────────────────────
	$wp_customize->add_section( 'enhanced_shop', array(
		'title' => __( 'Shop', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$wp_customize->add_setting( 'enhanced_shop_sidebar', array( 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean' ) );
	$wp_customize->add_control( 'enhanced_shop_sidebar', array(
		'label'   => __( 'Show sidebar filters on shop page', 'enhanced' ),
		'section' => 'enhanced_shop',
		'type'    => 'checkbox',
	) );

	// ── Footer ────────────────────────────────────────────────
	$wp_customize->add_section( 'enhanced_footer', array(
		'title' => __( 'Footer', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$wp_customize->add_setting( 'enhanced_footer_logo_text', array( 'default' => 'Enhanced', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
	$wp_customize->add_control( 'enhanced_footer_logo_text', array(
		'label'   => __( 'Footer logotype text', 'enhanced' ),
		'section' => 'enhanced_footer',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'enhanced_footer_tagline', array( 'default' => __( 'Premium fashion — crafted for modern life.', 'enhanced' ), 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
	$wp_customize->add_control( 'enhanced_footer_tagline', array(
		'label'   => __( 'Footer tagline', 'enhanced' ),
		'section' => 'enhanced_footer',
		'type'    => 'textarea',
	) );
}
add_action( 'customize_register', 'enhanced_customizer' );

/**
 * Output customizer CSS inline.
 */
function enhanced_customizer_css() {
	$accent  = get_theme_mod( 'enhanced_accent_color', '#e53e3e' );
	$util_bg = get_theme_mod( 'enhanced_utility_bg',   '#111111' );
	$foot_bg = get_theme_mod( 'enhanced_footer_bg',    '#1a1a1a' );
	?>
	<style id="enhanced-custom-css">
		:root {
			--en-accent:   <?php echo esc_attr( $accent ); ?>;
			--en-util-bg:  <?php echo esc_attr( $util_bg ); ?>;
			--en-footer-bg:<?php echo esc_attr( $foot_bg ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'enhanced_customizer_css' );
