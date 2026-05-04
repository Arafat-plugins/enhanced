<?php
/**
 * Front page product rail.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$marquee_direction = isset( $marquee_direction ) ? sanitize_key( $marquee_direction ) : '';
$is_marquee        = in_array( $marquee_direction, array( 'left', 'right' ), true ) && ! empty( $products );
$is_arrivals_style = false !== strpos( (string) ( $section_classes ?? '' ), 'lx-product-rail--arrivals' );
$is_slider         = $is_arrivals_style && ! $is_marquee;
$rail_autoplay     = isset( $rail_autoplay ) ? max( 0, (int) $rail_autoplay ) : 0;
$rail_step         = isset( $rail_step ) ? min( 4, max( 1, (int) $rail_step ) ) : 1;
$rail_loop         = isset( $rail_loop ) ? (bool) $rail_loop : true;
$rail_pause_hover  = isset( $rail_pause_hover ) ? (bool) $rail_pause_hover : true;
$rail_animation    = isset( $rail_animation ) ? enhanced_sanitize_rp_animation( $rail_animation ) : 'slide-left';
$rail_animation_duration = isset( $rail_animation_duration ) ? min( 3000, max( 200, (int) $rail_animation_duration ) ) : 700;
$rail_animation_easing = isset( $rail_animation_easing ) ? enhanced_sanitize_rp_animation_easing( $rail_animation_easing ) : 'smooth';
$rail_cols_desktop = isset( $rail_cols_desktop ) ? min( 5, max( 2, (int) $rail_cols_desktop ) ) : 4;
$rail_cols_tablet  = isset( $rail_cols_tablet ) ? min( 3, max( 1, (int) $rail_cols_tablet ) ) : 2;
$rail_cols_mobile  = isset( $rail_cols_mobile ) ? min( 2, max( 1, (int) $rail_cols_mobile ) ) : 1;
$section_class     = trim( 'lx-product-rail fp-reveal ' . ( $section_classes ?? '' ) . ( $is_marquee ? ' lx-product-rail--marquee lx-product-rail--marquee-' . $marquee_direction : '' ) );
$rail_products     = $products;

if ( $is_marquee ) {
	while ( count( $rail_products ) < 8 ) {
		$rail_products = array_merge( $rail_products, $products );
	}
	$rail_products = array_slice( $rail_products, 0, 8 );
}

$render_product_card = static function ( $product_item, $i, $is_duplicate = false ) use ( $is_arrivals_style ) {
	$img_id  = $product_item->get_image_id();
	$img_url = $img_id
		? wp_get_attachment_image_url( $img_id, 'enhanced-card' )
		: ( enhanced_is_woo() ? wc_placeholder_img_src( 'enhanced-card' ) : '' );
	$is_sale = $product_item->is_on_sale();
	$created = $product_item->get_date_created();
	$is_new  = $created && ( time() - $created->getTimestamp() ) < ( 30 * DAY_IN_SECONDS );
	$link_attrs = $is_duplicate ? ' tabindex="-1" aria-hidden="true"' : '';

	if ( $is_arrivals_style ) {
		$sale_badge   = function_exists( 'enhanced_get_product_sale_badge' ) ? enhanced_get_product_sale_badge( $product_item ) : '';
		$card_options = function_exists( 'enhanced_get_product_card_attribute_options' )
			? enhanced_get_product_card_attribute_options( $product_item )
			: array( 'colors' => array(), 'sizes' => array() );
		$card_colors  = array_slice( $card_options['colors'], 0, 3 );
		$card_sizes   = array_slice( $card_options['sizes'], 0, 4 );
		$product_id   = $product_item->get_id();
		?>
		<article class="lx-prod-card lx-prod-card--arrival fp-reveal fp-reveal--delay-<?php echo esc_attr( min( ( $i % 4 ) + 1, 4 ) ); ?>">
			<a class="lx-prod-card__img lx-prod-card__img--arrival"
				href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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

				<?php if ( $sale_badge || $is_sale || $is_new ) : ?>
					<span class="lx-badge lx-badge--arrival<?php echo $is_sale ? ' lx-badge--sale' : ' lx-badge--new'; ?>">
						<?php
						echo esc_html(
							$sale_badge
								? $sale_badge
								: ( $is_sale ? __( 'Sale', 'enhanced' ) : __( 'New', 'enhanced' ) )
						);
						?>
					</span>
				<?php endif; ?>

				<?php if ( ! $is_duplicate ) : ?>
					<button
						class="lx-prod-card__wishlist"
						type="button"
						data-wishlist-toggle
						data-product-id="<?php echo esc_attr( $product_id ); ?>"
						aria-pressed="false"
						aria-label="<?php esc_attr_e( 'Add to wishlist', 'enhanced' ); ?>"
					>
						<span class="product-details__wishlist-icon" aria-hidden="true">&#9825;</span>
						<span class="screen-reader-text" data-wishlist-label><?php esc_html_e( 'Add to wishlist', 'enhanced' ); ?></span>
					</button>
				<?php endif; ?>

				<?php if ( ! empty( $card_colors ) ) : ?>
					<div class="lx-prod-card__swatches" aria-hidden="true">
						<?php foreach ( $card_colors as $color ) : ?>
							<span
								class="lx-prod-card__swatch<?php echo enhanced_is_light_hex_color( $color['color'] ) ? ' lx-prod-card__swatch--light' : ''; ?>"
								style="--card-swatch: <?php echo esc_attr( $color['color'] ); ?>;"
							></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</a>
			<div class="lx-prod-card__body lx-prod-card__body--arrival">
				<h3 class="lx-prod-card__name lx-prod-card__name--arrival">
					<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
						<?php echo esc_html( $product_item->get_name() ); ?>
					</a>
				</h3>
				<div class="lx-prod-card__price lx-prod-card__price--arrival">
					<?php echo wp_kses_post( $product_item->get_price_html() ); ?>
				</div>
				<?php if ( ! empty( $card_sizes ) ) : ?>
					<div class="lx-prod-card__meta">
						<span class="lx-prod-card__meta-label"><?php esc_html_e( 'Sizes', 'enhanced' ); ?></span>
						<div class="lx-prod-card__sizes" aria-label="<?php esc_attr_e( 'Available sizes', 'enhanced' ); ?>">
							<?php foreach ( $card_sizes as $size ) : ?>
								<span class="lx-prod-card__size"><?php echo esc_html( $size ); ?></span>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</article>
		<?php
		return;
	}
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
	<div class="container<?php echo $is_slider ? ' lx-product-rail__slider' : ''; ?>"
		<?php
		if ( $is_slider ) {
			echo ' data-rail-slider';
			echo ' data-rail-autoplay="' . esc_attr( $rail_autoplay ) . '"';
			echo ' data-rail-step="' . esc_attr( $rail_step ) . '"';
			echo ' data-rail-loop="' . esc_attr( $rail_loop ? '1' : '0' ) . '"';
			echo ' data-rail-pause-hover="' . esc_attr( $rail_pause_hover ? '1' : '0' ) . '"';
			echo ' data-rail-animation="' . esc_attr( $rail_animation ) . '"';
			echo ' data-rail-animation-duration="' . esc_attr( $rail_animation_duration ) . '"';
			echo ' data-rail-animation-easing="' . esc_attr( $rail_animation_easing ) . '"';
			echo ' style="--lx-arrivals-cols-desktop:' . esc_attr( $rail_cols_desktop ) . ';--lx-arrivals-cols-tablet:' . esc_attr( $rail_cols_tablet ) . ';--lx-arrivals-cols-mobile:' . esc_attr( $rail_cols_mobile ) . ';"';
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>>
		<div class="lx-sec-head">
			<div>
				<?php if ( ! empty( $eyebrow ) ) : ?>
					<p class="lx-sec-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="lx-sec-title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( ! empty( $tabs ) && is_array( $tabs ) ) : ?>
					<nav class="lx-product-tabs" aria-label="<?php echo esc_attr( $title ); ?>">
						<?php foreach ( $tabs as $tab ) :
							$tab_url = ( ! empty( $tab['url'] ) && ! is_wp_error( $tab['url'] ) ) ? $tab['url'] : $shop_url;
							?>
							<a class="lx-product-tab<?php echo ! empty( $tab['active'] ) ? ' is-active' : ''; ?>"
								href="<?php echo esc_url( $tab_url ); ?>">
								<?php echo esc_html( $tab['label'] ?? '' ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
			</div>
			<div class="lx-product-rail__actions">
				<a class="lx-sec-link" href="<?php echo esc_url( $link_url ); ?>">
					<?php echo esc_html( $link_label ); ?>
				</a>
				<?php if ( ! $is_marquee ) : ?>
					<div class="lx-nav-arrows">
						<button class="lx-nav-btn" type="button" data-lx-rail-prev<?php echo $is_slider ? ' data-rail-prev' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							aria-label="<?php echo esc_attr( $prev_label ); ?>">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
								<path d="M8.5 2.5L4 7l4.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
						<button class="lx-nav-btn" type="button" data-lx-rail-next<?php echo $is_slider ? ' data-rail-next' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
			<div class="lx-rail-track" data-lx-rail<?php echo $is_slider ? ' data-rail-track' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php foreach ( $products as $i => $product_item ) : ?>
					<?php $render_product_card( $product_item, $i ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
