<?php
/**
 * Front page layout - suggested storefront structure.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<main id="primary" class="site-main">

	<?php /* 1. Hero */ ?>
	<?php
	enhanced_get_template(
		'front-page/hero',
		compact(
			'shop_url', 'hero_eyebrow', 'hero_title', 'hero_description',
			'hero_primary_label', 'hero_primary_url',
			'hero_media_type', 'hero_image_url', 'hero_video_upload_url',
			'hero_external_video_url', 'hero_external_embed', 'hero_external_is_video',
			'reference_visual_url', 'hero_product', 'popular_subcats'
		)
	);
	?>

	<?php /* 2. Trust badges */ ?>
	<?php enhanced_get_template( 'front-page/trust-badges' ); ?>

	<?php /* 3. Shop by category */ ?>
	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<?php enhanced_get_template( 'front-page/category-rail', compact( 'categories', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php /* 4. New arrivals */ ?>
	<?php if ( ! empty( $arrivals ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => 'lx-product-rail--grid',
				'products'        => array_slice( $arrivals, 0, 8 ),
				'shop_url'        => $shop_url,
				'eyebrow'         => '',
				'title'           => __( 'New Arrivals', 'enhanced' ),
				'link_label'      => __( 'View All', 'enhanced' ),
				'link_url'        => $shop_url,
				'prev_label'      => __( 'Previous', 'enhanced' ),
				'next_label'      => __( 'Next', 'enhanced' ),
			)
		);
		?>
	<?php endif; ?>

	<?php /* 5. Sale promos */ ?>
	<?php if ( ! empty( $sale_items ) || ! empty( $featured ) ) : ?>
		<?php enhanced_get_template( 'front-page/sale-rail', compact( 'sale_items', 'featured', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php /* 6. Dark benefit banner */ ?>
	<?php enhanced_get_template( 'front-page/sale-banner', compact( 'shop_url' ) ); ?>

	<?php /* 7. Featured products */ ?>
	<?php if ( ! empty( $featured ) || ! empty( $arrivals ) ) : ?>
		<?php
		$featured_tabs = array(
			array(
				'label'  => __( 'Women', 'enhanced' ),
				'url'    => $women_cat ? get_term_link( $women_cat ) : $shop_url,
				'active' => true,
			),
			array(
				'label'  => __( 'Men', 'enhanced' ),
				'url'    => $men_cat ? get_term_link( $men_cat ) : $shop_url,
				'active' => false,
			),
			array(
				'label'  => __( 'Sale', 'enhanced' ),
				'url'    => add_query_arg( 'orderby', 'price', $shop_url ),
				'active' => false,
			),
		);

		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => 'lx-product-rail--grid lx-product-rail--featured',
				'products'        => array_slice( ! empty( $featured ) ? $featured : $arrivals, 0, 8 ),
				'shop_url'        => $shop_url,
				'eyebrow'         => '',
				'title'           => __( 'Featured Products', 'enhanced' ),
				'tabs'            => $featured_tabs,
				'link_label'      => __( 'View All', 'enhanced' ),
				'link_url'        => $shop_url,
				'prev_label'      => __( 'Previous', 'enhanced' ),
				'next_label'      => __( 'Next', 'enhanced' ),
			)
		);
		?>
	<?php endif; ?>

	<?php /* 8. Blog */ ?>
	<?php if ( ! empty( $blog_posts ) ) : ?>
		<?php enhanced_get_template( 'front-page/blog-section', compact( 'blog_posts' ) ); ?>
	<?php endif; ?>

	<?php /* 9. Newsletter */ ?>
	<?php enhanced_get_template( 'front-page/newsletter' ); ?>

</main>
