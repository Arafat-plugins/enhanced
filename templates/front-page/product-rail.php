<?php
/**
 * Front page product rail — Luxina style horizontal scroll.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="lx-product-rail fp-reveal <?php echo esc_attr( $section_classes ); ?>">
	<div class="container">
		<div class="lx-sec-head">
			<div>
				<?php if ( ! empty( $eyebrow ) ) : ?>
					<p class="lx-sec-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="lx-sec-title"><?php echo esc_html( $title ); ?></h2>
			</div>
			<div style="display:flex;align-items:center;gap:12px;">
				<a class="lx-sec-link" href="<?php echo esc_url( $link_url ); ?>">
					<?php echo esc_html( $link_label ); ?>
				</a>
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
			</div>
		</div>

		<div class="lx-rail-track" data-lx-rail>
			<?php foreach ( $products as $i => $product_item ) :
				$img_id  = $product_item->get_image_id();
				$img_url = $img_id
					? wp_get_attachment_image_url( $img_id, 'enhanced-card' )
					: ( enhanced_is_woo() ? wc_placeholder_img_src( 'enhanced-card' ) : '' );
				$is_sale = $product_item->is_on_sale();
				$is_new  = ( time() - $product_item->get_date_created()->getTimestamp() ) < ( 30 * DAY_IN_SECONDS );
				$cats    = get_the_terms( $product_item->get_id(), 'product_cat' );
				$cat     = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
			?>
				<div class="lx-prod-card fp-reveal fp-reveal--delay-<?php echo min( ( $i % 4 ) + 1, 4 ); ?>">
					<div class="lx-prod-card__img">
						<?php if ( $img_url ) : ?>
							<img src="<?php echo esc_url( $img_url ); ?>"
								alt="<?php echo esc_attr( $product_item->get_name() ); ?>"
								loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>">
						<?php else : ?>
							<div class="lx-prod-card__fallback">
								<?php echo esc_html( $product_item->get_name() ); ?><br>
								<small><?php esc_html_e( '[product image]', 'enhanced' ); ?></small>
							</div>
						<?php endif; ?>

						<?php if ( $is_sale ) :
							$reg   = (float) $product_item->get_regular_price();
							$sale  = (float) $product_item->get_sale_price();
							$pct   = ( $reg > 0 && $sale < $reg ) ? round( ( $reg - $sale ) / $reg * 100 ) : 0;
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
							<a href="<?php echo esc_url( get_permalink( $product_item->get_id() ) ); ?>">
								<?php echo esc_html( $product_item->get_name() ); ?>
							</a>
						</h3>
						<div class="lx-prod-card__price">
							<?php echo wp_kses_post( $product_item->get_price_html() ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
