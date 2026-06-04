<?php
/**
 * Single product gallery.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$main_image_alt    = $main_image['alt'] ?: get_the_title();
$main_image_width  = '';
$main_image_height = '';

if ( ! empty( $main_image['id'] ) ) {
	$main_image_meta = wp_get_attachment_image_src( (int) $main_image['id'], 'woocommerce_single' );

	if ( is_array( $main_image_meta ) ) {
		$main_image_width  = isset( $main_image_meta[1] ) ? (int) $main_image_meta[1] : '';
		$main_image_height = isset( $main_image_meta[2] ) ? (int) $main_image_meta[2] : '';
	}
}
?>

<div class="product-gallery product-gallery--minimal">
	<div class="product-gallery__main">
		<?php if ( $product->is_on_sale() ) : ?>
			<span class="product-gallery__badge"><?php esc_html_e( 'Special offer', 'enhanced' ); ?></span>
		<?php endif; ?>

		<img
			src="<?php echo esc_url( $main_image['large'] ); ?>"
			alt="<?php echo esc_attr( $main_image_alt ); ?>"
			data-gallery-main
			loading="eager"
			fetchpriority="high"
			decoding="async"
			<?php if ( $main_image_width ) : ?>
				width="<?php echo esc_attr( $main_image_width ); ?>"
			<?php endif; ?>
			<?php if ( $main_image_height ) : ?>
				height="<?php echo esc_attr( $main_image_height ); ?>"
			<?php endif; ?>
		>
	</div>

	<?php if ( count( $gallery_images ) > 1 ) : ?>
		<div class="product-gallery__thumbs" aria-label="<?php esc_attr_e( 'Product gallery thumbnails', 'enhanced' ); ?>">
			<?php foreach ( $gallery_images as $index => $image ) : ?>
				<button
					class="product-gallery__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>"
					type="button"
					data-gallery-thumb
					data-full-src="<?php echo esc_url( $image['large'] ); ?>"
					data-alt="<?php echo esc_attr( $image['alt'] ?: get_the_title() ); ?>"
					aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"
				>
					<img
						src="<?php echo esc_url( $image['thumb'] ); ?>"
						alt="<?php echo esc_attr( $image['alt'] ?: get_the_title() ); ?>"
						loading="lazy"
						decoding="async"
					>
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
