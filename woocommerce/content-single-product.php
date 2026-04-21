<?php
/**
 * Custom single product layout.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

if ( ! $product instanceof WC_Product ) {
	return;
}

$gallery_images     = enhanced_get_product_gallery_images( $product );
$main_image         = reset( $gallery_images );
$primary_category   = enhanced_get_product_primary_category_name( $product->get_id() );
$stock_badge        = enhanced_get_product_stock_badge( $product );
$rating_count       = (int) $product->get_rating_count();
$average_rating     = (float) $product->get_average_rating();
$short_description  = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
$description_text   = $short_description ? $short_description : wpautop( wp_kses_post( wp_trim_words( wp_strip_all_tags( $post->post_content ), 42 ) ) );
$attributes         = array();
$product_attributes = $product->get_attributes();

foreach ( $product_attributes as $attribute ) {
	if ( ! $attribute->get_visible() ) {
		continue;
	}

	$label = wc_attribute_label( $attribute->get_name() );
	$value = $product->get_attribute( $attribute->get_name() );

	if ( ! $value ) {
		continue;
	}

	$attributes[] = array(
		'label' => $label,
		'value' => $value,
	);

	if ( count( $attributes ) >= 3 ) {
		break;
	}
}
?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'enhanced-single-product', $product ); ?>>
	<div class="page-banner page-banner--product">
		<div class="container page-banner__inner page-banner__inner--product">
			<div>
				<p class="breadcrumb">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'enhanced' ); ?></a>
					<span class="breadcrumb__sep">/</span>
					<a href="<?php echo esc_url( enhanced_shop_url() ); ?>"><?php esc_html_e( 'Shop', 'enhanced' ); ?></a>
					<?php if ( $primary_category ) : ?>
						<span class="breadcrumb__sep">/</span>
						<span><?php echo esc_html( $primary_category ); ?></span>
					<?php endif; ?>
				</p>

				<?php if ( $primary_category ) : ?>
					<span class="product-details__brand"><?php echo esc_html( $primary_category ); ?></span>
				<?php endif; ?>

				<h1 class="page-banner__title page-banner__title--product"><?php the_title(); ?></h1>
			</div>

			<div class="page-banner__aside">
				<span class="product-stock-badge product-stock-badge--<?php echo esc_attr( $stock_badge['class'] ); ?>">
					<?php echo esc_html( $stock_badge['label'] ); ?>
				</span>
				<?php if ( $rating_count > 0 && $average_rating > 0 ) : ?>
					<div class="page-banner__rating">
						<?php echo wp_kses_post( wc_get_rating_html( $average_rating, $rating_count ) ); ?>
						<span>
							<?php
							printf(
								/* translators: %d review count */
								esc_html( _n( '%d review', '%d reviews', $rating_count, 'enhanced' ) ),
								$rating_count
							);
							?>
						</span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="single-product-wrap">
		<div class="container">
			<div class="single-product-layout" data-product-gallery>
				<div class="product-gallery-shell">
					<div class="product-gallery">
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

						<div class="product-gallery__stage">
							<div class="product-gallery__main">
								<img
									src="<?php echo esc_url( $main_image['large'] ); ?>"
									alt="<?php echo esc_attr( $main_image['alt'] ?: get_the_title() ); ?>"
									data-gallery-main
								>
							</div>

							<div class="product-gallery__notes">
								<div>
									<strong><?php esc_html_e( 'Designed for detail', 'enhanced' ); ?></strong>
									<span><?php esc_html_e( 'A cleaner gallery with faster visual scanning on mobile and desktop.', 'enhanced' ); ?></span>
								</div>
								<div>
									<strong><?php esc_html_e( 'Commerce ready', 'enhanced' ); ?></strong>
									<span><?php esc_html_e( 'Add to cart, variations, and product data keep working with WooCommerce core.', 'enhanced' ); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="product-details">
					<div class="product-details__card">
						<?php if ( $rating_count > 0 && $average_rating > 0 ) : ?>
							<div class="product-details__rating">
								<?php echo wp_kses_post( wc_get_rating_html( $average_rating, $rating_count ) ); ?>
								<span>
									<?php
									printf(
										/* translators: %d review count */
										esc_html( _n( '%d review', '%d reviews', $rating_count, 'enhanced' ) ),
										$rating_count
									);
									?>
								</span>
							</div>
						<?php endif; ?>

						<div class="product-details__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

						<div class="product-details__desc">
							<?php echo wp_kses_post( $description_text ); ?>
						</div>

						<div class="product-details__service-list">
							<div class="product-service-card">
								<strong><?php esc_html_e( 'Express dispatch', 'enhanced' ); ?></strong>
								<span><?php esc_html_e( 'Clear fulfillment messaging with a layout that feels elevated instead of default.', 'enhanced' ); ?></span>
							</div>
							<div class="product-service-card">
								<strong><?php esc_html_e( 'Responsive purchase flow', 'enhanced' ); ?></strong>
								<span><?php esc_html_e( 'The add-to-cart area stays usable and balanced on smaller screens.', 'enhanced' ); ?></span>
							</div>
						</div>

						<div class="product-details__purchase">
							<?php woocommerce_template_single_add_to_cart(); ?>
						</div>

						<div class="product-meta-card">
							<div class="product-meta-card__row">
								<span><?php esc_html_e( 'SKU', 'enhanced' ); ?></span>
								<strong><?php echo esc_html( $product->get_sku() ? $product->get_sku() : __( 'Made to order', 'enhanced' ) ); ?></strong>
							</div>

							<?php if ( $primary_category ) : ?>
								<div class="product-meta-card__row">
									<span><?php esc_html_e( 'Category', 'enhanced' ); ?></span>
									<strong><?php echo esc_html( $primary_category ); ?></strong>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $attributes ) ) : ?>
								<?php foreach ( $attributes as $attribute ) : ?>
									<div class="product-meta-card__row">
										<span><?php echo esc_html( $attribute['label'] ); ?></span>
										<strong><?php echo esc_html( $attribute['value'] ); ?></strong>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>

						<div class="product-details__extra-hooks">
							<?php do_action( 'woocommerce_single_product_summary' ); ?>
						</div>
					</div>
				</div>
			</div>

			<?php woocommerce_output_product_data_tabs(); ?>

			<section class="product-related">
				<div class="section-heading">
					<div>
						<span class="section-heading__eyebrow"><?php esc_html_e( 'Related picks', 'enhanced' ); ?></span>
						<h2 class="section-heading__title"><?php esc_html_e( 'More products in the same direction', 'enhanced' ); ?></h2>
					</div>
					<p class="section-heading__desc"><?php esc_html_e( 'The related products grid now inherits the same cleaner editorial card treatment for a more premium browsing flow.', 'enhanced' ); ?></p>
				</div>

				<?php woocommerce_output_related_products(); ?>
			</section>
		</div>
	</div>
</article>

<?php do_action( 'woocommerce_after_single_product' ); ?>
