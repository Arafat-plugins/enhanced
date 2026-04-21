<?php
/**
 * Front page product rail.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="<?php echo esc_attr( $section_classes ); ?>">
	<div class="container">
		<div class="home-rail" data-rail-slider>
			<div class="home-rail__head">
				<div class="home-rail__title-wrap">
					<span class="home-rail__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
					<h2 class="home-rail__title"><?php echo esc_html( $title ); ?></h2>
				</div>
				<div class="home-rail__actions">
					<a class="home-rail__link" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_label ); ?></a>
					<div class="home-rail__nav">
						<button class="home-rail__btn" type="button" data-rail-prev aria-label="<?php echo esc_attr( $prev_label ); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M9.5 3L5 8l4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
						<button class="home-rail__btn" type="button" data-rail-next aria-label="<?php echo esc_attr( $next_label ); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6.5 3L11 8l-4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>
				</div>
			</div>

			<div class="home-rail__track home-rail__track--products" data-rail-track>
				<?php foreach ( $products as $product_item ) : ?>
					<?php enhanced_render_product_card( $product_item ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
