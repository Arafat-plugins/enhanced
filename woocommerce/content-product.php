<?php
/**
 * Product card — <div> wrapper with stretched-link pattern.
 * No nested <a> tags — fully valid HTML.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->is_visible() ) {
	return;
}

$permalink  = get_permalink( $product->get_id() );
$image_id   = $product->get_image_id();
$image_url  = $image_id
	? wp_get_attachment_image_url( $image_id, 'enhanced-card' )
	: wc_placeholder_img_src( 'enhanced-card' );
$image_alt  = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
$on_sale    = $product->is_on_sale();
$is_new     = ( time() - strtotime( $product->get_date_created() ) ) < ( 21 * DAY_IN_SECONDS );
$avg_rating = (float) $product->get_average_rating();
$rating_cnt = (int) $product->get_rating_count();
$reg_price  = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$price_html = $product->get_price_html();

// Strip links from category list — a naked <a> inside the card breaks HTML.
$cats = wp_strip_all_tags( wc_get_product_category_list( $product->get_id(), ', ' ) );
?>

<div class="product-card">

	<div class="product-card__media">
		<img
			src="<?php echo esc_url( $image_url ); ?>"
			alt="<?php echo esc_attr( $image_alt ?: $product->get_name() ); ?>"
			loading="lazy"
		>
		<div class="product-card__badges">
			<?php if ( $on_sale ) : ?>
				<span class="product-card__badge product-card__badge--sale"><?php esc_html_e( 'Sale', 'enhanced' ); ?></span>
			<?php elseif ( $is_new ) : ?>
				<span class="product-card__badge product-card__badge--new"><?php esc_html_e( 'New', 'enhanced' ); ?></span>
			<?php endif; ?>
		</div>
	</div>

	<div class="product-card__body">

		<?php if ( $avg_rating > 0 ) : ?>
		<div class="product-card__rating">
			<div class="product-card__stars">
				<?php for ( $i = 1; $i <= 5; $i++ ) :
					$filled = $i <= round( $avg_rating ); ?>
					<svg viewBox="0 0 12 12" fill="<?php echo $filled ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="1">
						<path d="M6 1l1.35 2.73L10.5 4.3l-2.25 2.2.53 3.1L6 8.1l-2.78 1.5.53-3.1L1.5 4.3l3.15-.57L6 1z"/>
					</svg>
				<?php endfor; ?>
			</div>
			<?php if ( $rating_cnt ) : ?>
				<span class="product-card__rating-count">(<?php echo esc_html( $rating_cnt ); ?>)</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="product-card__price-wrap">
			<?php if ( $price_html ) : ?>
				<span class="product-card__price"><?php echo wp_kses_post( $price_html ); ?></span>
			<?php endif; ?>
		</div>

		<h3 class="product-card__name">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		</h3>

		<?php if ( $cats ) : ?>
			<span class="product-card__brand"><?php echo esc_html( $cats ); ?></span>
		<?php endif; ?>

	</div>
</div>
