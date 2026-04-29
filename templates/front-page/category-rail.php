<?php
/**
 * Category rail — Luxina category tiles.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$render_category_tile = static function ( $term, $index ) {
	$media      = enhanced_get_term_card_media( $term, 'enhanced-card' );
	$link       = get_term_link( $term );

	if ( is_wp_error( $link ) ) {
		return;
	}
	?>
	<a class="lx-cat-tile"
		href="<?php echo esc_url( $link ); ?>">
		<div class="lx-cat-tile__img">
			<?php if ( ! empty( $media['image_id'] ) ) : ?>
				<?php
				echo wp_get_attachment_image(
					(int) $media['image_id'],
					'large',
					false,
					array(
						'alt'      => $media['alt'],
						'loading'  => 0 === $index ? 'eager' : 'lazy',
						'decoding' => 'async',
						'sizes'    => '(max-width: 640px) 100vw, (max-width: 960px) 50vw, (max-width: 1280px) 33vw, 25vw',
					)
				);
				?>
			<?php elseif ( ! empty( $media['image_url'] ) ) : ?>
				<img src="<?php echo esc_url( $media['image_url'] ); ?>"
					alt="<?php echo esc_attr( $media['alt'] ); ?>"
					loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
					decoding="async">
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
	<?php
};
?>

<section class="lx-browse-cats fp-reveal">
	<div class="lx-browse-cats__shell">

		<div class="lx-cat-grid" aria-label="<?php esc_attr_e( 'Shop by category', 'enhanced' ); ?>">
			<?php foreach ( $categories as $i => $term ) : ?>
				<?php $render_category_tile( $term, $i ); ?>
			<?php endforeach; ?>
		</div>

	</div><!-- .lx-browse-cats__shell -->

</section>
