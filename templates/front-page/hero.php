<?php
/**
 * Front page hero.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

// Determine media mode first. Uploaded or external video takes priority.
$hero_video_src = $hero_video_upload_url ?: $hero_external_video_url;
$show_video     = in_array( $hero_media_type, array( 'video', 'external-video' ), true )
	&& ! empty( $hero_video_src );

// Resolve image fallback only when not in video mode.
$uploaded_image       = $hero_image_url;
$uploaded_image_id    = (int) enhanced_get_option( 'hero_image', 0 );
$hero_product_image_id = 0;
$latest_product_image = '';
$left_panel_image     = '';
$left_panel_image_id  = 0;

if ( enhanced_is_woo() && $hero_product instanceof WC_Product ) {
	$hero_product_image_id = (int) $hero_product->get_image_id();
	$latest_product_image = $hero_product_image_id
		? wp_get_attachment_image_url( $hero_product_image_id, 'enhanced-hero' )
		: wc_placeholder_img_src( 'enhanced-hero' );
}

if ( ! $show_video ) {
	$left_panel_image_id = $uploaded_image_id ?: $hero_product_image_id;
	$left_panel_image    = $left_panel_image_id
		? wp_get_attachment_image_url( $left_panel_image_id, 'enhanced-hero' )
		: ( $uploaded_image ?: $latest_product_image );

	if ( ! $left_panel_image ) {
		$left_panel_image = $reference_visual_url;
	}
}

$hero_discount         = enhanced_get_option( 'hero_discount', '50' );
$hero_overlay_color   = enhanced_get_option( 'hero_overlay_color', '#000000' );
$hero_overlay_opacity = min( 100, max( 0, (int) enhanced_get_option( 'hero_overlay_opacity', 0 ) ) );

// Build right-panel slides from Enhanced Settings › Right Panel Slides.
$rp_tr_interval = max( 2000, (int) enhanced_get_option( 'rp_tr_interval', 4000 ) );
$rp_br_interval = max( 2000, (int) enhanced_get_option( 'rp_br_interval', 5000 ) );

$rp_tr_slides = enhanced_build_rp_slides( 'tr' );
$rp_br_slides = enhanced_build_rp_slides( 'br' );
?>

<div class="lx-hero-grid">
	<div class="lx-hero-left">
		<div class="lx-hero-left__content">
			<?php if ( $hero_eyebrow ) : ?>
				<span class="lx-hero-barcode" aria-hidden="true">
					<svg class="lx-hero-barcode__svg" viewBox="0 0 80 28" fill="currentColor" aria-hidden="true">
						<rect x="0" y="0" width="3" height="28"/><rect x="5" y="0" width="1" height="28"/>
						<rect x="8" y="0" width="2" height="28"/><rect x="12" y="0" width="4" height="28"/>
						<rect x="18" y="0" width="1" height="28"/><rect x="21" y="0" width="3" height="28"/>
						<rect x="26" y="0" width="2" height="28"/><rect x="30" y="0" width="1" height="28"/>
						<rect x="33" y="0" width="4" height="28"/><rect x="39" y="0" width="1" height="28"/>
						<rect x="42" y="0" width="2" height="28"/><rect x="46" y="0" width="3" height="28"/>
						<rect x="51" y="0" width="1" height="28"/><rect x="54" y="0" width="2" height="28"/>
						<rect x="58" y="0" width="4" height="28"/><rect x="64" y="0" width="1" height="28"/>
						<rect x="67" y="0" width="3" height="28"/><rect x="72" y="0" width="2" height="28"/>
						<rect x="76" y="0" width="4" height="28"/>
					</svg>
					<span class="lx-hero-barcode__label"><?php echo esc_html( $hero_eyebrow ); ?></span>
				</span>
			<?php endif; ?>

			<div class="lx-hero-discount">
				<?php echo esc_html( $hero_discount ); ?><sup>%<br><?php esc_html_e( 'OFF', 'enhanced' ); ?></sup>
			</div>

			<?php if ( $hero_description ) : ?>
				<p class="lx-hero-desc"><?php echo esc_html( $hero_description ); ?></p>
			<?php endif; ?>

			<?php if ( $hero_primary_label ) : ?>
				<a class="lx-btn lx-btn--black lx-btn--pill"
					href="<?php echo esc_url( $hero_primary_url ?: $shop_url ); ?>">
					<?php echo esc_html( $hero_primary_label ); ?>
					<svg width="13" height="13" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
			<?php endif; ?>
		</div>

		<div class="lx-hero-left__media">
			<?php if ( $show_video ) : ?>
				<?php if ( $hero_video_upload_url ) : ?>
					<video class="lx-hero-left__model" autoplay muted loop playsinline>
						<source src="<?php echo esc_url( $hero_video_upload_url ); ?>">
					</video>
				<?php elseif ( $hero_external_is_video ) : ?>
					<video class="lx-hero-left__model" autoplay muted loop playsinline>
						<source src="<?php echo esc_url( $hero_external_video_url ); ?>">
					</video>
				<?php elseif ( $hero_external_embed ) : ?>
					<div class="lx-hero-left__model lx-hero-left__embed">
						<?php echo $hero_external_embed; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				<?php else : ?>
					<div class="lx-hero-left__fallback"></div>
				<?php endif; ?>
			<?php elseif ( $left_panel_image ) : ?>
				<?php if ( $left_panel_image_id ) : ?>
					<?php
					echo wp_get_attachment_image(
						$left_panel_image_id,
						'enhanced-hero',
						false,
						array(
							'class'         => 'lx-hero-left__model',
							'alt'           => $hero_title ?: get_bloginfo( 'name' ),
							'loading'       => 'eager',
							'fetchpriority' => 'high',
							'decoding'      => 'async',
							'sizes'         => '(min-width: 961px) 42vw, 100vw',
						)
					);
					?>
				<?php else : ?>
					<img class="lx-hero-left__model"
						src="<?php echo esc_url( $left_panel_image ); ?>"
						alt="<?php echo esc_attr( $hero_title ?: get_bloginfo( 'name' ) ); ?>"
						loading="eager"
						fetchpriority="high"
						decoding="async">
				<?php endif; ?>
			<?php else : ?>
				<div class="lx-hero-left__fallback"></div>
			<?php endif; ?>
		</div>

		<?php if ( $hero_overlay_opacity > 0 ) : ?>
		<div class="lx-hero-left__overlay"
		     style="background:<?php echo esc_attr( $hero_overlay_color ); ?>;opacity:<?php echo esc_attr( $hero_overlay_opacity / 100 ); ?>;"></div>
		<?php endif; ?>
	</div>

	<?php /* Top-right slider */ ?>
	<div class="lx-hero-tr lx-rp-slider" data-lx-rp-slider
	     data-interval="<?php echo esc_attr( $rp_tr_interval ); ?>">
		<?php if ( ! empty( $rp_tr_slides ) ) : ?>
			<?php foreach ( $rp_tr_slides as $rp_i => $rp_slide ) : ?>
			<div class="lx-rp-slide<?php echo 0 === $rp_i ? ' is-active' : ''; ?>"
			     style="--rp-opacity:<?php echo esc_attr( $rp_slide['opacity'] ); ?>">
				<img class="lx-rp-slide__img"
				     src="<?php echo esc_url( $rp_slide['img'] ); ?>"
				     alt=""
				     loading="<?php echo 0 === $rp_i ? 'eager' : 'lazy'; ?>"
				     fetchpriority="<?php echo 0 === $rp_i ? 'low' : 'auto'; ?>"
				     decoding="async">
				<div class="lx-rp-slide__overlay"></div>
				<?php if ( '' !== $rp_slide['title'] ) : ?>
					<p class="lx-rp-slide__title"><?php echo esc_html( $rp_slide['title'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="lx-rp-slide is-active">
				<div class="lx-rp-slide__empty"></div>
			</div>
		<?php endif; ?>
	</div>

	<?php /* Bottom-right slider */ ?>
	<div class="lx-hero-br lx-rp-slider" data-lx-rp-slider
	     data-interval="<?php echo esc_attr( $rp_br_interval ); ?>">
		<?php if ( ! empty( $rp_br_slides ) ) : ?>
			<?php foreach ( $rp_br_slides as $rp_i => $rp_slide ) : ?>
			<div class="lx-rp-slide<?php echo 0 === $rp_i ? ' is-active' : ''; ?>"
			     style="--rp-opacity:<?php echo esc_attr( $rp_slide['opacity'] ); ?>">
				<img class="lx-rp-slide__img"
				     src="<?php echo esc_url( $rp_slide['img'] ); ?>"
				     alt=""
				     loading="<?php echo 0 === $rp_i ? 'eager' : 'lazy'; ?>"
				     fetchpriority="<?php echo 0 === $rp_i ? 'low' : 'auto'; ?>"
				     decoding="async">
				<div class="lx-rp-slide__overlay"></div>
				<?php if ( '' !== $rp_slide['title'] ) : ?>
					<p class="lx-rp-slide__title"><?php echo esc_html( $rp_slide['title'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="lx-rp-slide is-active">
				<div class="lx-rp-slide__empty"></div>
			</div>
		<?php endif; ?>
	</div>
</div><!-- .lx-hero-grid -->
