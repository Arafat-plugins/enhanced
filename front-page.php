<?php
/**
 * Front page template — Luxina layout.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url                = enhanced_shop_url();
$hero_eyebrow            = enhanced_get_option( 'hero_eyebrow', __( 'Limited Offers', 'enhanced' ) );
$hero_title              = enhanced_get_option( 'hero_title', __( 'Elevated Ethnic Menswear', 'enhanced' ) );
$hero_description        = enhanced_get_option( 'hero_description', __( 'Discover quality fashion that reflects your style and makes everyday living more enjoyable.', 'enhanced' ) );
$hero_primary_label      = enhanced_get_option( 'hero_primary_label', __( 'Explore Product', 'enhanced' ) );
$hero_primary_url        = enhanced_get_option( 'hero_primary_url', $shop_url );
$hero_secondary_label    = enhanced_get_option( 'hero_secondary_label', __( 'Explore the Brand', 'enhanced' ) );
$hero_secondary_url      = enhanced_get_option( 'hero_secondary_url', home_url( '/about/' ) );
$hero_media_type         = enhanced_get_option( 'hero_media_type', 'image' );
$hero_image_url          = enhanced_get_image_option_url( 'hero_image', 'enhanced-hero' );
$hero_video_upload_id    = (int) enhanced_get_option( 'hero_video_upload', 0 );
$hero_video_upload_url   = $hero_video_upload_id ? wp_get_attachment_url( $hero_video_upload_id ) : '';
$hero_external_video_url = enhanced_get_option( 'hero_video_url', '' );
$reference_visual_url    = get_template_directory_uri() . '/assets/images/goodhero-reference.webp';

$categories = array();
$featured   = array();
$arrivals   = array();
$sale_items = array();
$blog_posts = array();

if ( enhanced_is_woo() ) {
	$categories = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'number'     => 10,
		'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
	) );

	$featured = wc_get_products( array(
		'status'   => 'publish',
		'limit'    => 8,
		'featured' => true,
	) );

	if ( empty( $featured ) ) {
		$featured = wc_get_products( array(
			'status'  => 'publish',
			'limit'   => 8,
			'orderby' => 'date',
			'order'   => 'DESC',
		) );
	}

	$arrivals = wc_get_products( array(
		'status'  => 'publish',
		'limit'   => 10,
		'orderby' => 'date',
		'order'   => 'DESC',
	) );

	$sale_ids = array_slice( wc_get_product_ids_on_sale(), 0, 10 );
	if ( ! empty( $sale_ids ) ) {
		$sale_items = wc_get_products( array(
			'status'  => 'publish',
			'limit'   => 10,
			'include' => $sale_ids,
		) );
	}
}

$popular_subcats = enhanced_is_woo() ? enhanced_get_popular_subcategories( 2 ) : array();

/* Blog posts */
$blog_query = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
) );
$blog_posts = $blog_query->posts;

$hero_product        = ! empty( $arrivals ) ? $arrivals[0] : ( ! empty( $featured ) ? $featured[0] : null );

$hero_external_embed   = '';
$hero_external_type    = wp_check_filetype( $hero_external_video_url );
$hero_external_is_video = ! empty( $hero_external_type['type'] ) && 0 === strpos( $hero_external_type['type'], 'video/' );

if ( $hero_external_video_url && ! $hero_external_is_video ) {
	$hero_external_embed = enhanced_get_autoplay_embed_html( $hero_external_video_url );
}

enhanced_get_template(
	'front-page/layout',
	compact(
		'shop_url', 'hero_eyebrow', 'hero_title', 'hero_description',
		'hero_primary_label', 'hero_primary_url', 'hero_secondary_label', 'hero_secondary_url',
		'hero_media_type', 'hero_image_url', 'hero_video_upload_url',
		'hero_external_video_url', 'hero_external_embed', 'hero_external_is_video',
		'reference_visual_url', 'categories', 'featured', 'sale_items',
		'hero_product', 'arrivals', 'blog_posts', 'popular_subcats'
	)
);

get_footer();
