<?php
/**
 * Front page arrival showcase.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="arrival-showcase" style="--arrival-reference-image: url('<?php echo esc_url( $reference_visual_url ); ?>');">
	<div class="container">
		<div class="arrival-showcase__layout" data-arrival-showcase>
			<div class="arrival-showcase__stage">
				<?php foreach ( $showcase_items as $index => $showcase_product ) : ?>
					<?php
					$showcase_image = $showcase_product->get_image_id() ? wp_get_attachment_image_url( $showcase_product->get_image_id(), 'enhanced-hero' ) : wc_placeholder_img_src( 'enhanced-hero' );
					$showcase_cat   = enhanced_get_product_primary_category_name( $showcase_product->get_id() );
					?>
					<article class="arrival-showcase__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-arrival-slide>
						<div class="arrival-showcase__image">
							<img src="<?php echo esc_url( $showcase_image ); ?>" alt="<?php echo esc_attr( $showcase_product->get_name() ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
						</div>

						<div class="arrival-showcase__content">
							<span class="arrival-showcase__eyebrow"><?php esc_html_e( 'New Products', 'enhanced' ); ?></span>
							<h2 class="arrival-showcase__title"><?php echo esc_html( $showcase_product->get_name() ); ?></h2>
							<div class="arrival-showcase__price"><?php echo wp_kses_post( $showcase_product->get_price_html() ); ?></div>
							<?php if ( $showcase_cat ) : ?>
								<p class="arrival-showcase__meta"><?php echo esc_html( $showcase_cat ); ?></p>
							<?php endif; ?>
							<a class="btn btn--accent" href="<?php echo esc_url( get_permalink( $showcase_product->get_id() ) ); ?>"><?php esc_html_e( 'Explore product', 'enhanced' ); ?></a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<?php if ( count( $showcase_items ) > 1 ) : ?>
				<div class="arrival-showcase__thumbs">
					<?php foreach ( $showcase_items as $index => $showcase_product ) : ?>
						<?php
						$thumb_image = $showcase_product->get_image_id() ? wp_get_attachment_image_url( $showcase_product->get_image_id(), 'enhanced-thumb' ) : wc_placeholder_img_src( 'enhanced-thumb' );
						?>
						<button class="arrival-showcase__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-arrival-thumb aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
							<img src="<?php echo esc_url( $thumb_image ); ?>" alt="<?php echo esc_attr( $showcase_product->get_name() ); ?>" loading="lazy">
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
