<?php
/**
 * WordPress Customizer settings — LUXINA layout.
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

/**
 * Helper: register text/url/textarea controls in bulk.
 */
function enhanced_customizer_add_controls( $wp_customize, $section, $controls ) {
	foreach ( $controls as $id => $args ) {
		$wp_customize->add_setting( 'enhanced_' . $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => $args['sanitize_callback'],
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'enhanced_' . $id, array(
			'label'       => $args['label'],
			'description' => isset( $args['description'] ) ? $args['description'] : '',
			'section'     => $section,
			'type'        => $args['type'],
		) );
	}
}

/**
 * Helper: register an image attachment control.
 */
function enhanced_customizer_add_image( $wp_customize, $id, $section, $label, $description = '' ) {
	$wp_customize->add_setting( 'enhanced_' . $id, array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'enhanced_' . $id, array(
		'label'       => $label,
		'description' => $description,
		'section'     => $section,
		'mime_type'   => 'image',
	) ) );
}

function enhanced_customizer( $wp_customize ) {
	$wp_customize->add_panel( 'enhanced_panel', array(
		'title'    => __( 'Enhanced Theme (Luxina)', 'enhanced' ),
		'priority' => 10,
	) );

	/* ──────────────────────────────────────────────────────────
	 * 1) HEADER
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_header', array(
		'title'       => __( 'Header', 'enhanced' ),
		'panel'       => 'enhanced_panel',
		'description' => __( 'Header utility bar (hidden by default in Luxina) and contact info.', 'enhanced' ),
	) );

	enhanced_customizer_add_controls( $wp_customize, 'enhanced_header', array(
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
	) );

	/* ──────────────────────────────────────────────────────────
	 * 2) COLORS
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_colors', array(
		'title' => __( 'Colors', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$colors = array(
		'accent_color' => array( 'label' => __( 'Accent / Sale color', 'enhanced' ), 'default' => '#cc2222' ),
		'utility_bg'   => array( 'label' => __( 'Utility bar background', 'enhanced' ), 'default' => '#111111' ),
		'footer_bg'    => array( 'label' => __( 'Footer background', 'enhanced' ), 'default' => '#1a1a1a' ),
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

	/* ──────────────────────────────────────────────────────────
	 * 3) FRONT PAGE — HERO (3-panel)
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_hero', array(
		'title'       => __( 'Front Page · Hero', 'enhanced' ),
		'panel'       => 'enhanced_panel',
		'description' => __( '3-panel hero: left coral panel + top-right offer + bottom-right accessories.', 'enhanced' ),
	) );

	enhanced_customizer_add_controls( $wp_customize, 'enhanced_hero', array(
		// Left panel
		'hero_eyebrow' => array(
			'label'             => __( 'Left · Eyebrow / Badge text', 'enhanced' ),
			'default'           => __( 'Limited Offers', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_discount' => array(
			'label'             => __( 'Left · Big discount number', 'enhanced' ),
			'description'       => __( 'Just the number, e.g. 50', 'enhanced' ),
			'default'           => '50',
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_description' => array(
			'label'             => __( 'Left · Description', 'enhanced' ),
			'default'           => __( 'Discover quality fashion that reflects your style and makes everyday living more enjoyable.', 'enhanced' ),
			'type'              => 'textarea',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'hero_primary_label' => array(
			'label'             => __( 'Left · Button label', 'enhanced' ),
			'default'           => __( 'Explore Product', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_primary_url' => array(
			'label'             => __( 'Left · Button URL', 'enhanced' ),
			'default'           => '',
			'type'              => 'url',
			'sanitize_callback' => 'esc_url_raw',
		),
		// Top right (TR) panel
		'hero_tr_label' => array(
			'label'             => __( 'Top-Right · Eyebrow label', 'enhanced' ),
			'default'           => __( 'For New Commerce', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_tr_title' => array(
			'label'             => __( 'Top-Right · Title', 'enhanced' ),
			'default'           => __( 'Exclusive Offer', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_tr_btn' => array(
			'label'             => __( 'Top-Right · Button label', 'enhanced' ),
			'default'           => __( 'Click More', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		// Bottom right (BR) panel
		'hero_br_badge' => array(
			'label'             => __( 'Bottom-Right · Badge text', 'enhanced' ),
			'default'           => __( 'New Arrival 2026', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'hero_br_title' => array(
			'label'             => __( 'Bottom-Right · Title', 'enhanced' ),
			'default'           => __( 'Browse Accessories', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
	) );

	enhanced_customizer_add_image( $wp_customize, 'hero_image', 'enhanced_hero', __( 'Left panel · Uploaded image', 'enhanced' ) );
	enhanced_customizer_add_image( $wp_customize, 'hero_tr_image', 'enhanced_hero', __( 'Top-Right panel · Image', 'enhanced' ) );
	enhanced_customizer_add_image( $wp_customize, 'hero_br_image', 'enhanced_hero', __( 'Bottom-Right panel · Image (e.g. accessories)', 'enhanced' ) );

	// Hero media type controls.
	$wp_customize->add_setting( 'enhanced_hero_media_type', array(
		'default'           => 'image',
		'sanitize_callback' => 'enhanced_sanitize_select',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'enhanced_hero_media_type', array(
		'label'       => __( 'Left panel · Media type', 'enhanced' ),
		'description' => __( 'Choose whether the left hero should show an image, an uploaded video, or an external video URL.', 'enhanced' ),
		'section'     => 'enhanced_hero',
		'type'        => 'select',
		'choices'     => array(
			'image'          => __( 'Image', 'enhanced' ),
			'video'          => __( 'Uploaded video', 'enhanced' ),
			'external-video' => __( 'External video URL', 'enhanced' ),
		),
	) );

	$wp_customize->add_setting( 'enhanced_hero_video_upload', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'enhanced_hero_video_upload', array(
		'label'       => __( 'Left panel · Uploaded video', 'enhanced' ),
		'description' => __( 'Used when Media type is set to Uploaded video.', 'enhanced' ),
		'section'     => 'enhanced_hero',
		'mime_type'   => 'video',
	) ) );

	$wp_customize->add_setting( 'enhanced_hero_video_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'enhanced_hero_video_url', array(
		'label'       => __( 'Left panel · External video URL', 'enhanced' ),
		'description' => __( 'Used when Media type is set to External video URL.', 'enhanced' ),
		'section'     => 'enhanced_hero',
		'type'        => 'url',
	) );

	/* ──────────────────────────────────────────────────────────
	 * 4) FRONT PAGE — RIGHT PANEL SLIDES (TR + BR sliders)
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_right_slides', array(
		'title'       => __( 'Right Panel Slides', 'enhanced' ),
		'panel'       => 'enhanced_panel',
		'description' => __( 'Configure the top-right and bottom-right auto-sliding panels. Each supports up to 3 slides. Upload an image to activate a slide. Set overlay text and adjust opacity for readability.', 'enhanced' ),
	) );

	// Autoplay intervals
	$wp_customize->add_setting( 'enhanced_rp_tr_interval', array(
		'default'           => 4000,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'enhanced_rp_tr_interval', array(
		'label'       => __( 'Top-Right · Slide interval (ms)', 'enhanced' ),
		'description' => __( 'How long each slide stays visible, e.g. 4000 = 4 seconds.', 'enhanced' ),
		'section'     => 'enhanced_right_slides',
		'type'        => 'number',
	) );

	$wp_customize->add_setting( 'enhanced_rp_br_interval', array(
		'default'           => 5000,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'enhanced_rp_br_interval', array(
		'label'       => __( 'Bottom-Right · Slide interval (ms)', 'enhanced' ),
		'description' => __( 'How long each slide stays visible, e.g. 5000 = 5 seconds.', 'enhanced' ),
		'section'     => 'enhanced_right_slides',
		'type'        => 'number',
	) );

	// Slide fields: 2 panels × 3 slides × (image + title + opacity)
	$rp_panels = array(
		'tr' => __( '⬆ Top-Right', 'enhanced' ),
		'br' => __( '⬇ Bottom-Right', 'enhanced' ),
	);

	foreach ( $rp_panels as $panel_key => $panel_label ) {
		for ( $n = 1; $n <= 3; $n++ ) {
			$pfx = "rp_{$panel_key}_s{$n}";

			enhanced_customizer_add_image(
				$wp_customize,
				"{$pfx}_img",
				'enhanced_right_slides',
				/* translators: 1: panel label 2: slide number */
				sprintf( __( '%1$s · Slide %2$d · Image', 'enhanced' ), $panel_label, $n ),
				__( 'Leave empty to skip this slide.', 'enhanced' )
			);

			$wp_customize->add_setting( "enhanced_{$pfx}_title", array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			) );
			$wp_customize->add_control( "enhanced_{$pfx}_title", array(
				'label'   => sprintf( __( '%1$s · Slide %2$d · Overlay text', 'enhanced' ), $panel_label, $n ),
				'section' => 'enhanced_right_slides',
				'type'    => 'text',
			) );

			$wp_customize->add_setting( "enhanced_{$pfx}_opacity", array(
				'default'           => 25,
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			) );
			$wp_customize->add_control( "enhanced_{$pfx}_opacity", array(
				'label'       => sprintf( __( '%1$s · Slide %2$d · Overlay opacity (0–100)', 'enhanced' ), $panel_label, $n ),
				'description' => __( '0 = no overlay · 100 = fully dark', 'enhanced' ),
				'section'     => 'enhanced_right_slides',
				'type'        => 'number',
			) );
		}
	}

	/* ──────────────────────────────────────────────────────────
	 * 5) FRONT PAGE — BROWSE CATEGORIES SPLIT
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_browse', array(
		'title'       => __( 'Front Page · Browse Categories', 'enhanced' ),
		'panel'       => 'enhanced_panel',
		'description' => __( 'The right-side model image shown below the category tiles.', 'enhanced' ),
	) );

	enhanced_customizer_add_image( $wp_customize, 'browse_model_image', 'enhanced_browse', __( 'Browse · Model image (right panel)', 'enhanced' ) );

	enhanced_customizer_add_image( $wp_customize, 'browse_panel_bg', 'enhanced_browse', __( 'Browse · Left panel background image', 'enhanced' ), __( 'Image shown behind the dark overlay on the left text panel.', 'enhanced' ) );

	$wp_customize->add_setting( 'enhanced_browse_overlay_opacity', array(
		'default'           => 0.78,
		'sanitize_callback' => 'floatval',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'enhanced_browse_overlay_opacity', array(
		'label'       => __( 'Browse · Left panel overlay opacity', 'enhanced' ),
		'description' => __( '0 = fully transparent (image only) · 1 = fully black. Default: 0.78', 'enhanced' ),
		'section'     => 'enhanced_browse',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ),
	) );

	/* ──────────────────────────────────────────────────────────
	 * 5) FRONT PAGE — SALE IS ON
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_sale_on', array(
		'title' => __( 'Front Page · Sale Is On!', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	enhanced_customizer_add_controls( $wp_customize, 'enhanced_sale_on', array(
		'sale_due_label' => array(
			'label'             => __( 'Due-date badge text', 'enhanced' ),
			'description'       => __( 'Shown in the corner of each promo card, e.g. "Due Aug 24"', 'enhanced' ),
			'default'           => __( 'Due Aug 24', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
	) );

	/* ──────────────────────────────────────────────────────────
	 * 6) FRONT PAGE — END OF SEASON SALE BANNER
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_sale_banner', array(
		'title' => __( 'Front Page · Sale Banner', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	enhanced_customizer_add_controls( $wp_customize, 'enhanced_sale_banner', array(
		'sale_banner_eyebrow' => array(
			'label'             => __( 'Eyebrow', 'enhanced' ),
			'default'           => __( 'Last Chance', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'sale_banner_title' => array(
			'label'             => __( 'Title', 'enhanced' ),
			'default'           => __( 'End of Season Sale Up to 50% Off', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'sale_banner_btn' => array(
			'label'             => __( 'Button label', 'enhanced' ),
			'default'           => __( 'Check It Out', 'enhanced' ),
			'type'              => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'sale_banner_url' => array(
			'label'             => __( 'Button URL', 'enhanced' ),
			'default'           => '',
			'type'              => 'url',
			'sanitize_callback' => 'esc_url_raw',
		),
	) );

	enhanced_customizer_add_image( $wp_customize, 'sale_banner_image', 'enhanced_sale_banner', __( 'Banner · Image', 'enhanced' ) );

	/* ──────────────────────────────────────────────────────────
	 * 7) FRONT PAGE — BLOG SECTION
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_blog_section', array(
		'title' => __( 'Front Page · Blog Section', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	enhanced_customizer_add_controls( $wp_customize, 'enhanced_blog_section', array(
		'blog_section_desc' => array(
			'label'             => __( 'Header description', 'enhanced' ),
			'default'           => __( 'Discover quality of our blog, that better reflects your style and makes everything living more enjoyable.', 'enhanced' ),
			'type'              => 'textarea',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
	) );

	/* ──────────────────────────────────────────────────────────
	 * 8) FRONT PAGE — NEWSLETTER
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_newsletter', array(
		'title' => __( 'Front Page · Newsletter', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	enhanced_customizer_add_controls( $wp_customize, 'enhanced_newsletter', array(
		'newsletter_desc' => array(
			'label'             => __( 'Newsletter description', 'enhanced' ),
			'default'           => __( 'Discover quality fashion that reflects your style and makes everyday living more enjoyable.', 'enhanced' ),
			'type'              => 'textarea',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
	) );

	/* ──────────────────────────────────────────────────────────
	 * 9) SHOP
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_shop', array(
		'title' => __( 'Shop / WooCommerce', 'enhanced' ),
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

	/* ──────────────────────────────────────────────────────────
	 * 10) SINGLE PRODUCT
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_single_product', array(
		'title' => __( 'Single Product', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	enhanced_customizer_add_controls( $wp_customize, 'enhanced_single_product', array(
		'product_collection_label' => array(
			'label' => __( 'Collection label', 'enhanced' ),
			'default' => __( 'Minimal modern collection', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_size_guide_label' => array(
			'label' => __( 'Size guide label', 'enhanced' ), 'default' => __( 'Size guide', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_size_guide_url' => array(
			'label' => __( 'Size guide URL', 'enhanced' ), 'default' => '',
			'type' => 'url', 'sanitize_callback' => 'esc_url_raw',
		),
		'product_details_heading' => array(
			'label' => __( 'Details heading', 'enhanced' ), 'default' => __( 'Product details', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_material_heading' => array(
			'label' => __( 'Material heading', 'enhanced' ), 'default' => __( 'Material & care', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_material_items' => array(
			'label' => __( 'Material items', 'enhanced' ),
			'default' => __( "Premium fabric blend\nMachine wash or dry clean", 'enhanced' ),
			'type' => 'textarea', 'sanitize_callback' => 'sanitize_textarea_field',
		),
		'product_seller_heading' => array(
			'label' => __( 'Seller heading', 'enhanced' ), 'default' => __( 'Sold by', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_seller_name' => array(
			'label' => __( 'Seller name', 'enhanced' ), 'default' => get_bloginfo( 'name' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_seller_meta' => array(
			'label' => __( 'Seller meta line', 'enhanced' ), 'default' => __( 'Fast dispatch and careful packaging.', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_seller_points' => array(
			'label' => __( 'Seller bullet points', 'enhanced' ),
			'default' => __( "Secure checkout\nCarefully packed orders\nResponsive customer support", 'enhanced' ),
			'type' => 'textarea', 'sanitize_callback' => 'sanitize_textarea_field',
		),
		'product_related_eyebrow' => array(
			'label' => __( 'Related section eyebrow', 'enhanced' ), 'default' => __( 'Similar products', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_related_title' => array(
			'label' => __( 'Related section title', 'enhanced' ), 'default' => __( 'You may also like', 'enhanced' ),
			'type' => 'text', 'sanitize_callback' => 'sanitize_text_field',
		),
		'product_related_count' => array(
			'label' => __( 'Related product count', 'enhanced' ), 'default' => 8,
			'type' => 'number', 'sanitize_callback' => 'absint',
		),
		'product_related_autoplay' => array(
			'label' => __( 'Related slider autoplay (ms)', 'enhanced' ), 'default' => 2000,
			'type' => 'number', 'sanitize_callback' => 'absint',
		),
	) );

	$wp_customize->add_setting( 'enhanced_product_related_columns', array(
		'default'           => '4',
		'sanitize_callback' => 'enhanced_sanitize_select',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'enhanced_product_related_columns', array(
		'label'   => __( 'Related products visible on desktop', 'enhanced' ),
		'section' => 'enhanced_single_product',
		'type'    => 'select',
		'choices' => array( '4' => '4 cards', '3' => '3 cards' ),
	) );

	/* ──────────────────────────────────────────────────────────
	 * 11) FOOTER
	 * ────────────────────────────────────────────────────────── */
	$wp_customize->add_section( 'enhanced_footer', array(
		'title' => __( 'Footer', 'enhanced' ),
		'panel' => 'enhanced_panel',
	) );

	$wp_customize->add_setting( 'enhanced_footer_logo_text', array(
		'default'           => 'LUXINA',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'enhanced_footer_logo_text', array(
		'label'       => __( 'Footer logotype text', 'enhanced' ),
		'description' => __( 'The giant text shown at the very bottom of the page.', 'enhanced' ),
		'section'     => 'enhanced_footer',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'enhanced_footer_tagline', array(
		'default'           => __( 'Premium fashion - crafted for modern life.', 'enhanced' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'enhanced_footer_tagline', array(
		'label'   => __( 'Footer tagline (used on inner pages)', 'enhanced' ),
		'section' => 'enhanced_footer',
		'type'    => 'textarea',
	) );
}
// Settings are now managed via Enhanced → Settings admin page.
// add_action( 'customize_register', 'enhanced_customizer' );

/**
 * Output customizer CSS inline.
 */
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
