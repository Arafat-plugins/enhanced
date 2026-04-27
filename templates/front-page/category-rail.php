<?php
/**
 * Category rail — Luxina: tiles row + browse split below.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$browse_img_url   = enhanced_get_image_option_url( 'browse_model_image', 'enhanced-hero' );
$browse_panel_bg  = enhanced_get_image_option_url( 'browse_panel_bg', 'enhanced-hero' );
$browse_opacity   = (float) get_theme_mod( 'enhanced_browse_overlay_opacity', 0.78 );
$browse_opacity   = max( 0, min( 1, $browse_opacity ) );
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

	<!-- Browse split: editorial dark panel + image -->
	<?php $product_count = enhanced_is_woo() ? (int) wp_count_posts( 'product' )->publish : 0; ?>
	<div class="lx-browse-split">

		<div class="lx-browse-split__text<?php echo $browse_panel_bg ? ' has-panel-bg' : ''; ?>"
			<?php if ( $browse_panel_bg ) : ?>
				style="background-image: url('<?php echo esc_url( $browse_panel_bg ); ?>');"
			<?php endif; ?>>

			<?php if ( $browse_panel_bg ) : ?>
			<div class="lx-browse-split__overlay" style="background: rgba(0,0,0,<?php echo esc_attr( $browse_opacity ); ?>);"></div>
			<?php endif; ?>

			<span class="lx-browse-split__kicker"><?php esc_html_e( 'Our Collections', 'enhanced' ); ?></span>

			<h3 class="lx-browse-split__heading">
				<span class="lx-browse-split__heading-outline"><?php esc_html_e( 'Browse', 'enhanced' ); ?></span>
				<span><?php esc_html_e( 'Categories', 'enhanced' ); ?></span>
			</h3>

			<p class="lx-browse-split__desc">
				<?php esc_html_e( 'Curated collections that match your unique taste and lifestyle.', 'enhanced' ); ?>
			</p>

			<?php if ( ! empty( $categories ) ) : ?>
			<div class="lx-browse-split__stats">
				<div class="lx-browse-split__stat">
					<span class="lx-browse-split__stat-num"><?php echo count( $categories ); ?>+</span>
					<span class="lx-browse-split__stat-label"><?php esc_html_e( 'Categories', 'enhanced' ); ?></span>
				</div>
				<?php if ( $product_count > 0 ) : ?>
				<div class="lx-browse-split__divider"></div>
				<div class="lx-browse-split__stat">
					<span class="lx-browse-split__stat-num"><?php echo esc_html( $product_count ); ?>+</span>
					<span class="lx-browse-split__stat-label"><?php esc_html_e( 'Products', 'enhanced' ); ?></span>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<a class="lx-browse-split__cta" href="<?php echo esc_url( $shop_url ); ?>">
				<?php esc_html_e( 'Explore All', 'enhanced' ); ?>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
					<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>

		<div class="lx-browse-split__image">
			<?php if ( $browse_img_url ) : ?>
				<img src="<?php echo esc_url( $browse_img_url ); ?>" alt="" loading="lazy">
			<?php else : ?>
				<div class="lx-browse-split__fallback"></div>
			<?php endif; ?>
			<div class="lx-browse-split__image-badge">
				<span><?php esc_html_e( 'New Season', 'enhanced' ); ?></span>
			</div>
		</div>

	</div>

</section>
