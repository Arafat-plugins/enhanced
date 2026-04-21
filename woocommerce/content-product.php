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
$permalink           = get_permalink( $product_id );
$image_id            = $product->get_image_id();
$image_url           = $image_id ? wp_get_attachment_image_url( $image_id, 'enhanced-card' ) : wc_placeholder_img_src( 'enhanced-card' );
$secondary_image_url = enhanced_get_product_secondary_image_url( $product );
$image_alt           = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
$primary_category    = enhanced_get_product_primary_category_name( $product_id );
$on_sale             = $product->is_on_sale();
$date_created        = $product->get_date_created();
$is_new              = $date_created && ( time() - $date_created->getTimestamp() ) < ( 21 * DAY_IN_SECONDS );
$avg_rating          = (float) $product->get_average_rating();
$rating_cnt          = (int) $product->get_rating_count();
$price_html          = $product->get_price_html();
$stock_badge         = enhanced_get_product_stock_badge( $product );
$sale_badge          = '';

if ( $on_sale && $product->get_regular_price() && $product->get_sale_price() ) {
	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();

	if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
		$percentage = (int) round( ( ( $regular - $sale ) / $regular ) * 100 );

		if ( $percentage > 0 ) {
			$sale_badge = sprintf(
				/* translators: %d percentage off */
				__( '-%d%%', 'enhanced' ),
				$percentage
			);
		}
	}
}
?>

<div class="product-card">
	<div class="product-card__media">
		<img
			class="product-card__image product-card__image--primary"
			src="<?php echo esc_url( $image_url ); ?>"
			alt="<?php echo esc_attr( $image_alt ?: $product->get_name() ); ?>"
			loading="lazy"
		>

		<?php if ( $secondary_image_url ) : ?>
			<img
				class="product-card__image product-card__image--secondary"
				src="<?php echo esc_url( $secondary_image_url ); ?>"
				alt="<?php echo esc_attr( $image_alt ?: $product->get_name() ); ?>"
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
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
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
