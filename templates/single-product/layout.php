<?php
/**
 * Single product layout.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'enhanced-single-product enhanced-single-product--refined', $product ); ?>>
	<div class="single-product-wrap">
		<div class="container">
			<div class="product-focus">
				<?php enhanced_get_template( 'single-product/breadcrumb', compact( 'primary_category', 'primary_category_url' ) ); ?>

				<div class="single-product-layout single-product-layout--editorial" data-product-gallery>
					<?php enhanced_get_template( 'single-product/gallery', compact( 'product', 'main_image', 'gallery_images' ) ); ?>
					<?php
					enhanced_get_template(
						'single-product/details',
						compact(
							'product',
							'collection_label',
							'primary_category',
							'rating_count',
							'average_rating',
							'stock_badge',
							'size_guide_label',
							'size_guide_url',
							'simple_selection_fields',
							'wishlist_label',
							'attributes',
							'description_html',
							'details_heading',
							'material_heading',
							'material_items',
							'seller_heading',
							'seller_name',
							'seller_meta',
							'seller_points'
						)
					);
					?>
				</div>

				<?php woocommerce_output_product_data_tabs(); ?>

				<?php if ( ! empty( $related_products ) ) : ?>
					<?php enhanced_get_template( 'single-product/related', compact( 'related_products', 'related_columns', 'related_autoplay', 'related_eyebrow', 'related_title' ) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>
