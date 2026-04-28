<?php
/**
 * Product card template for shop archives.
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
$price_html          = $product->get_price_html();
$sale_badge          = enhanced_get_product_sale_badge( $product );
$card_options        = function_exists( 'enhanced_get_product_card_attribute_options' )
	? enhanced_get_product_card_attribute_options( $product )
	: array( 'colors' => array(), 'sizes' => array() );
$card_colors         = array_slice( $card_options['colors'], 0, 4 );
$card_sizes          = array_slice( $card_options['sizes'], 0, 3 );
$average_rating      = (float) $product->get_average_rating();
$rating_display      = $average_rating > 0 ? number_format_i18n( $average_rating, 1 ) : '';
$add_to_cart_classes = array_filter(
	array(
		'button',
		'product-card__buy',
		'product_type_' . $product->get_type(),
		$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
		$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
	)
);
?>

<article class="product-card<?php echo $has_secondary_image ? ' product-card--has-secondary' : ''; ?>">
	<a class="product-card__media" href="<?php echo esc_url( $permalink ); ?>">
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

		<?php if ( $sale_badge || $on_sale || $is_new ) : ?>
			<span class="product-card__badges">
				<?php if ( $sale_badge ) : ?>
					<span class="product-card__badge product-card__badge--sale"><?php echo esc_html( $sale_badge ); ?></span>
				<?php elseif ( $on_sale ) : ?>
					<span class="product-card__badge product-card__badge--sale"><?php esc_html_e( 'Sale', 'enhanced' ); ?></span>
				<?php endif; ?>

				<?php if ( $is_new ) : ?>
					<span class="product-card__badge product-card__badge--new"><?php esc_html_e( 'New', 'enhanced' ); ?></span>
				<?php endif; ?>
			</span>
		<?php endif; ?>
	</a>

	<button
		class="product-card__wishlist"
		type="button"
		data-wishlist-toggle
		data-product-id="<?php echo esc_attr( $product_id ); ?>"
		aria-pressed="false"
		aria-label="<?php esc_attr_e( 'Add to wishlist', 'enhanced' ); ?>"
	>
		<span class="product-details__wishlist-icon" aria-hidden="true">&#9825;</span>
		<span class="screen-reader-text" data-wishlist-label><?php esc_html_e( 'Add to wishlist', 'enhanced' ); ?></span>
	</button>

	<div class="product-card__body">
		<div class="product-card__summary">
			<h3 class="product-card__name">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product_name ); ?></a>
			</h3>

			<?php if ( $price_html ) : ?>
				<div class="product-card__price"><?php echo wp_kses_post( $price_html ); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $card_colors ) || ! empty( $card_sizes ) ) : ?>
			<div class="product-card__options">
				<?php if ( ! empty( $card_colors ) ) : ?>
					<div class="product-card__swatches" aria-label="<?php esc_attr_e( 'Available colors', 'enhanced' ); ?>">
						<?php foreach ( $card_colors as $color ) : ?>
							<span
								class="product-card__swatch<?php echo enhanced_is_light_hex_color( $color['color'] ) ? ' product-card__swatch--light' : ''; ?>"
								style="--card-swatch: <?php echo esc_attr( $color['color'] ); ?>;"
								title="<?php echo esc_attr( $color['label'] ); ?>"
							></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $card_sizes ) ) : ?>
					<div class="product-card__sizes" aria-label="<?php esc_attr_e( 'Available sizes', 'enhanced' ); ?>">
						<?php foreach ( $card_sizes as $size ) : ?>
							<span class="product-card__size"><?php echo esc_html( $size ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="product-card__footer">
			<?php if ( $rating_display ) : ?>
				<span class="product-card__rating" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'enhanced' ), $rating_display ) ); ?>">
					<span aria-hidden="true">&#9734;</span>
					<?php echo esc_html( $rating_display ); ?>
				</span>
			<?php elseif ( $primary_category ) : ?>
				<span class="product-card__category"><?php echo esc_html( $primary_category ); ?></span>
			<?php endif; ?>

			<a
				href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
				data-quantity="1"
				data-product_id="<?php echo esc_attr( $product_id ); ?>"
				data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
				class="<?php echo esc_attr( implode( ' ', $add_to_cart_classes ) ); ?>"
				aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
				rel="nofollow"
			>
				<?php esc_html_e( 'BUY +', 'enhanced' ); ?>
			</a>
		</div>
	</div>
</article>
