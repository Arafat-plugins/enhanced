<?php
/**
 * Single product related rail.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="product-related">
	<div class="home-rail home-rail--related home-rail--columns-<?php echo esc_attr( $related_columns ); ?>" data-rail-slider data-rail-autoplay="<?php echo esc_attr( $related_autoplay ); ?>">
		<div class="home-rail__head">
			<div class="home-rail__title-wrap">
				<?php if ( $related_eyebrow ) : ?>
					<span class="home-rail__eyebrow"><?php echo esc_html( $related_eyebrow ); ?></span>
				<?php endif; ?>

				<?php if ( $related_title ) : ?>
					<h2 class="home-rail__title"><?php echo esc_html( $related_title ); ?></h2>
				<?php endif; ?>
			</div>

			<div class="home-rail__actions">
				<div class="home-rail__nav">
					<button class="home-rail__btn" type="button" data-rail-prev aria-label="<?php esc_attr_e( 'Previous related products', 'enhanced' ); ?>">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M9.5 3L5 8l4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
					<button class="home-rail__btn" type="button" data-rail-next aria-label="<?php esc_attr_e( 'Next related products', 'enhanced' ); ?>">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6.5 3L11 8l-4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
				</div>
			</div>
		</div>

		<div class="home-rail__track home-rail__track--products" data-rail-track>
			<?php foreach ( $related_products as $related_product ) : ?>
				<?php enhanced_render_product_card( $related_product ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
