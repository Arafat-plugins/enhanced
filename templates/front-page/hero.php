<?php
/**
 * Front page hero section.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$fallback_image = $hero_image_url;

if ( ! $fallback_image && enhanced_is_woo() && $hero_product instanceof WC_Product ) {
	$fallback_image = $hero_product->get_image_id() ? wp_get_attachment_image_url( $hero_product->get_image_id(), 'enhanced-hero' ) : wc_placeholder_img_src( 'enhanced-hero' );
}

if ( ! $fallback_image ) {
	$fallback_image = $reference_visual_url;
}
?>

<section class="landing-hero landing-hero--<?php echo esc_attr( sanitize_html_class( $hero_media_type ) ); ?>">
	<div class="container">
		<div class="landing-hero__grid">
			<div class="landing-hero__content">
				<?php if ( $hero_eyebrow ) : ?>
					<span class="landing-hero__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></span>
				<?php endif; ?>

				<?php if ( $hero_title ) : ?>
					<h1 class="landing-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
				<?php endif; ?>

				<?php if ( $hero_description ) : ?>
					<p class="landing-hero__desc"><?php echo esc_html( $hero_description ); ?></p>
				<?php endif; ?>

				<div class="landing-hero__actions">
					<?php if ( $hero_primary_label ) : ?>
						<a class="btn btn--accent" href="<?php echo esc_url( $hero_primary_url ?: $shop_url ); ?>"><?php echo esc_html( $hero_primary_label ); ?></a>
					<?php endif; ?>

					<?php if ( $hero_secondary_label ) : ?>
						<a class="btn btn--outline" href="<?php echo esc_url( $hero_secondary_url ?: home_url( '/about/' ) ); ?>"><?php echo esc_html( $hero_secondary_label ); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<div class="landing-hero__media">
				<?php if ( 'video' === $hero_media_type && $hero_video_upload_url ) : ?>
					<div class="landing-hero__video-shell">
						<video class="landing-hero__video" autoplay muted loop playsinline>
							<source src="<?php echo esc_url( $hero_video_upload_url ); ?>">
						</video>
					</div>
				<?php elseif ( 'external-video' === $hero_media_type && $hero_external_video_url ) : ?>
					<div class="landing-hero__video-shell">
						<?php if ( $hero_external_is_video ) : ?>
							<video class="landing-hero__video" autoplay muted loop playsinline>
								<source src="<?php echo esc_url( $hero_external_video_url ); ?>">
							</video>
						<?php elseif ( $hero_external_embed ) : ?>
							<div class="landing-hero__embed">
								<?php echo wp_kses_post( $hero_external_embed ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php elseif ( 'slider' === $hero_media_type && ! empty( $hero_slider_items ) ) : ?>
					<div class="landing-hero-slider" data-hero-slider>
						<div class="landing-hero-slider__stage">
							<?php foreach ( $hero_slider_items as $index => $slider_product ) : ?>
								<?php
								$slider_image = $slider_product->get_image_id() ? wp_get_attachment_image_url( $slider_product->get_image_id(), 'enhanced-hero' ) : wc_placeholder_img_src( 'enhanced-hero' );
								$slider_cat   = enhanced_get_product_primary_category_name( $slider_product->get_id() );
								?>
								<article class="landing-hero-slider__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-hero-slide>
									<div class="landing-hero-slider__image">
										<img src="<?php echo esc_url( $slider_image ); ?>" alt="<?php echo esc_attr( $slider_product->get_name() ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
									</div>
									<div class="landing-hero-slider__caption">
										<?php if ( $slider_cat ) : ?>
											<span><?php echo esc_html( $slider_cat ); ?></span>
										<?php endif; ?>
										<strong><?php echo esc_html( $slider_product->get_name() ); ?></strong>
										<em><?php echo wp_kses_post( $slider_product->get_price_html() ); ?></em>
									</div>
								</article>
							<?php endforeach; ?>
						</div>

						<?php if ( count( $hero_slider_items ) > 1 ) : ?>
							<div class="landing-hero-slider__thumbs">
								<?php foreach ( $hero_slider_items as $index => $slider_product ) : ?>
									<?php
									$thumb_image = $slider_product->get_image_id() ? wp_get_attachment_image_url( $slider_product->get_image_id(), 'enhanced-thumb' ) : wc_placeholder_img_src( 'enhanced-thumb' );
									?>
									<button class="landing-hero-slider__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-hero-thumb aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
										<img src="<?php echo esc_url( $thumb_image ); ?>" alt="<?php echo esc_attr( $slider_product->get_name() ); ?>" loading="lazy">
									</button>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="landing-hero__image-shell">
						<?php if ( $fallback_image ) : ?>
							<img src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php echo esc_attr( $hero_title ? $hero_title : get_bloginfo( 'name' ) ); ?>" loading="eager">
						<?php else : ?>
							<div class="landing-hero__fallback-note">
								<span><?php esc_html_e( 'Upload hero media from theme settings', 'enhanced' ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
