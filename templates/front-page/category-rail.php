<?php
/**
 * Category rail — Luxina category tiles.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$marquee_speed_seconds = min( 120, max( 8, (int) enhanced_get_option( 'category_marquee_speed', 30 ) ) );
$rail_categories = $categories;

while ( count( $rail_categories ) < 8 && ! empty( $categories ) ) {
	$rail_categories = array_merge( $rail_categories, $categories );
}

$rail_categories = array_slice( $rail_categories, 0, max( 8, count( $categories ) ) );

$render_category_tile = static function ( $term, $index, $is_duplicate = false ) {
	$media      = enhanced_get_term_card_media( $term, 'enhanced-card' );
	$link       = get_term_link( $term );
	$link_attrs = $is_duplicate ? ' tabindex="-1" aria-hidden="true"' : '';

	if ( is_wp_error( $link ) ) {
		return;
	}
	?>
	<a class="lx-cat-tile"
		href="<?php echo esc_url( $link ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lx-cat-tile__img">
			<?php if ( ! empty( $media['image_id'] ) ) : ?>
				<?php
				echo wp_get_attachment_image(
					(int) $media['image_id'],
					'large',
					false,
					array(
						'alt'      => $media['alt'],
						'loading'  => 0 === $index && ! $is_duplicate ? 'eager' : 'lazy',
						'decoding' => 'async',
						'sizes'    => '(max-width: 860px) 52vw, (max-width: 1200px) 26vw, 228px',
					)
				);
				?>
			<?php elseif ( ! empty( $media['image_url'] ) ) : ?>
				<img src="<?php echo esc_url( $media['image_url'] ); ?>"
					alt="<?php echo esc_attr( $media['alt'] ); ?>"
					loading="<?php echo 0 === $index && ! $is_duplicate ? 'eager' : 'lazy'; ?>"
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
	<div class="container">

		<div class="lx-sec-head">
			<h2 class="lx-sec-title"><?php esc_html_e( 'Shop By Category', 'enhanced' ); ?></h2>
			<a class="lx-sec-link" href="<?php echo esc_url( $shop_url ); ?>">
				<?php esc_html_e( 'View All', 'enhanced' ); ?>
			</a>
		</div>

		<div class="lx-cat-marquee lx-rail-marquee"
			aria-label="<?php esc_attr_e( 'Shop by category', 'enhanced' ); ?>"
			style="--lx-cat-marquee-duration: <?php echo esc_attr( $marquee_speed_seconds ); ?>s;">
			<div class="lx-rail-marquee__inner">
				<div class="lx-rail-marquee__group">
					<?php foreach ( $rail_categories as $i => $term ) : ?>
						<?php $render_category_tile( $term, $i ); ?>
					<?php endforeach; ?>
				</div>
				<div class="lx-rail-marquee__group" aria-hidden="true">
					<?php foreach ( $rail_categories as $i => $term ) : ?>
						<?php $render_category_tile( $term, $i, true ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

	</div><!-- .container -->

</section>
