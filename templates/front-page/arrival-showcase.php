<?php
/**
 * Front page arrival showcase — cinematic dark editorial slider.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="fp-showcase">
	<div class="fp-showcase__inner" data-fp-showcase>

		<!-- Left: image slides -->
		<div class="fp-showcase__stage">
			<?php foreach ( $showcase_items as $i => $product ) :
				$img_url = $product->get_image_id()
					? wp_get_attachment_image_url( $product->get_image_id(), 'enhanced-hero' )
					: '';
				$cat = enhanced_get_product_primary_category_name( $product->get_id() );
			?>
				<article class="fp-showcase__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
					data-fp-showcase-slide>
					<?php if ( $img_url ) : ?>
						<img class="fp-showcase__slide-img"
							src="<?php echo esc_url( $img_url ); ?>"
							alt="<?php echo esc_attr( $product->get_name() ); ?>"
							loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>">
					<?php else : ?>
						<div class="fp-showcase__slide-img-fallback">
							<?php echo esc_html( $product->get_name() ); ?><br>
							<small><?php esc_html_e( '[product image]', 'enhanced' ); ?></small>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

		<!-- Right: content -->
		<div class="fp-showcase__content-col">

			<!-- Slide counter -->
			<div class="fp-showcase__counter" data-fp-showcase-counter aria-live="polite">
				<strong>01</strong> / <?php echo str_pad( count( $showcase_items ), 2, '0', STR_PAD_LEFT ); ?>
			</div>

			<!-- Content for each slide -->
			<?php foreach ( $showcase_items as $i => $product ) :
				$cat = enhanced_get_product_primary_category_name( $product->get_id() );
				$is_on_sale = $product->is_on_sale();
			?>
				<div class="fp-showcase__content<?php echo 0 === $i ? ' is-active' : ''; ?>"
					data-fp-showcase-content>

					<p class="fp-showcase__eyebrow">
						<?php echo $cat ? esc_html( $cat ) : esc_html__( 'New Arrival', 'enhanced' ); ?>
					</p>

					<h2 class="fp-showcase__title"><?php echo esc_html( $product->get_name() ); ?></h2>

					<div class="fp-showcase__price">
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					</div>

					<?php if ( $is_on_sale ) : ?>
						<p class="fp-showcase__meta"><?php esc_html_e( 'On Sale — Limited Stock', 'enhanced' ); ?></p>
					<?php elseif ( $product->is_featured() ) : ?>
						<p class="fp-showcase__meta"><?php esc_html_e( 'Featured Pick', 'enhanced' ); ?></p>
					<?php endif; ?>

					<a class="btn btn--accent"
						href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
						<?php esc_html_e( 'Explore Product', 'enhanced' ); ?>
					</a>

				</div>
			<?php endforeach; ?>

			<!-- Thumb nav -->
			<?php if ( count( $showcase_items ) > 1 ) : ?>
				<div class="fp-showcase__thumbs">

					<button class="fp-swiper-btn fp-swiper-btn--light"
						type="button" data-fp-showcase-prev
						aria-label="<?php esc_attr_e( 'Previous', 'enhanced' ); ?>">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
							<path d="M9.5 3L5 8l4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>

					<?php foreach ( $showcase_items as $i => $product ) :
						$thumb = $product->get_image_id()
							? wp_get_attachment_image_url( $product->get_image_id(), 'enhanced-thumb' )
							: '';
					?>
						<button class="fp-showcase__thumb<?php echo 0 === $i ? ' is-active' : ''; ?>"
							type="button" data-fp-showcase-thumb
							aria-pressed="<?php echo 0 === $i ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
							<?php if ( $thumb ) : ?>
								<img src="<?php echo esc_url( $thumb ); ?>"
									alt="<?php echo esc_attr( $product->get_name() ); ?>"
									loading="lazy">
							<?php else : ?>
								<div class="fp-showcase__thumb-fallback"><?php echo esc_html( substr( $product->get_name(), 0, 2 ) ); ?></div>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>

					<button class="fp-swiper-btn fp-swiper-btn--light"
						type="button" data-fp-showcase-next
						aria-label="<?php esc_attr_e( 'Next', 'enhanced' ); ?>">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
							<path d="M6.5 3L11 8l-4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>

				</div>
			<?php endif; ?>

		</div><!-- .fp-showcase__content-col -->

	</div><!-- .fp-showcase__inner -->
</section>
