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

	<?php /* 2. Shop by category */ ?>
	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<?php enhanced_get_template( 'front-page/category-rail', compact( 'categories', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php /* 3. Sale promos */ ?>
	<?php if ( ! empty( $sale_items ) || ! empty( $featured ) ) : ?>
		<?php enhanced_get_template( 'front-page/sale-rail', compact( 'sale_items', 'featured', 'shop_url' ) ); ?>
	<?php endif; ?>

	<?php /* 4. Dark benefit banner */ ?>
	<?php enhanced_get_template( 'front-page/sale-banner', compact( 'shop_url' ) ); ?>

	<?php /* 5. New arrivals */ ?>
	<?php if ( ! empty( $arrivals ) ) : ?>
		<?php
		$arrivals_slider_count    = min( 10, max( 4, (int) enhanced_get_option( 'arrivals_slider_count', 8 ) ) );
		$arrivals_slider_autoplay = max( 0, (int) enhanced_get_option( 'arrivals_slider_autoplay', 3200 ) );
		$arrivals_slider_step     = min( 4, max( 1, (int) enhanced_get_option( 'arrivals_slider_step', 1 ) ) );
		$arrivals_slider_animation = enhanced_sanitize_rp_animation( enhanced_get_option( 'arrivals_slider_animation', 'slide-left' ) );
		$arrivals_slider_animation_duration = min( 3000, max( 200, (int) enhanced_get_option( 'arrivals_slider_animation_duration', 700 ) ) );
		$arrivals_slider_animation_easing = enhanced_sanitize_rp_animation_easing( enhanced_get_option( 'arrivals_slider_animation_easing', 'smooth' ) );
		$arrivals_slider_desktop  = min( 5, max( 2, (int) enhanced_get_option( 'arrivals_slider_desktop_columns', 4 ) ) );
		$arrivals_slider_tablet   = min( 3, max( 1, (int) enhanced_get_option( 'arrivals_slider_tablet_columns', 2 ) ) );
		$arrivals_slider_mobile   = min( 2, max( 1, (int) enhanced_get_option( 'arrivals_slider_mobile_columns', 1 ) ) );
		$arrivals_slider_loop     = (bool) enhanced_get_option( 'arrivals_slider_loop', true );
		$arrivals_slider_pause    = (bool) enhanced_get_option( 'arrivals_slider_pause_hover', true );

		enhanced_get_template(
			'front-page/product-rail',
			array(
				'section_classes' => 'lx-product-rail--grid lx-product-rail--arrivals',
				'products'        => array_slice( $arrivals, 0, $arrivals_slider_count ),
				'shop_url'        => $shop_url,
				'rail_autoplay'   => $arrivals_slider_autoplay,
				'rail_step'       => $arrivals_slider_step,
				'rail_loop'       => $arrivals_slider_loop,
				'rail_pause_hover'=> $arrivals_slider_pause,
				'rail_animation'  => $arrivals_slider_animation,
				'rail_animation_duration' => $arrivals_slider_animation_duration,
				'rail_animation_easing' => $arrivals_slider_animation_easing,
				'rail_cols_desktop' => $arrivals_slider_desktop,
				'rail_cols_tablet'  => $arrivals_slider_tablet,
				'rail_cols_mobile'  => $arrivals_slider_mobile,
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

	<?php /* 6. Featured products */ ?>
	<?php if ( ! empty( $featured ) ) : ?>
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
				'products'        => array_slice( $featured, 0, 8 ),
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

	<?php /* 7. Blog */ ?>
	<?php if ( ! empty( $blog_posts ) ) : ?>
		<?php enhanced_get_template( 'front-page/blog-section', compact( 'blog_posts' ) ); ?>
	<?php endif; ?>

	<?php /* 8. Newsletter */ ?>
	<?php enhanced_get_template( 'front-page/newsletter' ); ?>

</main>
