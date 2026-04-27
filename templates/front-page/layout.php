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

	<?php /* 3. New Arrivals carousel */ ?>
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

	<?php /* 4. Sale Is On */ ?>
	<?php if ( ! empty( $sale_items ) || ! empty( $featured ) ) : ?>
		<?php enhanced_get_template( 'front-page/sale-rail', compact( 'sale_items', 'featured', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php /* 5. Full-width sale banner */ ?>
	<?php enhanced_get_template( 'front-page/sale-banner', compact( 'shop_url' ) ); ?>

	<?php /* 6. Women's products — sale items if available, else latest from women's category */ ?>
	<?php if ( ! empty( $women_products ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => '',
				'products'        => array_slice( $women_products, 0, 8 ),
				'marquee_direction' => 'left',
				'shop_url'        => $shop_url,
				'eyebrow'         => '',
				'title'           => $women_cat
					? sprintf( __( 'New Sale For %s', 'enhanced' ), $women_cat->name )
					: __( 'New Sale', 'enhanced' ),
				'link_label'      => __( 'View All', 'enhanced' ),
				'link_url'        => $women_cat ? get_term_link( $women_cat ) : add_query_arg( 'orderby', 'price', $shop_url ),
				'prev_label'      => __( 'Previous', 'enhanced' ),
				'next_label'      => __( 'Next', 'enhanced' ),
			)
		);
		?>
	<?php endif; ?>

	<?php /* 7. Men's products — featured if available, else latest from men's category */ ?>
	<?php if ( ! empty( $men_products ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => 'lx-product-rail--alt',
				'products'        => array_slice( $men_products, 0, 8 ),
				'marquee_direction' => 'right',
				'shop_url'        => $shop_url,
				'eyebrow'         => '',
				'title'           => $men_cat
					? sprintf( __( 'New For %s', 'enhanced' ), $men_cat->name )
					: __( 'Featured Products', 'enhanced' ),
				'link_label'      => __( 'View All', 'enhanced' ),
				'link_url'        => $men_cat ? get_term_link( $men_cat ) : $shop_url,
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
