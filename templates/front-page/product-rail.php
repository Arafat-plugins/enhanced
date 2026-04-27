<?php
/**
 * Front page product rail.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$marquee_direction = isset( $marquee_direction ) ? sanitize_key( $marquee_direction ) : '';
$is_marquee        = in_array( $marquee_direction, array( 'left', 'right' ), true ) && ! empty( $products );
$section_class     = trim( 'lx-product-rail fp-reveal ' . ( $section_classes ?? '' ) . ( $is_marquee ? ' lx-product-rail--marquee lx-product-rail--marquee-' . $marquee_direction : '' ) );
$rail_products     = $products;

if ( $is_marquee ) {
	while ( count( $rail_products ) < 8 ) {
		$rail_products = array_merge( $rail_products, $products );
	}
	$rail_products = array_slice( $rail_products, 0, 8 );
}

$render_product_card = static function ( $product_item, $i, $is_duplicate = false ) {
	$img_id  = $product_item->get_image_id();
	$img_url = $img_id
		? wp_get_attachment_image_url( $img_id, 'enhanced-card' )
		: ( enhanced_is_woo() ? wc_placeholder_img_src( 'enhanced-card' ) : '' );
	$is_sale = $product_item->is_on_sale();
	$created = $product_item->get_date_created();
	$is_new  = $created && ( time() - $created->getTimestamp() ) < ( 30 * DAY_IN_SECONDS );
	$link_attrs = $is_duplicate ? ' tabindex="-1" aria-hidden="true"' : '';
	?>
	<a class="lx-prod-card fp-reveal fp-reveal--delay-<?php echo esc_attr( min( ( $i % 4 ) + 1, 4 ) ); ?>"
		href="<?php echo esc_url( get_permalink( $product_item->get_id() ) ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lx-prod-card__img">
			<?php if ( $img_url ) : ?>
				<img src="<?php echo esc_url( $img_url ); ?>"
					alt="<?php echo esc_attr( $product_item->get_name() ); ?>"
					loading="<?php echo 0 === $i && ! $is_duplicate ? 'eager' : 'lazy'; ?>">
			<?php else : ?>
				<div class="lx-prod-card__fallback">
					<?php echo esc_html( $product_item->get_name() ); ?><br>
					<small><?php esc_html_e( '[product image]', 'enhanced' ); ?></small>
				</div>
			<?php endif; ?>

			<?php if ( $is_sale ) :
				$reg  = (float) $product_item->get_regular_price();
				$sale = (float) $product_item->get_sale_price();
				$pct  = ( $reg > 0 && $sale < $reg ) ? round( ( $reg - $sale ) / $reg * 100 ) : 0;
				?>
				<span class="lx-badge lx-badge--sale">
					<?php echo $pct ? esc_html( $pct . '% ' . __( 'Off', 'enhanced' ) ) : esc_html__( 'Sale', 'enhanced' ); ?>
				</span>
			<?php elseif ( $is_new ) : ?>
				<span class="lx-badge lx-badge--new"><?php esc_html_e( 'New', 'enhanced' ); ?></span>
			<?php endif; ?>
		</div>
		<div class="lx-prod-card__body">
			<h3 class="lx-prod-card__name">
				<?php echo esc_html( $product_item->get_name() ); ?>
			</h3>
			<div class="lx-prod-card__price">
				<?php echo wp_kses_post( $product_item->get_price_html() ); ?>
			</div>
		</div>
	</a>
	<?php
};
?>

<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="container">
		<div class="lx-sec-head">
			<div>
				<?php if ( ! empty( $eyebrow ) ) : ?>
					<p class="lx-sec-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="lx-sec-title"><?php echo esc_html( $title ); ?></h2>
			</div>
			<div class="lx-product-rail__actions">
				<a class="lx-sec-link" href="<?php echo esc_url( $link_url ); ?>">
					<?php echo esc_html( $link_label ); ?>
				</a>
				<?php if ( ! $is_marquee ) : ?>
					<div class="lx-nav-arrows">
						<button class="lx-nav-btn" type="button" data-lx-rail-prev
							aria-label="<?php echo esc_attr( $prev_label ); ?>">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
								<path d="M8.5 2.5L4 7l4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
						<button class="lx-nav-btn" type="button" data-lx-rail-next
							aria-label="<?php echo esc_attr( $next_label ); ?>">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
								<path d="M5.5 2.5L10 7l-4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $is_marquee ) : ?>
			<div class="lx-rail-marquee" aria-label="<?php echo esc_attr( $title ); ?>">
				<div class="lx-rail-marquee__inner">
					<div class="lx-rail-marquee__group">
						<?php foreach ( $rail_products as $i => $product_item ) : ?>
							<?php $render_product_card( $product_item, $i ); ?>
						<?php endforeach; ?>
					</div>
					<div class="lx-rail-marquee__group" aria-hidden="true">
						<?php foreach ( $rail_products as $i => $product_item ) : ?>
							<?php $render_product_card( $product_item, $i, true ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="lx-rail-track" data-lx-rail>
				<?php foreach ( $products as $i => $product_item ) : ?>
					<?php $render_product_card( $product_item, $i ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
