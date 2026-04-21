<?php
/**
 * Front page category rail.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="home-rail-section">
	<div class="container">
		<div class="home-rail" data-rail-slider>
			<div class="home-rail__head">
				<div class="home-rail__title-wrap">
					<span class="home-rail__eyebrow"><?php esc_html_e( 'Browse', 'enhanced' ); ?></span>
					<h2 class="home-rail__title"><?php esc_html_e( 'Shop by Category', 'enhanced' ); ?></h2>
				</div>
				<div class="home-rail__actions">
					<a class="home-rail__link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'View all', 'enhanced' ); ?></a>
					<div class="home-rail__nav">
						<button class="home-rail__btn" type="button" data-rail-prev aria-label="<?php esc_attr_e( 'Previous items', 'enhanced' ); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M9.5 3L5 8l4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
						<button class="home-rail__btn" type="button" data-rail-next aria-label="<?php esc_attr_e( 'Next items', 'enhanced' ); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6.5 3L11 8l-4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>
				</div>
			</div>

			<div class="home-rail__track home-rail__track--categories" data-rail-track>
				<?php foreach ( $categories as $term ) : ?>
					<?php
					$thumb_id  = get_term_meta( $term->term_id, 'thumbnail_id', true );
					$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'enhanced-card' ) : '';
					?>
					<a class="home-category-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
						<div class="home-category-card__media">
							<?php if ( $thumb_url ) : ?>
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy">
							<?php endif; ?>
						</div>
						<div class="home-category-card__body">
							<strong><?php echo esc_html( $term->name ); ?></strong>
							<span>
								<?php
								printf(
									esc_html( _n( '%d item', '%d items', $term->count, 'enhanced' ) ),
									$term->count
								);
								?>
							</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
