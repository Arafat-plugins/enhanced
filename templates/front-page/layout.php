<?php
/**
 * Front page layout — Luxina structure.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<main id="primary" class="site-main">

	<?php /* 1. 3-panel hero */ ?>
	<?php
	enhanced_get_template(
		'front-page/hero',
		compact(
			'shop_url', 'hero_eyebrow', 'hero_title', 'hero_description',
			'hero_primary_label', 'hero_primary_url', 'hero_secondary_label', 'hero_secondary_url',
			'hero_media_type', 'hero_image_url', 'hero_video_upload_url',
			'hero_external_video_url', 'hero_external_embed', 'hero_external_is_video',
			'reference_visual_url', 'hero_product', 'popular_subcats'
		)
	);
	?>

	<?php /* 2. Browse Categories */ ?>
	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<?php enhanced_get_template( 'front-page/category-rail', compact( 'categories', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php /* 3. Sale Is On */ ?>
	<?php if ( ! empty( $sale_items ) || ! empty( $featured ) ) : ?>
		<?php enhanced_get_template( 'front-page/sale-rail', compact( 'sale_items', 'featured', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php /* 4. New Arrivals carousel */ ?>
	<?php if ( ! empty( $arrivals ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => '',
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

	<?php /* 5. Full-width sale banner */ ?>
	<?php enhanced_get_template( 'front-page/sale-banner', compact( 'shop_url' ) ); ?>

	<?php /* 6. New Sale For Women */ ?>
	<?php if ( ! empty( $sale_items ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => '',
				'products'        => array_slice( $sale_items, 0, 8 ),
				'shop_url'        => $shop_url,
				'eyebrow'         => '',
				'title'           => __( 'New Sale For Women', 'enhanced' ),
				'link_label'      => __( 'View All', 'enhanced' ),
				'link_url'        => add_query_arg( 'orderby', 'price', $shop_url ),
				'prev_label'      => __( 'Previous', 'enhanced' ),
				'next_label'      => __( 'Next', 'enhanced' ),
			)
		);
		?>
	<?php endif; ?>

	<?php /* 7. New Sale For Men */ ?>
	<?php if ( ! empty( $featured ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => 'lx-product-rail--alt',
				'products'        => array_slice( $featured, 0, 8 ),
				'shop_url'        => $shop_url,
				'eyebrow'         => '',
				'title'           => __( 'New Sale For Men', 'enhanced' ),
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
