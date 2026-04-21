<?php
/**
 * Front page template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url                = enhanced_shop_url();
$hero_eyebrow            = enhanced_get_option( 'hero_eyebrow', __( 'Editorial commerce for modern brands', 'enhanced' ) );
$hero_title              = enhanced_get_option( 'hero_title', __( 'Build a storefront that feels tailored, not templated.', 'enhanced' ) );
$hero_description        = enhanced_get_option( 'hero_description', __( 'Enhanced now leans into stronger storytelling, clearer product discovery, and a more premium responsive storefront from homepage to checkout.', 'enhanced' ) );
$hero_primary_label      = enhanced_get_option( 'hero_primary_label', __( 'Shop the collection', 'enhanced' ) );
$hero_primary_url        = enhanced_get_option( 'hero_primary_url', $shop_url );
$hero_secondary_label    = enhanced_get_option( 'hero_secondary_label', __( 'Explore the brand story', 'enhanced' ) );
$hero_secondary_url      = enhanced_get_option( 'hero_secondary_url', home_url( '/about/' ) );
$hero_media_type         = enhanced_get_option( 'hero_media_type', 'image' );
$hero_image_id           = (int) enhanced_get_option( 'hero_image', 0 );
$hero_image_url          = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'enhanced-hero' ) : '';
$hero_video_upload_id    = (int) enhanced_get_option( 'hero_video_upload', 0 );
$hero_video_upload_url   = $hero_video_upload_id ? wp_get_attachment_url( $hero_video_upload_id ) : '';
$hero_external_video_url = enhanced_get_option( 'hero_video_url', '' );
$hero_slider_source      = enhanced_get_option( 'hero_slider_source', 'featured' );
$reference_visual_url    = get_template_directory_uri() . '/assets/images/goodhero-reference.webp';

$categories = array();
$featured   = array();
$arrivals   = array();
$sale_items = array();

if ( enhanced_is_woo() ) {
	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 8,
			'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
		)
	);

	$featured = wc_get_products(
		array(
			'status'   => 'publish',
			'limit'    => 8,
			'featured' => true,
		)
	);

	if ( empty( $featured ) ) {
		$featured = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 8,
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
	}

	$arrivals = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => 10,
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);

	$sale_product_ids = array_slice( wc_get_product_ids_on_sale(), 0, 10 );

	if ( ! empty( $sale_product_ids ) ) {
		$sale_items = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 10,
				'include' => $sale_product_ids,
			)
		);
	}
}

$hero_product           = ! empty( $featured ) ? $featured[0] : ( ! empty( $arrivals ) ? $arrivals[0] : null );
$hero_collections       = array(
	'featured' => $featured,
	'arrivals' => $arrivals,
	'sale'     => $sale_items,
);
$hero_slider_items      = ! empty( $hero_collections[ $hero_slider_source ] ) ? array_slice( $hero_collections[ $hero_slider_source ], 0, 5 ) : array();
$showcase_items         = ! empty( $arrivals ) ? array_slice( $arrivals, 0, 5 ) : array_slice( $featured, 0, 5 );
$hero_external_embed    = '';
$hero_external_type     = wp_check_filetype( $hero_external_video_url );
$hero_external_is_video = ! empty( $hero_external_type['type'] ) && 0 === strpos( $hero_external_type['type'], 'video/' );

if ( $hero_external_video_url && ! $hero_external_is_video ) {
	$hero_external_embed = wp_oembed_get(
		$hero_external_video_url,
		array(
			'width'  => 960,
			'height' => 540,
		)
	);
}

enhanced_get_template(
	'front-page/layout',
	compact(
		'shop_url',
		'hero_eyebrow',
		'hero_title',
		'hero_description',
		'hero_primary_label',
		'hero_primary_url',
		'hero_secondary_label',
		'hero_secondary_url',
		'hero_media_type',
		'hero_image_url',
		'hero_video_upload_url',
		'hero_external_video_url',
		'hero_external_embed',
		'hero_external_is_video',
		'reference_visual_url',
		'categories',
		'featured',
		'sale_items',
		'hero_product',
		'hero_slider_items',
		'showcase_items'
	)
);

get_footer();
