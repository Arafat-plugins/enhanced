<?php
/**
 * Category rail — Luxina: tiles row + browse split below.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$browse_img_url = enhanced_get_image_option_url( 'browse_model_image', 'enhanced-hero' );
?>

<section class="lx-browse-cats fp-reveal">
	<div class="container">

		<div class="lx-sec-head">
			<h2 class="lx-sec-title"><?php esc_html_e( 'Browse Categories', 'enhanced' ); ?></h2>
			<div class="lx-cat-gender-tabs">
				<a class="lx-cat-gender-tab" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Men', 'enhanced' ); ?></a>
				<a class="lx-cat-gender-tab" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Women', 'enhanced' ); ?></a>
			</div>
		</div>

		<!-- Category tiles -->
		<div class="lx-cat-tiles" data-lx-cat-track data-lx-cat-paginate="5">
			<?php foreach ( $categories as $i => $term ) :
				$thumb_id  = get_term_meta( $term->term_id, 'thumbnail_id', true );
				$thumb_url = $thumb_id ? wp_get_attachment_image_url( (int) $thumb_id, 'enhanced-card' ) : '';
			?>
				<a class="lx-cat-tile fp-reveal fp-reveal--delay-<?php echo min( $i + 1, 4 ); ?>"
					href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<div class="lx-cat-tile__img">
						<?php if ( $thumb_url ) : ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>"
								alt="<?php echo esc_attr( $term->name ); ?>"
								loading="lazy">
						<?php else : ?>
							<div class="lx-cat-tile__fallback">
								<?php echo esc_html( $term->name ); ?>
							</div>
						<?php endif; ?>
						<div class="lx-cat-tile__bar"></div>
					</div>
					<div class="lx-cat-tile__label">
						<?php echo esc_html( $term->name ); ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="lx-cat-pagination" aria-label="<?php esc_attr_e( 'Category pages', 'enhanced' ); ?>"></div>

	</div><!-- .container -->

	<!-- Browse split: text CTA (left) + model image (right) -->
	<div class="lx-browse-split">
		<div class="lx-browse-split__text">
			<p class="lx-browse-cta__eyebrow"><?php esc_html_e( 'our categories match by your taste', 'enhanced' ); ?></p>
			<h3 class="lx-browse-cta__title"><?php esc_html_e( 'Browse Categories', 'enhanced' ); ?></h3>
			<a class="lx-btn lx-btn--black" href="<?php echo esc_url( $shop_url ); ?>">
				<?php esc_html_e( 'Check It Out', 'enhanced' ); ?>
				<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</a>
		</div>
		<div class="lx-browse-split__image">
			<?php if ( $browse_img_url ) : ?>
				<img src="<?php echo esc_url( $browse_img_url ); ?>" alt="" loading="lazy">
			<?php else : ?>
				<div class="lx-browse-split__fallback">
					<?php esc_html_e( 'Upload browse image in Theme Settings', 'enhanced' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

</section>
