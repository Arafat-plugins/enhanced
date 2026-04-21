<?php
/**
 * Single product gallery.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="product-gallery product-gallery--minimal">
	<div class="product-gallery__main">
		<?php if ( $product->is_on_sale() ) : ?>
			<span class="product-gallery__badge"><?php esc_html_e( 'Special offer', 'enhanced' ); ?></span>
		<?php endif; ?>

		<img
			src="<?php echo esc_url( $main_image['large'] ); ?>"
			alt="<?php echo esc_attr( $main_image['alt'] ?: get_the_title() ); ?>"
			data-gallery-main
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
					<img src="<?php echo esc_url( $image['thumb'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ?: get_the_title() ); ?>" loading="lazy">
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
