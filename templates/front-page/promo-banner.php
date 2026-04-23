<?php
/**
 * Front page promotional banner — split with badge.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$promo_discount = enhanced_get_option( 'promo_discount', '30' );
$promo_label    = enhanced_get_option( 'promo_label', __( 'Off', 'enhanced' ) );
$promo_title    = enhanced_get_option( 'promo_title', __( 'On New Collection', 'enhanced' ) );
$promo_eyebrow  = enhanced_get_option( 'promo_eyebrow', __( 'Up Coming Discount', 'enhanced' ) );
$promo_btn      = enhanced_get_option( 'promo_btn', __( 'Shop Now', 'enhanced' ) );
$promo_url      = enhanced_get_option( 'promo_url', $shop_url );

$promo_left_img     = enhanced_get_image_option_url( 'promo_left_image', 'enhanced-hero' );
$promo_right_img    = enhanced_get_image_option_url( 'promo_right_image', 'enhanced-hero' );
?>

<section class="fp2-promo">

	<?php /* Left panel */ ?>
	<div class="fp2-promo__panel fp2-promo__panel--left">
		<?php if ( $promo_left_img ) : ?>
			<img class="fp2-promo__panel-bg" src="<?php echo esc_url( $promo_left_img ); ?>" alt="" loading="lazy">
		<?php else : ?>
			<div class="fp2-promo__panel-fallback fp2-promo__panel-fallback--left">
				<span><?php esc_html_e( 'Promo left image', 'enhanced' ); ?></span>
			</div>
		<?php endif; ?>
		<div class="fp2-promo__panel-overlay"></div>
		<div class="fp2-promo__panel-content">
			<span class="fp2-promo__eyebrow"><?php echo esc_html( $promo_eyebrow ); ?></span>
			<h3 class="fp2-promo__title"><?php echo esc_html( $promo_title ); ?></h3>
			<a class="fp2-promo__btn" href="<?php echo esc_url( $promo_url ); ?>">
				<?php echo esc_html( $promo_btn ); ?>
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
					<path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>
	</div>

	<?php /* Centre badge */ ?>
	<div class="fp2-promo__badge">
		<span class="fp2-promo__badge-num"><?php echo esc_html( $promo_discount ); ?>%</span>
		<span class="fp2-promo__badge-label"><?php echo esc_html( $promo_label ); ?></span>
	</div>

	<?php /* Right panel */ ?>
	<div class="fp2-promo__panel fp2-promo__panel--right">
		<?php if ( $promo_right_img ) : ?>
			<img class="fp2-promo__panel-bg" src="<?php echo esc_url( $promo_right_img ); ?>" alt="" loading="lazy">
		<?php else : ?>
			<div class="fp2-promo__panel-fallback fp2-promo__panel-fallback--right">
				<span><?php esc_html_e( 'Promo right image', 'enhanced' ); ?></span>
			</div>
		<?php endif; ?>
		<div class="fp2-promo__panel-overlay"></div>
		<div class="fp2-promo__panel-content">
			<span class="fp2-promo__eyebrow"><?php echo esc_html( $promo_eyebrow ); ?></span>
			<h3 class="fp2-promo__title"><?php echo esc_html( $promo_title ); ?></h3>
			<a class="fp2-promo__btn" href="<?php echo esc_url( $promo_url ); ?>">
				<?php echo esc_html( $promo_btn ); ?>
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
					<path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>
	</div>

</section>
