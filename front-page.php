<?php
/**
 * Front page template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url                = enhanced_shop_url();
$hero_eyebrow            = enhanced_get_option( 'hero_eyebrow', __( 'Editorial commerce for modern brands', 'enhanced' ) );
$hero_title              = enhanced_get_option( 'hero_title', __( 'Build a storefront that feels tailored, not templated.', 'enhanced' ) );
$hero_description        = enhanced_get_option( 'hero_description', __( 'Enhanced now leans into stronger storytelling, clearer product discovery, and a more premium responsive storefront from homepage to checkout.', 'enhanced' ) );
$hero_primary_label      = enhanced_get_option( 'hero_primary_label', __( 'Shop the collection', 'enhanced' ) );
$hero_primary_url        = enhanced_get_option( 'hero_primary_url', $shop_url );
$hero_secondary_label    = enhanced_get_option( 'hero_secondary_label', __( 'Explore the brand story', 'enhanced' ) );
$hero_secondary_url      = enhanced_get_option( 'hero_secondary_url', home_url( '/about/' ) );
$hero_media_type         = enhanced_get_option( 'hero_media_type', 'image' );
$hero_image_id           = (int) enhanced_get_option( 'hero_image', 0 );
$hero_image_url          = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'enhanced-hero' ) : '';
$hero_video_upload_id    = (int) enhanced_get_option( 'hero_video_upload', 0 );
$hero_video_upload_url   = $hero_video_upload_id ? wp_get_attachment_url( $hero_video_upload_id ) : '';
$hero_external_video_url = enhanced_get_option( 'hero_video_url', '' );
$hero_slider_source      = enhanced_get_option( 'hero_slider_source', 'featured' );
$reference_visual_url    = get_template_directory_uri() . '/assets/images/goodhero-reference.webp';

$categories = array();
$featured   = array();
$arrivals   = array();
$sale_items = array();

if ( enhanced_is_woo() ) {
	$categories = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'number'     => 8,
		'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
	) );

	$featured = wc_get_products( array(
		'status'   => 'publish',
		'limit'    => 8,
		'featured' => true,
	) );

	if ( empty( $featured ) ) {
		$featured = wc_get_products( array(
			'status'  => 'publish',
			'limit'   => 8,
			'orderby' => 'date',
			'order'   => 'DESC',
		) );
	}

	$arrivals = wc_get_products( array(
		'status'  => 'publish',
		'limit'   => 10,
		'orderby' => 'date',
		'order'   => 'DESC',
	) );

	$sale_product_ids = array_slice( wc_get_product_ids_on_sale(), 0, 10 );

	if ( ! empty( $sale_product_ids ) ) {
		$sale_items = wc_get_products( array(
			'status'  => 'publish',
			'limit'   => 10,
			'include' => $sale_product_ids,
		) );
	}
}

$hero_product      = ! empty( $featured ) ? $featured[0] : ( ! empty( $arrivals ) ? $arrivals[0] : null );
$hero_collections  = array(
	'featured' => $featured,
	'arrivals' => $arrivals,
	'sale'     => $sale_items,
);
$hero_slider_items = ! empty( $hero_collections[ $hero_slider_source ] ) ? array_slice( $hero_collections[ $hero_slider_source ], 0, 5 ) : array();
$showcase_items    = ! empty( $arrivals ) ? array_slice( $arrivals, 0, 5 ) : array_slice( $featured, 0, 5 );

$hero_external_embed = '';
$hero_external_type  = wp_check_filetype( $hero_external_video_url );
$hero_external_is_video = ! empty( $hero_external_type['type'] ) && 0 === strpos( $hero_external_type['type'], 'video/' );

if ( $hero_external_video_url && ! $hero_external_is_video ) {
	$hero_external_embed = wp_oembed_get(
		$hero_external_video_url,
		array(
			'width'  => 960,
			'height' => 540,
		)
	);
}
?>

<main id="primary" class="site-main">
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
						<?php
						$fallback_image = $hero_image_url;

						if ( ! $fallback_image && enhanced_is_woo() && $hero_product instanceof WC_Product ) {
							$fallback_image = $hero_product->get_image_id() ? wp_get_attachment_image_url( $hero_product->get_image_id(), 'enhanced-hero' ) : wc_placeholder_img_src( 'enhanced-hero' );
						}

						if ( ! $fallback_image ) {
							$fallback_image = $reference_visual_url;
						}
						?>
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

	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
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
	<?php endif; ?>

	<?php if ( ! empty( $showcase_items ) ) : ?>
		<section class="arrival-showcase" style="--arrival-reference-image: url('<?php echo esc_url( $reference_visual_url ); ?>');">
			<div class="container">
				<div class="arrival-showcase__layout" data-arrival-showcase>
					<div class="arrival-showcase__stage">
						<?php foreach ( $showcase_items as $index => $showcase_product ) : ?>
							<?php
							$showcase_image = $showcase_product->get_image_id() ? wp_get_attachment_image_url( $showcase_product->get_image_id(), 'enhanced-hero' ) : wc_placeholder_img_src( 'enhanced-hero' );
							$showcase_cat   = enhanced_get_product_primary_category_name( $showcase_product->get_id() );
							?>
							<article class="arrival-showcase__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-arrival-slide>
								<div class="arrival-showcase__image">
									<img src="<?php echo esc_url( $showcase_image ); ?>" alt="<?php echo esc_attr( $showcase_product->get_name() ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
								</div>

								<div class="arrival-showcase__content">
									<span class="arrival-showcase__eyebrow"><?php esc_html_e( 'New Products', 'enhanced' ); ?></span>
									<h2 class="arrival-showcase__title"><?php echo esc_html( $showcase_product->get_name() ); ?></h2>
									<div class="arrival-showcase__price"><?php echo wp_kses_post( $showcase_product->get_price_html() ); ?></div>
									<?php if ( $showcase_cat ) : ?>
										<p class="arrival-showcase__meta"><?php echo esc_html( $showcase_cat ); ?></p>
									<?php endif; ?>
									<a class="btn btn--accent" href="<?php echo esc_url( get_permalink( $showcase_product->get_id() ) ); ?>"><?php esc_html_e( 'Explore product', 'enhanced' ); ?></a>
								</div>
							</article>
						<?php endforeach; ?>
					</div>

					<?php if ( count( $showcase_items ) > 1 ) : ?>
						<div class="arrival-showcase__thumbs">
							<?php foreach ( $showcase_items as $index => $showcase_product ) : ?>
								<?php
								$thumb_image = $showcase_product->get_image_id() ? wp_get_attachment_image_url( $showcase_product->get_image_id(), 'enhanced-thumb' ) : wc_placeholder_img_src( 'enhanced-thumb' );
								?>
								<button class="arrival-showcase__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-arrival-thumb aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
									<img src="<?php echo esc_url( $thumb_image ); ?>" alt="<?php echo esc_attr( $showcase_product->get_name() ); ?>" loading="lazy">
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $featured ) ) : ?>
		<section class="home-rail-section">
			<div class="container">
				<div class="home-rail" data-rail-slider>
					<div class="home-rail__head">
						<div class="home-rail__title-wrap">
							<span class="home-rail__eyebrow"><?php esc_html_e( 'Featured', 'enhanced' ); ?></span>
							<h2 class="home-rail__title"><?php esc_html_e( 'Featured Products', 'enhanced' ); ?></h2>
						</div>
						<div class="home-rail__actions">
							<a class="home-rail__link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop all', 'enhanced' ); ?></a>
							<div class="home-rail__nav">
								<button class="home-rail__btn" type="button" data-rail-prev aria-label="<?php esc_attr_e( 'Previous products', 'enhanced' ); ?>">
									<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M9.5 3L5 8l4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
								</button>
								<button class="home-rail__btn" type="button" data-rail-next aria-label="<?php esc_attr_e( 'Next products', 'enhanced' ); ?>">
									<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6.5 3L11 8l-4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
								</button>
							</div>
						</div>
					</div>

					<div class="home-rail__track home-rail__track--products" data-rail-track>
						<?php foreach ( $featured as $featured_product ) : ?>
							<?php enhanced_render_product_card( $featured_product ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $sale_items ) ) : ?>
		<section class="home-rail-section home-rail-section--soft">
			<div class="container">
				<div class="home-rail" data-rail-slider>
					<div class="home-rail__head">
						<div class="home-rail__title-wrap">
							<span class="home-rail__eyebrow"><?php esc_html_e( 'Offers', 'enhanced' ); ?></span>
							<h2 class="home-rail__title"><?php esc_html_e( 'Sale Picks', 'enhanced' ); ?></h2>
						</div>
						<div class="home-rail__actions">
							<a class="home-rail__link" href="<?php echo esc_url( add_query_arg( 'orderby', 'price', $shop_url ) ); ?>"><?php esc_html_e( 'See more', 'enhanced' ); ?></a>
							<div class="home-rail__nav">
								<button class="home-rail__btn" type="button" data-rail-prev aria-label="<?php esc_attr_e( 'Previous products', 'enhanced' ); ?>">
									<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M9.5 3L5 8l4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
								</button>
								<button class="home-rail__btn" type="button" data-rail-next aria-label="<?php esc_attr_e( 'Next products', 'enhanced' ); ?>">
									<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6.5 3L11 8l-4.5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
								</button>
							</div>
						</div>
					</div>

					<div class="home-rail__track home-rail__track--products" data-rail-track>
						<?php foreach ( $sale_items as $sale_product ) : ?>
							<?php enhanced_render_product_card( $sale_product ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
