<?php
/**
 * Category rail — Luxina category tiles.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
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

</section>
