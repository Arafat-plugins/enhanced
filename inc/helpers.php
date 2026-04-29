<?php
/**
 * Helper functions.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_is_woo() {
	return class_exists( 'WooCommerce' );
}

function enhanced_is_shop_context() {
	if ( ! enhanced_is_woo() || ! function_exists( 'is_shop' ) ) {
		return false;
	}

	return is_shop() || is_product_taxonomy() || is_post_type_archive( 'product' );
}

function enhanced_should_load_woo_assets() {
	if ( ! enhanced_is_woo() || is_admin() ) {
		return false;
	}

	if ( enhanced_is_shop_context() ) {
		return true;
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		return true;
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		return true;
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return true;
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		return true;
	}

	return false;
}

function enhanced_get_option( $key, $default = '' ) {
	return get_theme_mod( 'enhanced_' . $key, $default );
}

function enhanced_get_rp_animation_choices() {
	return array(
		'zoom-out'   => __( 'Zoom out', 'enhanced' ),
		'fade'       => __( 'Fade in / out', 'enhanced' ),
		'slide-right' => __( 'Left to right', 'enhanced' ),
		'slide-left' => __( 'Right to left', 'enhanced' ),
		'slide-up'   => __( 'Bottom to top', 'enhanced' ),
		'slide-down' => __( 'Top to bottom', 'enhanced' ),
		'zoom-in'    => __( 'Zoom in', 'enhanced' ),
		'random'     => __( 'Random', 'enhanced' ),
	);
}

function enhanced_sanitize_rp_animation( $value ) {
	$value   = sanitize_key( (string) $value );
	$choices = enhanced_get_rp_animation_choices();

	return isset( $choices[ $value ] ) ? $value : 'zoom-out';
}

function enhanced_get_rp_animation_easing_choices() {
	return array(
		'smooth'      => __( 'Smooth', 'enhanced' ),
		'ease'        => __( 'Ease', 'enhanced' ),
		'ease-in'     => __( 'Ease in', 'enhanced' ),
		'ease-out'    => __( 'Ease out', 'enhanced' ),
		'ease-in-out' => __( 'Ease in out', 'enhanced' ),
		'linear'      => __( 'Linear', 'enhanced' ),
	);
}

function enhanced_sanitize_rp_animation_easing( $value ) {
	$value   = sanitize_key( (string) $value );
	$choices = enhanced_get_rp_animation_easing_choices();

	return isset( $choices[ $value ] ) ? $value : 'smooth';
}

function enhanced_get_image_option_url( $key, $size = 'full', $default = '' ) {
	$value = enhanced_get_option( $key, '' );

	if ( is_numeric( $value ) && (int) $value > 0 ) {
		$image_url = wp_get_attachment_image_url( (int) $value, $size );
		return $image_url ?: $default;
	}

	if ( is_string( $value ) && '' !== trim( $value ) ) {
		return esc_url_raw( $value );
	}

	return $default;
}

function enhanced_get_youtube_video_id( $url ) {
	$parts = wp_parse_url( (string) $url );

	if ( empty( $parts['host'] ) ) {
		return '';
	}

	$host = strtolower( (string) $parts['host'] );
	$path = isset( $parts['path'] ) ? trim( (string) $parts['path'], '/' ) : '';

	if ( false !== strpos( $host, 'youtu.be' ) && '' !== $path ) {
		return preg_replace( '/[^A-Za-z0-9_-]/', '', strtok( $path, '/' ) );
	}

	if ( false === strpos( $host, 'youtube.com' ) && false === strpos( $host, 'youtube-nocookie.com' ) ) {
		return '';
	}

	if ( ! empty( $parts['query'] ) ) {
		parse_str( (string) $parts['query'], $query_args );
		if ( ! empty( $query_args['v'] ) ) {
			return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $query_args['v'] );
		}
	}

	if ( preg_match( '~(?:embed|shorts)/([^/?&]+)~', $path, $matches ) ) {
		return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $matches[1] );
	}

	return '';
}

function enhanced_get_vimeo_video_id( $url ) {
	$parts = wp_parse_url( (string) $url );

	if ( empty( $parts['host'] ) ) {
		return '';
	}

	$host = strtolower( (string) $parts['host'] );
	$path = isset( $parts['path'] ) ? trim( (string) $parts['path'], '/' ) : '';

	if ( false === strpos( $host, 'vimeo.com' ) ) {
		return '';
	}

	if ( preg_match( '~(?:video/)?(\d+)~', $path, $matches ) ) {
		return (string) $matches[1];
	}

	return '';
}

function enhanced_get_autoplay_embed_html( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return '';
	}

	$iframe_attrs = 'loading="eager" allow="autoplay; encrypted-media; fullscreen; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"';
	$iframe_title = esc_attr__( 'Hero video', 'enhanced' );
	$youtube_id   = enhanced_get_youtube_video_id( $url );

	if ( $youtube_id ) {
		$src = add_query_arg(
			array(
				'autoplay'        => 1,
				'mute'            => 1,
				'loop'            => 1,
				'playlist'        => $youtube_id,
				'controls'        => 0,
				'playsinline'     => 1,
				'rel'             => 0,
				'modestbranding'  => 1,
				'iv_load_policy'  => 3,
				'enablejsapi'     => 1,
				'origin'          => home_url(),
			),
			'https://www.youtube.com/embed/' . rawurlencode( $youtube_id )
		);

		return sprintf(
			'<iframe src="%1$s" title="%2$s" %3$s data-hero-autoplay-provider="youtube"></iframe>',
			esc_url( $src ),
			$iframe_title,
			$iframe_attrs
		);
	}

	$vimeo_id = enhanced_get_vimeo_video_id( $url );

	if ( $vimeo_id ) {
		$src = add_query_arg(
			array(
				'autoplay'  => 1,
				'muted'     => 1,
				'loop'      => 1,
				'autopause' => 0,
				'background'=> 1,
			),
			'https://player.vimeo.com/video/' . rawurlencode( $vimeo_id )
		);

		return sprintf(
			'<iframe src="%1$s" title="%2$s" %3$s data-hero-autoplay-provider="vimeo"></iframe>',
			esc_url( $src ),
			$iframe_title,
			$iframe_attrs
		);
	}

	$embed = wp_oembed_get( $url, array( 'width' => 960, 'height' => 540 ) );

	if ( ! $embed ) {
		return '';
	}

	return $embed;
}

function enhanced_get_option_lines( $key, $default = array() ) {
	$default_value = is_array( $default ) ? implode( "\n", $default ) : (string) $default;
	$value         = (string) enhanced_get_option( $key, $default_value );
	$lines         = preg_split( '/\r\n|\r|\n/', $value );

	if ( ! is_array( $lines ) ) {
		return (array) $default;
	}

	$lines = array_values(
		array_filter(
			array_map( 'trim', $lines )
		)
	);

	return ! empty( $lines ) ? $lines : (array) $default;
}

function enhanced_shop_url() {
	if ( enhanced_is_woo() ) {
		$shop_id = wc_get_page_id( 'shop' );

		if ( $shop_id > 0 ) {
			return get_permalink( $shop_id );
		}
	}

	return home_url( '/shop/' );
}

function enhanced_cart_count() {
	if ( enhanced_is_woo() && function_exists( 'WC' ) && WC()->cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}
	return 0;
}

function enhanced_cart_url() {
	return enhanced_is_woo() ? wc_get_cart_url() : home_url( '/cart/' );
}

function enhanced_account_url() {
	return enhanced_is_woo() ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
}

/**
 * Returns up to $count popular/best-selling subcategories scored by total product sales.
 * Falls back to all (non-default) categories if no subcategories exist.
 *
 * Each item: [ 'name', 'url', 'total_sales', 'thumbnail_url' ]
 */
/**
 * Build slides array for a right-panel slider from Enhanced Settings storage.
 * Storage: rp_{panel}_images (comma-separated IDs), rp_{panel}_title, rp_{panel}_opacity.
 */
function enhanced_build_rp_slides( $panel_key ) {
	$ids_raw = enhanced_get_option( "rp_{$panel_key}_images", '' );
	$ids     = array_filter( array_map( 'intval', explode( ',', (string) $ids_raw ) ) );

	if ( empty( $ids ) ) {
		return array();
	}

	$title   = sanitize_text_field( (string) enhanced_get_option( "rp_{$panel_key}_title", '' ) );
	$opacity = min( 100, max( 0, (int) enhanced_get_option( "rp_{$panel_key}_opacity", 25 ) ) ) / 100;
	$slides  = array();

	foreach ( $ids as $img_id ) {
		$url = wp_get_attachment_image_url( $img_id, 'enhanced-hero' );
		if ( ! $url ) {
			continue;
		}
		$slides[] = array(
			'img'     => $url,
			'title'   => $title,
			'opacity' => $opacity,
		);
	}

	return $slides;
}

function enhanced_get_popular_subcategories( $count = 2 ) {
	if ( ! enhanced_is_woo() ) {
		return array();
	}

	$default_cat = (int) get_option( 'default_product_cat', 0 );
	$exclude     = $default_cat ? array( $default_cat ) : array();

	$all_cats = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'number'     => 0,
		'exclude'    => $exclude,
	) );

	if ( empty( $all_cats ) || is_wp_error( $all_cats ) ) {
		return array();
	}

	// Prefer real sub-categories (parent > 0); fall back to all categories.
	$pool = array_filter( $all_cats, function ( $t ) { return $t->parent > 0; } );
	if ( empty( $pool ) ) {
		$pool = $all_cats;
	}

	$scored = array();
	foreach ( $pool as $term ) {
		$product_ids = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'tax_query'      => array( array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			) ),
		) );

		$total_sales = 0;
		foreach ( $product_ids as $pid ) {
			$total_sales += (int) get_post_meta( $pid, 'total_sales', true );
		}

		$thumbnail_id  = get_term_meta( $term->term_id, 'thumbnail_id', true );
		$thumbnail_url = $thumbnail_id
			? wp_get_attachment_image_url( (int) $thumbnail_id, 'enhanced-card' )
			: '';

		$term_link = get_term_link( $term );

		$scored[] = array(
			'name'          => $term->name,
			'url'           => is_wp_error( $term_link ) ? '' : $term_link,
			'total_sales'   => $total_sales,
			'thumbnail_url' => $thumbnail_url,
		);
	}

	usort( $scored, function ( $a, $b ) {
		return $b['total_sales'] - $a['total_sales'];
	} );

	return array_slice( $scored, 0, $count );
}

function enhanced_get_product_primary_category_name( $product_id = 0 ) {
	if ( ! enhanced_is_woo() ) {
		return '';
	}

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$terms      = get_the_terms( $product_id, 'product_cat' );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	$default_category = (int) get_option( 'default_product_cat', 0 );

	foreach ( $terms as $term ) {
		if ( (int) $term->term_id === $default_category ) {
			continue;
		}

		return $term->name;
	}

	return $terms[0]->name;
}

function enhanced_get_product_primary_image_id( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return 0;
	}

	$featured_image_id = (int) $product->get_image_id();
	$gallery_ids       = array_map( 'intval', $product->get_gallery_image_ids() );
	$primary_image_id  = $featured_image_id;

	if ( ! $primary_image_id && ! empty( $gallery_ids[0] ) ) {
		$primary_image_id = (int) $gallery_ids[0];
	}

	if ( ! $primary_image_id && $product->is_type( 'variable' ) ) {
		foreach ( $product->get_children() as $variation_id ) {
			$variation = wc_get_product( $variation_id );

			if ( ! $variation instanceof WC_Product_Variation ) {
				continue;
			}

			$primary_image_id = (int) $variation->get_image_id();

			if ( $primary_image_id ) {
				break;
			}
		}
	}

	return (int) $primary_image_id;
}

function enhanced_get_product_card_media( $product, $size = 'enhanced-card' ) {
	$placeholder = enhanced_is_woo() && function_exists( 'wc_placeholder_img_src' )
		? wc_placeholder_img_src( $size )
		: '';

	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return array(
			'primary_url'   => $placeholder,
			'secondary_url' => '',
			'alt'           => '',
		);
	}

	$featured_image_id = (int) $product->get_image_id();
	$gallery_ids       = array_map( 'intval', $product->get_gallery_image_ids() );
	$primary_image_id  = enhanced_get_product_primary_image_id( $product );

	$secondary_image_id = 0;

	if ( $featured_image_id && ! empty( $gallery_ids[0] ) ) {
		$secondary_image_id = (int) $gallery_ids[0];
	} elseif ( ! $featured_image_id && ! empty( $gallery_ids[1] ) ) {
		$secondary_image_id = (int) $gallery_ids[1];
	}

	$primary_url = $primary_image_id ? wp_get_attachment_image_url( $primary_image_id, $size ) : '';

	if ( ! $primary_url ) {
		$primary_url = $placeholder;
	}

	$secondary_url = $secondary_image_id ? wp_get_attachment_image_url( $secondary_image_id, $size ) : '';
	$alt           = $primary_image_id ? get_post_meta( $primary_image_id, '_wp_attachment_image_alt', true ) : '';

	return array(
		'primary_url'   => $primary_url,
		'secondary_url' => $secondary_url ? $secondary_url : '',
		'alt'           => $alt ? $alt : $product->get_name(),
	);
}

function enhanced_get_term_card_media( $term, $size = 'enhanced-card' ) {
	$placeholder = enhanced_is_woo() && function_exists( 'wc_placeholder_img_src' )
		? wc_placeholder_img_src( $size )
		: '';

	if ( ! $term instanceof WP_Term ) {
		return array(
			'image_id'  => 0,
			'image_url' => $placeholder,
			'alt'       => '',
		);
	}

	$image_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, $size ) : '';

	if ( $image_id && $image_url ) {
		return array(
			'image_id'  => $image_id,
			'image_url' => $image_url,
			'alt'       => $term->name,
		);
	}

	if ( enhanced_is_woo() ) {
		$product_ids = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				),
			),
		) );

		if ( ! empty( $product_ids[0] ) ) {
			$product = wc_get_product( (int) $product_ids[0] );
			$image_id = enhanced_get_product_primary_image_id( $product );

			if ( $image_id ) {
				return array(
					'image_id'  => $image_id,
					'image_url' => '',
					'alt'       => $term->name,
				);
			}
		}
	}

	return array(
		'image_id'  => 0,
		'image_url' => $placeholder,
		'alt'       => $term->name,
	);
}

function enhanced_get_product_sale_badge( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product || ! $product->is_on_sale() ) {
		return '';
	}

	if ( ! $product->get_regular_price() || ! $product->get_sale_price() ) {
		return '';
	}

	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();

	if ( $regular <= 0 || $sale <= 0 || $sale >= $regular ) {
		return '';
	}

	$percentage = (int) round( ( ( $regular - $sale ) / $regular ) * 100 );

	if ( $percentage <= 0 ) {
		return '';
	}

	return sprintf(
		/* translators: %d percentage off */
		__( '-%d%%', 'enhanced' ),
		$percentage
	);
}

function enhanced_get_product_card_attribute_options( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return array(
			'colors' => array(),
			'sizes'  => array(),
		);
	}

	$options = array(
		'colors' => array(),
		'sizes'  => array(),
	);

	foreach ( $product->get_attributes() as $attribute ) {
		if ( ! $attribute instanceof WC_Product_Attribute || ! $attribute->get_visible() ) {
			continue;
		}

		$attribute_name  = $attribute->get_name();
		$attribute_label = wc_attribute_label( $attribute_name );
		$attribute_type  = enhanced_get_attribute_picker_type( $attribute_name, $attribute_label );

		if ( ! in_array( $attribute_type, array( 'color', 'size' ), true ) ) {
			continue;
		}

		$raw_values = array();

		if ( $attribute->is_taxonomy() ) {
			$terms = wc_get_product_terms(
				$product->get_id(),
				$attribute_name,
				array( 'fields' => 'all' )
			);

			if ( is_wp_error( $terms ) ) {
				$terms = array();
			}

			foreach ( $terms as $term ) {
				$raw_values[] = $term->name;
			}
		} else {
			foreach ( $attribute->get_options() as $option_value ) {
				$raw_values[] = $option_value;
			}
		}

		$fallback_value = $product->get_attribute( $attribute_name );
		if ( $fallback_value ) {
			$raw_values[] = $fallback_value;
		}

		foreach ( enhanced_split_attribute_option_values( $raw_values ) as $value ) {
			if ( 'color' === $attribute_type ) {
				$options['colors'][ strtolower( $value ) ] = array(
					'label' => $value,
					'color' => enhanced_get_color_swatch_value( $value ),
				);
			} else {
				$options['sizes'][ strtolower( $value ) ] = $value;
			}
		}
	}

	$options['colors'] = array_values( $options['colors'] );
	$options['sizes']  = array_values( $options['sizes'] );

	return $options;
}

function enhanced_get_product_gallery_images( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return array();
	}

	$image_ids = array_unique(
		array_filter(
			array_merge(
				array( (int) $product->get_image_id() ),
				array_map( 'intval', $product->get_gallery_image_ids() )
			)
		)
	);

	$images = array();

	foreach ( $image_ids as $image_id ) {
		$large = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
		$thumb = wp_get_attachment_image_url( $image_id, 'enhanced-thumb' );

		if ( ! $large || ! $thumb ) {
			continue;
		}

		$images[] = array(
			'id'    => $image_id,
			'large' => $large,
			'thumb' => $thumb,
			'alt'   => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
		);
	}

	if ( ! empty( $images ) ) {
		return $images;
	}

	return array(
		array(
			'id'    => 0,
			'large' => wc_placeholder_img_src( 'woocommerce_single' ),
			'thumb' => wc_placeholder_img_src( 'enhanced-thumb' ),
			'alt'   => $product->get_name(),
		),
	);
}

function enhanced_get_product_stock_badge( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return array(
			'class' => 'is-neutral',
			'label' => __( 'Available now', 'enhanced' ),
		);
	}

	if ( ! $product->is_in_stock() ) {
		return array(
			'class' => 'is-out',
			'label' => __( 'Currently unavailable', 'enhanced' ),
		);
	}

	if ( $product->is_on_backorder( 1 ) ) {
		return array(
			'class' => 'is-backorder',
			'label' => __( 'Available on backorder', 'enhanced' ),
		);
	}

	if ( $product->managing_stock() && null !== $product->get_stock_quantity() ) {
		$quantity = (int) $product->get_stock_quantity();

		if ( $quantity > 0 && $quantity <= 6 ) {
			return array(
				'class' => 'is-low',
				'label' => sprintf(
					/* translators: %d remaining stock quantity */
					__( 'Only %d left in stock', 'enhanced' ),
					$quantity
				),
			);
		}
	}

	return array(
		'class' => 'is-in',
		'label' => __( 'Ready to dispatch', 'enhanced' ),
	);
}

function enhanced_get_attribute_picker_type( $attribute_name, $attribute_label = '' ) {
	$haystack = strtolower( trim( $attribute_name . ' ' . $attribute_label ) );

	if ( false !== strpos( $haystack, 'color' ) || false !== strpos( $haystack, 'colour' ) ) {
		return 'color';
	}

	if ( false !== strpos( $haystack, 'size' ) ) {
		return 'size';
	}

	return 'text';
}

function enhanced_is_light_hex_color( $hex_color ) {
	$hex_color = strtolower( trim( (string) $hex_color ) );

	if ( ! preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6})$/', $hex_color, $matches ) ) {
		return false;
	}

	$hex = $matches[1];

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	$red        = hexdec( substr( $hex, 0, 2 ) );
	$green      = hexdec( substr( $hex, 2, 2 ) );
	$blue       = hexdec( substr( $hex, 4, 2 ) );
	$brightness = ( ( $red * 299 ) + ( $green * 587 ) + ( $blue * 114 ) ) / 1000;

	return $brightness >= 214;
}

function enhanced_split_attribute_option_values( $raw_values ) {
	$raw_values = is_array( $raw_values ) ? $raw_values : array( $raw_values );
	$values     = array();

	foreach ( $raw_values as $raw_value ) {
		$raw_value = html_entity_decode( wp_strip_all_tags( (string) $raw_value ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		$raw_value = trim( preg_replace( '/\s+/', ' ', $raw_value ) );

		if ( '' === $raw_value ) {
			continue;
		}

		$parts = preg_split( '/\s*\|\s*/', $raw_value );

		if ( ! is_array( $parts ) || count( $parts ) < 2 ) {
			$parts = preg_split( '/\s*,\s*/', $raw_value );
		}

		if ( ! is_array( $parts ) || empty( $parts ) ) {
			$parts = array( $raw_value );
		}

		foreach ( $parts as $part ) {
			$part = trim( (string) $part );

			if ( '' === $part ) {
				continue;
			}

			$values[ strtolower( $part ) ] = $part;
		}
	}

	return array_values( $values );
}

function enhanced_get_color_swatch_value( $color_name, $fallback = '#cccccc' ) {
	$color_name     = trim( (string) $color_name );
	$normalized_key = function_exists( 'enhanced_normalize_color_key' ) ? enhanced_normalize_color_key( $color_name ) : sanitize_title( $color_name );
	$colors         = function_exists( 'enhanced_get_color_options' ) ? enhanced_get_color_options() : array();
	$lookup         = array();

	foreach ( $colors as $name => $hex ) {
		$lookup[ strtolower( (string) $name ) ] = (string) $hex;

		if ( function_exists( 'enhanced_normalize_color_key' ) ) {
			$lookup[ enhanced_normalize_color_key( $name ) ] = (string) $hex;
		}
	}

	if ( isset( $lookup[ strtolower( $color_name ) ] ) ) {
		return $lookup[ strtolower( $color_name ) ];
	}

	if ( isset( $lookup[ $normalized_key ] ) ) {
		return $lookup[ $normalized_key ];
	}

	return (string) $fallback;
}

function enhanced_get_simple_product_selection_fields( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product || ! $product->is_type( 'simple' ) ) {
		return array();
	}

	$fields             = array();
	$product_attributes = $product->get_attributes();
	$color_map          = function_exists( 'enhanced_get_color_options' ) ? enhanced_get_color_options() : array();

	foreach ( $product_attributes as $attribute ) {
		if ( ! $attribute instanceof WC_Product_Attribute || ! $attribute->get_visible() ) {
			continue;
		}

		$attribute_name  = $attribute->get_name();
		$attribute_label = wc_attribute_label( $attribute_name );
		$attribute_type  = enhanced_get_attribute_picker_type( $attribute_name, $attribute_label );

		if ( ! in_array( $attribute_type, array( 'color', 'size' ), true ) ) {
			continue;
		}

		$raw_option_values = array();

		if ( $attribute->is_taxonomy() ) {
			$terms = wc_get_product_terms(
				$product->get_id(),
				$attribute_name,
				array(
					'fields' => 'all',
				)
			);

			if ( is_wp_error( $terms ) ) {
				$terms = array();
			}

			foreach ( $terms as $term ) {
				$raw_option_values[] = $term->name;
			}
		} else {
			foreach ( $attribute->get_options() as $option_value ) {
				$raw_option_values[] = $option_value;
			}
		}

		$fallback_value = $product->get_attribute( $attribute_name );

		if ( $fallback_value ) {
			$raw_option_values[] = $fallback_value;
		}

		$option_values = enhanced_split_attribute_option_values( $raw_option_values );

		if ( count( $option_values ) < 2 ) {
			continue;
		}

		$options = array();

		foreach ( $option_values as $option_value ) {
			$option = array(
				'label' => $option_value,
				'value' => $option_value,
			);

			if ( 'color' === $attribute_type ) {
				$option['color'] = enhanced_get_color_swatch_value( $option_value );
			}

			$options[] = $option;
		}

		if ( count( $options ) < 2 ) {
			continue;
		}

		$fields[] = array(
			'key'            => sanitize_title( $attribute_name ),
			'attribute_name' => $attribute_name,
			'label'          => $attribute_label,
			'type'           => $attribute_type,
			'options'        => $options,
		);
	}

	return $fields;
}

function enhanced_simple_product_has_selection_fields( $product ) {
	return ! empty( enhanced_get_simple_product_selection_fields( $product ) );
}

function enhanced_get_page_intro_data() {
	$title       = get_the_title();
	$eyebrow     = '';
	$description = '';
	$highlights  = array();

	if ( enhanced_is_woo() ) {
		if ( function_exists( 'is_cart' ) && is_cart() ) {
			$eyebrow     = __( 'Bag review', 'enhanced' );
			$title       = __( 'Your shopping bag', 'enhanced' );
			$description = __( 'Review your pieces, update quantities, and move to checkout with a cleaner premium flow on every screen size.', 'enhanced' );
			$highlights  = array(
				__( 'Flexible quantity controls', 'enhanced' ),
				__( 'Responsive totals summary', 'enhanced' ),
				__( 'Fast route to checkout', 'enhanced' ),
			);
		} elseif ( function_exists( 'is_checkout' ) && is_checkout() && ! is_order_received_page() ) {
			$eyebrow     = __( 'Secure checkout', 'enhanced' );
			$title       = __( 'Finish your order', 'enhanced' );
			$description = __( 'A calmer, more luxurious checkout with clear form groupings, secure payment messaging, and better mobile spacing.', 'enhanced' );
			$highlights  = array(
				__( 'Protected payment flow', 'enhanced' ),
				__( 'Clear order review panel', 'enhanced' ),
				__( 'Mobile-friendly fields', 'enhanced' ),
			);
		} elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
			$eyebrow     = __( 'Client account', 'enhanced' );
			$title       = __( 'Your personal account', 'enhanced' );
			$description = __( 'Track orders, manage addresses, and keep your customer dashboard feeling polished instead of looking like a default plugin page.', 'enhanced' );
			$highlights  = array(
				__( 'Better dashboard navigation', 'enhanced' ),
				__( 'Readable order history', 'enhanced' ),
				__( 'Comfortable mobile layout', 'enhanced' ),
			);
		}
	}

	if ( ! $description && has_excerpt() ) {
		$description = get_the_excerpt();
	}

	if ( ! $description ) {
		$content = wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', get_the_ID() ) ) );

		if ( $content ) {
			$description = wp_trim_words( $content, 26 );
		}
	}

	return array(
		'eyebrow'     => $eyebrow,
		'title'       => $title,
		'description' => $description,
		'highlights'  => $highlights,
	);
}

function enhanced_render_product_card( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return;
	}

	$previous_product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
	$GLOBALS['product'] = $product;

	wc_get_template_part( 'content', 'product' );

	if ( $previous_product instanceof WC_Product ) {
		$GLOBALS['product'] = $previous_product;
	} else {
		unset( $GLOBALS['product'] );
	}
}

/**
 * WooCommerce cart fragment for AJAX count update.
 */
function enhanced_cart_fragment( $fragments ) {
	$count = enhanced_cart_count();
	ob_start();
	?>
	<span class="header-cart__count" data-cart-count><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['span.header-cart__count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'enhanced_cart_fragment' );
