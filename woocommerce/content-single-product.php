<?php
/**
 * Custom single product layout.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

if ( ! $product instanceof WC_Product ) {
	return;
}

$gallery_images          = enhanced_get_product_gallery_images( $product );
$main_image              = reset( $gallery_images );
$rating_count            = (int) $product->get_rating_count();
$average_rating          = (float) $product->get_average_rating();
$stock_badge             = enhanced_get_product_stock_badge( $product );
$short_description       = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
$description_html        = $short_description ? $short_description : wpautop( wp_kses_post( wp_trim_words( wp_strip_all_tags( $post->post_content ), 42 ) ) );
$default_category_id     = (int) get_option( 'default_product_cat', 0 );
$primary_category        = '';
$primary_category_url    = '';
$collection_label        = enhanced_get_option( 'product_collection_label', __( 'Minimal modern collection', 'enhanced' ) );
$size_guide_label        = enhanced_get_option( 'product_size_guide_label', __( 'Size guide', 'enhanced' ) );
$size_guide_url          = enhanced_get_option( 'product_size_guide_url', '' );
$details_heading         = enhanced_get_option( 'product_details_heading', __( 'Product details', 'enhanced' ) );
$material_heading        = enhanced_get_option( 'product_material_heading', __( 'Material & care', 'enhanced' ) );
$material_items          = enhanced_get_option_lines(
	'product_material_items',
	array(
		__( 'Premium fabric blend', 'enhanced' ),
		__( 'Machine wash or dry clean', 'enhanced' ),
	)
);
$seller_heading          = enhanced_get_option( 'product_seller_heading', __( 'Sold by', 'enhanced' ) );
$seller_name             = enhanced_get_option( 'product_seller_name', get_bloginfo( 'name' ) );
$seller_meta             = enhanced_get_option( 'product_seller_meta', __( 'Fast dispatch and careful packaging.', 'enhanced' ) );
$seller_points           = enhanced_get_option_lines(
	'product_seller_points',
	array(
		__( 'Secure checkout', 'enhanced' ),
		__( 'Carefully packed orders', 'enhanced' ),
		__( 'Responsive customer support', 'enhanced' ),
	)
);
$related_eyebrow         = enhanced_get_option( 'product_related_eyebrow', __( 'Similar products', 'enhanced' ) );
$related_title           = enhanced_get_option( 'product_related_title', __( 'You may also like', 'enhanced' ) );
$related_count           = max( 3, (int) enhanced_get_option( 'product_related_count', 8 ) );
$related_columns         = (string) enhanced_get_option( 'product_related_columns', '4' );
$related_autoplay        = max( 0, (int) enhanced_get_option( 'product_related_autoplay', 2000 ) );
$related_product_ids     = wc_get_related_products( $product->get_id(), $related_count );
$related_products        = array();
$simple_selection_fields = enhanced_get_simple_product_selection_fields( $product );
$attributes              = array();
$product_attributes      = $product->get_attributes();
$category_terms          = get_the_terms( $product->get_id(), 'product_cat' );
$wishlist_label          = __( 'Add to wishlist', 'enhanced' );

if ( ! in_array( $related_columns, array( '3', '4' ), true ) ) {
	$related_columns = '4';
}

if ( ! empty( $category_terms ) && ! is_wp_error( $category_terms ) ) {
	foreach ( $category_terms as $category_term ) {
		if ( (int) $category_term->term_id === $default_category_id ) {
			continue;
		}

		$primary_category     = $category_term->name;
		$primary_category_url = get_term_link( $category_term );
		break;
	}

	if ( ! $primary_category ) {
		$primary_category     = $category_terms[0]->name;
		$primary_category_url = get_term_link( $category_terms[0] );
	}
}

foreach ( $product_attributes as $attribute ) {
	if ( ! $attribute->get_visible() ) {
		continue;
	}

	$label = wc_attribute_label( $attribute->get_name() );
	$value = $product->get_attribute( $attribute->get_name() );

	if ( ! $value ) {
		continue;
	}

	if ( count( $attributes ) < 3 ) {
		$attributes[] = array(
			'label' => $label,
			'value' => $value,
		);
	}
}

foreach ( $related_product_ids as $related_product_id ) {
	$related_product = wc_get_product( $related_product_id );

	if ( ! $related_product instanceof WC_Product || ! $related_product->is_visible() ) {
		continue;
	}

	$related_products[] = $related_product;
}

if ( empty( $related_products ) ) {
	$related_products = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => $related_count,
			'exclude' => array( $product->get_id() ),
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);
}

if ( count( $related_products ) < $related_count ) {
	$existing_ids = array_merge(
		array( $product->get_id() ),
		array_map(
			static function ( $related_product_item ) {
				return $related_product_item instanceof WC_Product ? $related_product_item->get_id() : 0;
			},
			$related_products
		)
	);

	$fallback_products = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => $related_count - count( $related_products ),
			'exclude' => array_filter( array_map( 'intval', $existing_ids ) ),
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);

	if ( ! empty( $fallback_products ) ) {
		$related_products = array_merge( $related_products, $fallback_products );
	}
}

enhanced_get_template(
	'single-product/layout',
	compact(
		'product',
		'gallery_images',
		'main_image',
		'rating_count',
		'average_rating',
		'stock_badge',
		'description_html',
		'primary_category',
		'primary_category_url',
		'collection_label',
		'size_guide_label',
		'size_guide_url',
		'details_heading',
		'material_heading',
		'material_items',
		'seller_heading',
		'seller_name',
		'seller_meta',
		'seller_points',
		'related_eyebrow',
		'related_title',
		'related_columns',
		'related_autoplay',
		'related_products',
		'simple_selection_fields',
		'attributes',
		'wishlist_label'
	)
);

do_action( 'woocommerce_after_single_product' );
