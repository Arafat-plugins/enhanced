<?php
/**
 * Product card template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->is_visible() ) {
	return;
}

$product_id          = $product->get_id();
$product_name        = $product->get_name();
$permalink           = get_permalink( $product_id );
$card_media          = enhanced_get_product_card_media( $product );
$has_secondary_image = ! empty( $card_media['secondary_url'] );
$primary_category    = enhanced_get_product_primary_category_name( $product_id );
$on_sale             = $product->is_on_sale();
$date_created        = $product->get_date_created();
$is_new              = $date_created && ( time() - $date_created->getTimestamp() ) < ( 21 * DAY_IN_SECONDS );
$avg_rating          = (float) $product->get_average_rating();
$rating_cnt          = (int) $product->get_rating_count();
$price_html          = $product->get_price_html();
$stock_badge         = enhanced_get_product_stock_badge( $product );
$sale_badge          = enhanced_get_product_sale_badge( $product );
?>

<div class="product-card<?php echo $has_secondary_image ? ' product-card--has-secondary' : ''; ?>">
	<div class="product-card__media">
		<img
			class="product-card__image product-card__image--primary"
			src="<?php echo esc_url( $card_media['primary_url'] ); ?>"
			alt="<?php echo esc_attr( $card_media['alt'] ); ?>"
			loading="lazy"
		>

		<?php if ( $has_secondary_image ) : ?>
			<img
				class="product-card__image product-card__image--secondary"
				src="<?php echo esc_url( $card_media['secondary_url'] ); ?>"
				alt="<?php echo esc_attr( $card_media['alt'] ); ?>"
				loading="lazy"
			>
		<?php endif; ?>

		<div class="product-card__badges">
			<?php if ( $sale_badge ) : ?>
				<span class="product-card__badge product-card__badge--sale"><?php echo esc_html( $sale_badge ); ?></span>
			<?php elseif ( $on_sale ) : ?>
				<span class="product-card__badge product-card__badge--sale"><?php esc_html_e( 'Sale', 'enhanced' ); ?></span>
			<?php endif; ?>

			<?php if ( $is_new ) : ?>
				<span class="product-card__badge product-card__badge--new"><?php esc_html_e( 'New', 'enhanced' ); ?></span>
			<?php endif; ?>
		</div>

		<div class="product-card__actions">
			<?php woocommerce_template_loop_add_to_cart(); ?>
		</div>
	</div>

	<div class="product-card__body">
		<?php if ( $primary_category ) : ?>
			<span class="product-card__eyebrow"><?php echo esc_html( $primary_category ); ?></span>
		<?php endif; ?>

		<h3 class="product-card__name">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product_name ); ?></a>
		</h3>

		<?php if ( $avg_rating > 0 ) : ?>
			<div class="product-card__rating">
				<div class="product-card__stars">
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<?php $filled = $i <= round( $avg_rating ); ?>
						<svg viewBox="0 0 12 12" fill="<?php echo $filled ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="1" aria-hidden="true">
							<path d="M6 1l1.35 2.73L10.5 4.3l-2.25 2.2.53 3.1L6 8.1l-2.78 1.5.53-3.1L1.5 4.3l3.15-.57L6 1z"/>
						</svg>
					<?php endfor; ?>
				</div>
				<?php if ( $rating_cnt ) : ?>
					<span class="product-card__rating-count">
						<?php
						printf(
							/* translators: %d number of reviews */
							esc_html( _n( '%d review', '%d reviews', $rating_cnt, 'enhanced' ) ),
							$rating_cnt
						);
						?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="product-card__footer">
			<div class="product-card__price-wrap">
				<?php if ( $price_html ) : ?>
					<span class="product-card__price"><?php echo wp_kses_post( $price_html ); ?></span>
				<?php endif; ?>
			</div>

			<span class="product-card__stock product-card__stock--<?php echo esc_attr( $stock_badge['class'] ); ?>">
				<?php echo esc_html( $stock_badge['label'] ); ?>
			</span>
		</div>
	</div>
</div>
