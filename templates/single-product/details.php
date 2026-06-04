<?php
/**
 * Single product details.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="product-details">
	<div class="product-details__header">
		<?php if ( $collection_label ) : ?>
			<span class="product-details__collection"><?php echo esc_html( $collection_label ); ?></span>
		<?php elseif ( $primary_category ) : ?>
			<span class="product-details__collection"><?php echo esc_html( $primary_category ); ?></span>
		<?php endif; ?>

		<h1 class="product-details__title"><?php the_title(); ?></h1>

		<div class="product-details__summary-row">
			<?php if ( $rating_count > 0 && $average_rating > 0 ) : ?>
				<div class="product-details__rating">
					<?php echo wp_kses_post( wc_get_rating_html( $average_rating, $rating_count ) ); ?>
					<span>
						<?php
						printf(
							/* translators: %d review count */
							esc_html( _n( '%d review', '%d reviews', $rating_count, 'enhanced' ) ),
							$rating_count
						);
						?>
					</span>
				</div>
			<?php endif; ?>

			<span class="product-stock-badge product-stock-badge--<?php echo esc_attr( $stock_badge['class'] ); ?>">
				<?php echo esc_html( $stock_badge['label'] ); ?>
			</span>
		</div>

		<div class="product-details__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
	</div>

	<div class="product-details__stack-shell">
		<div class="product-details__purchase-shell">
			<div class="product-details__purchase-head">
				<span class="product-details__purchase-label">
					<?php echo esc_html( ( $product->is_type( 'variable' ) || ! empty( $simple_selection_fields ) ) ? __( 'Select options', 'enhanced' ) : __( 'Ready to order', 'enhanced' ) ); ?>
				</span>

				<?php if ( $size_guide_url && ( $product->is_type( 'variable' ) || ! empty( $simple_selection_fields ) ) ) : ?>
					<a class="product-details__size-guide" href="<?php echo esc_url( $size_guide_url ); ?>">
						<?php echo esc_html( $size_guide_label ); ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="product-details__purchase">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>

			<button
				class="btn-wishlist product-details__wishlist"
				type="button"
				data-wishlist-toggle
				data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
				aria-pressed="false"
			>
				<span class="product-details__wishlist-icon" aria-hidden="true">&#9825;</span>
				<span data-wishlist-label><?php echo esc_html( $wishlist_label ); ?></span>
			</button>
		</div>

		<?php if ( ! empty( $attributes ) || $description_html || ! empty( $material_items ) || $seller_name || ! empty( $seller_points ) ) : ?>
			<div class="product-details__support-shell">
				<?php if ( ! empty( $attributes ) ) : ?>
					<div class="product-details__support-block product-details__support-block--specs">
						<div class="product-details__spec-grid">
							<?php foreach ( $attributes as $attribute ) : ?>
								<div class="product-details__spec-item">
									<span><?php echo esc_html( $attribute['label'] ); ?></span>
									<strong><?php echo esc_html( $attribute['value'] ); ?></strong>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $description_html ) : ?>
					<div class="product-details__support-block">
						<?php if ( $details_heading ) : ?>
							<h2 class="product-details__support-title"><?php echo esc_html( $details_heading ); ?></h2>
						<?php endif; ?>
						<div class="product-details__support-copy">
							<?php echo wp_kses_post( $description_html ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $material_items ) ) : ?>
					<div class="product-details__support-block">
						<?php if ( $material_heading ) : ?>
							<h2 class="product-details__support-title"><?php echo esc_html( $material_heading ); ?></h2>
						<?php endif; ?>
						<ul class="product-details__support-list">
							<?php foreach ( $material_items as $material_item ) : ?>
								<li><?php echo esc_html( $material_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( $seller_name || $seller_meta || ! empty( $seller_points ) ) : ?>
					<div class="product-details__support-block">
						<?php if ( $seller_heading ) : ?>
							<h2 class="product-details__support-title"><?php echo esc_html( $seller_heading ); ?></h2>
						<?php endif; ?>
						<?php if ( $seller_name ) : ?>
							<p class="product-details__seller-name"><?php echo esc_html( $seller_name ); ?></p>
						<?php endif; ?>
						<?php if ( $seller_meta ) : ?>
							<p class="product-details__seller-meta"><?php echo esc_html( $seller_meta ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $seller_points ) ) : ?>
							<ul class="product-details__support-list">
								<?php foreach ( $seller_points as $seller_point ) : ?>
									<li><?php echo esc_html( $seller_point ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="product-meta-card product-meta-card--inline product-meta-card--embedded">
			<div class="product-meta-card__row">
				<span><?php esc_html_e( 'SKU', 'enhanced' ); ?></span>
				<strong><?php echo esc_html( $product->get_sku() ? $product->get_sku() : __( 'Made to order', 'enhanced' ) ); ?></strong>
			</div>

			<?php if ( $primary_category ) : ?>
				<div class="product-meta-card__row">
					<span><?php esc_html_e( 'Category', 'enhanced' ); ?></span>
					<strong><?php echo esc_html( $primary_category ); ?></strong>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
