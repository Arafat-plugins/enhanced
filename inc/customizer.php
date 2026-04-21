<?php
/**
 * WordPress Customizer settings.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

function enhanced_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$control = $setting->manager->get_control( $setting->id );

	if ( ! $control || ! isset( $control->choices[ $input ] ) ) {
		return $setting->default;
	}

	return $input;
}

function enhanced_customizer( $wp_customize ) {
	$wp_customize->add_panel( 'enhanced_panel', array(
		'title'    => __( 'Enhanced Theme', 'enhanced' ),
		'priority' => 10,
	) );

	$wp_customize->add_section( 'enhanced_header', array(
		'title' => __( 'Header', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$header_controls = array(
		'utility_text'  => array(
			'label'             => __( 'Utility bar text', 'enhanced' ),
			'default'           => __( 'Free shipping on orders over $100', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'utility_link'  => array(
			'label'             => __( 'Utility bar link URL', 'enhanced' ),
			'default'           => '',
			'type'              => 'url',
			'sanitize_callback' => 'esc_url_raw',
		),
		'contact_email' => array(
			'label'             => __( 'Header email', 'enhanced' ),
			'default'           => 'hello@enhanced.store',
			'type'              => 'email',
			'sanitize_callback' => 'sanitize_email',
		),
		'contact_phone' => array(
			'label'             => __( 'Header phone', 'enhanced' ),
			'default'           => '+1 (800) 000-0000',
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
	);

	foreach ( $header_controls as $id => $args ) {
		$wp_customize->add_setting( 'enhanced_' . $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => $args['sanitize_callback'],
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'enhanced_' . $id, array(
			'label'   => $args['label'],
			'section' => 'enhanced_header',
			'type'    => $args['type'],
		) );
	}

	$wp_customize->add_section( 'enhanced_colors', array(
		'title' => __( 'Colors', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$colors = array(
		'accent_color' => array(
			'label'   => __( 'Accent / Price color', 'enhanced' ),
			'default' => '#e53e3e',
		),
		'utility_bg'   => array(
			'label'   => __( 'Utility bar background', 'enhanced' ),
			'default' => '#111111',
		),
		'footer_bg'    => array(
			'label'   => __( 'Footer background', 'enhanced' ),
			'default' => '#1a1a1a',
		),
	);

	foreach ( $colors as $id => $args ) {
		$wp_customize->add_setting( 'enhanced_' . $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'enhanced_' . $id, array(
			'label'   => $args['label'],
			'section' => 'enhanced_colors',
		) ) );
	}

	$wp_customize->add_section( 'enhanced_front_page', array(
		'title' => __( 'Front Page', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$front_page_controls = array(
		'hero_eyebrow'         => array(
			'label'             => __( 'Hero eyebrow', 'enhanced' ),
			'default'           => __( 'Editorial commerce for modern brands', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_title'           => array(
			'label'             => __( 'Hero title', 'enhanced' ),
			'default'           => __( 'Build a storefront that feels tailored, not templated.', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_description'     => array(
			'label'             => __( 'Hero description', 'enhanced' ),
			'default'           => __( 'Enhanced now leans into stronger storytelling, clearer product discovery, and a more premium responsive storefront from homepage to checkout.', 'enhanced' ),
			'type'              => 'textarea',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'hero_primary_label'   => array(
			'label'             => __( 'Primary button label', 'enhanced' ),
			'default'           => __( 'Shop the collection', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_primary_url'     => array(
			'label'             => __( 'Primary button URL', 'enhanced' ),
			'default'           => '',
			'type'              => 'url',
			'sanitize_callback' => 'esc_url_raw',
		),
		'hero_secondary_label' => array(
			'label'             => __( 'Secondary button label', 'enhanced' ),
			'default'           => __( 'Explore the brand story', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_secondary_url'   => array(
			'label'             => __( 'Secondary button URL', 'enhanced' ),
			'default'           => '',
			'type'              => 'url',
			'sanitize_callback' => 'esc_url_raw',
		),
	);

	foreach ( $front_page_controls as $id => $args ) {
		$wp_customize->add_setting( 'enhanced_' . $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => $args['sanitize_callback'],
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'enhanced_' . $id, array(
			'label'   => $args['label'],
			'section' => 'enhanced_front_page',
			'type'    => $args['type'],
		) );
	}

	$wp_customize->add_setting( 'enhanced_hero_image', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'enhanced_hero_image', array(
		'label'   => __( 'Hero image', 'enhanced' ),
		'section' => 'enhanced_front_page',
	) ) );

	$wp_customize->add_setting( 'enhanced_hero_media_type', array(
		'default'           => 'image',
		'sanitize_callback' => 'enhanced_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'enhanced_hero_media_type', array(
		'label'   => __( 'Hero media type', 'enhanced' ),
		'section' => 'enhanced_front_page',
		'type'    => 'select',
		'choices' => array(
			'image'          => __( 'Image', 'enhanced' ),
			'video'          => __( 'Uploaded video', 'enhanced' ),
			'external-video' => __( 'External video URL', 'enhanced' ),
			'slider'         => __( 'Product slider', 'enhanced' ),
		),
	) );

	$wp_customize->add_setting( 'enhanced_hero_video_upload', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'enhanced_hero_video_upload', array(
		'label'      => __( 'Hero uploaded video', 'enhanced' ),
		'section'    => 'enhanced_front_page',
		'mime_type'  => 'video',
	) ) );

	$wp_customize->add_setting( 'enhanced_hero_video_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'enhanced_hero_video_url', array(
		'label'       => __( 'Hero external video URL', 'enhanced' ),
		'description' => __( 'Supports direct MP4/WebM links or embeddable YouTube/Vimeo URLs.', 'enhanced' ),
		'section'     => 'enhanced_front_page',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'enhanced_hero_slider_source', array(
		'default'           => 'featured',
		'sanitize_callback' => 'enhanced_sanitize_select',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'enhanced_hero_slider_source', array(
		'label'   => __( 'Hero slider source', 'enhanced' ),
		'section' => 'enhanced_front_page',
		'type'    => 'select',
		'choices' => array(
			'featured' => __( 'Featured products', 'enhanced' ),
			'arrivals' => __( 'New arrivals', 'enhanced' ),
			'sale'     => __( 'Sale products', 'enhanced' ),
		),
	) );

	$wp_customize->add_section( 'enhanced_shop', array(
		'title' => __( 'Shop', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$wp_customize->add_setting( 'enhanced_shop_sidebar', array(
		'default'           => true,
		'sanitize_callback' => 'enhanced_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'enhanced_shop_sidebar', array(
		'label'   => __( 'Show sidebar filters on shop page', 'enhanced' ),
		'section' => 'enhanced_shop',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_section( 'enhanced_footer', array(
		'title' => __( 'Footer', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$wp_customize->add_setting( 'enhanced_footer_logo_text', array(
		'default'           => 'Enhanced',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'enhanced_footer_logo_text', array(
		'label'   => __( 'Footer logotype text', 'enhanced' ),
		'section' => 'enhanced_footer',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'enhanced_footer_tagline', array(
		'default'           => __( 'Premium fashion - crafted for modern life.', 'enhanced' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

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
	$util_bg = get_theme_mod( 'enhanced_utility_bg', '#111111' );
	$foot_bg = get_theme_mod( 'enhanced_footer_bg', '#1a1a1a' );
	?>
	<style id="enhanced-custom-css">
		:root {
			--en-accent: <?php echo esc_attr( $accent ); ?>;
			--en-util-bg: <?php echo esc_attr( $util_bg ); ?>;
			--en-footer-bg: <?php echo esc_attr( $foot_bg ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'enhanced_customizer_css' );
