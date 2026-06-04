<?php
/**
 * Full-width sale banner.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$banner_eyebrow = enhanced_get_option( 'sale_banner_eyebrow', __( 'Selected For You', 'enhanced' ) );
$banner_title   = enhanced_get_option( 'sale_banner_title', __( 'Need Beneficial', 'enhanced' ) );
$banner_desc    = enhanced_get_option( 'sale_banner_desc', __( 'Discover the best deals selected just for you.', 'enhanced' ) );
$banner_btn     = enhanced_get_option( 'sale_banner_btn', __( 'Go Ahead', 'enhanced' ) );
$banner_url     = enhanced_get_option( 'sale_banner_url', $shop_url );
$banner_img     = enhanced_get_image_option_url( 'sale_banner_image', 'enhanced-hero' );
?>

<section class="lx-sale-banner">
	<?php if ( $banner_img ) : ?>
		<img class="lx-sale-banner__img" src="<?php echo esc_url( $banner_img ); ?>" alt="" loading="lazy">
	<?php else : ?>
		<div class="lx-sale-banner__img-fallback"></div>
	<?php endif; ?>
	<div class="container lx-sale-banner__inner">
		<?php if ( $banner_eyebrow ) : ?>
			<p class="lx-sale-banner__eyebrow"><?php echo esc_html( $banner_eyebrow ); ?></p>
		<?php endif; ?>
		<h2 class="lx-sale-banner__title"><?php echo esc_html( $banner_title ); ?></h2>
		<?php if ( $banner_desc ) : ?>
			<p class="lx-sale-banner__desc"><?php echo esc_html( $banner_desc ); ?></p>
		<?php endif; ?>
		<?php if ( $banner_btn ) : ?>
			<a class="lx-btn lx-btn--white" href="<?php echo esc_url( $banner_url ); ?>">
				<?php echo esc_html( $banner_btn ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>
