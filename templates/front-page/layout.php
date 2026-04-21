<?php
/**
 * Front page layout.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<main id="primary" class="site-main">
	<?php
	enhanced_get_template(
		'front-page/hero',
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
			'hero_product',
			'hero_slider_items'
		)
	);
	?>

	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<?php enhanced_get_template( 'front-page/category-rail', compact( 'categories', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php if ( ! empty( $showcase_items ) ) : ?>
		<?php enhanced_get_template( 'front-page/arrival-showcase', compact( 'showcase_items', 'reference_visual_url' ) ); ?>
	<?php endif; ?>

	<?php if ( ! empty( $featured ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => 'home-rail-section',
				'products'        => $featured,
				'shop_url'        => $shop_url,
				'eyebrow'         => __( 'Featured', 'enhanced' ),
				'title'           => __( 'Featured Products', 'enhanced' ),
				'link_label'      => __( 'Shop all', 'enhanced' ),
				'link_url'        => $shop_url,
				'prev_label'      => __( 'Previous products', 'enhanced' ),
				'next_label'      => __( 'Next products', 'enhanced' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! empty( $sale_items ) ) : ?>
		<?php
		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => 'home-rail-section home-rail-section--soft',
				'products'        => $sale_items,
				'shop_url'        => $shop_url,
				'eyebrow'         => __( 'Offers', 'enhanced' ),
				'title'           => __( 'Sale Picks', 'enhanced' ),
				'link_label'      => __( 'See more', 'enhanced' ),
				'link_url'        => add_query_arg( 'orderby', 'price', $shop_url ),
				'prev_label'      => __( 'Previous products', 'enhanced' ),
				'next_label'      => __( 'Next products', 'enhanced' ),
			)
		);
		?>
	<?php endif; ?>
</main>
