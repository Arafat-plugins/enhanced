<?php
/**
 * Sale Is On — 2 large collection promo banners side by side.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $sale_items ) && empty( $featured ) ) return;

$cards = ! empty( $sale_items ) ? $sale_items : $featured;
$cards = array_slice( $cards, 0, 2 );

$due_label = enhanced_get_option( 'sale_due_label', __( 'Due Aug 24', 'enhanced' ) );
?>

<section class="lx-sale-on fp-reveal">
	<div class="container">
		<div class="lx-sec-head">
			<h2 class="lx-sec-title"><?php esc_html_e( 'Sale Is On!', 'enhanced' ); ?></h2>
		</div>
	</div>

	<div class="lx-sale-promos container">
		<?php foreach ( $cards as $i => $product ) :
			$img_id  = $product->get_image_id();
			$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'enhanced-hero' ) : '';
			$cats    = get_the_terms( $product->get_id(), 'product_cat' );
			$cat     = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : ( 0 === $i ? __( 'Main Collection', 'enhanced' ) : __( 'Woman Sale', 'enhanced' ) );
		?>
			<a class="lx-sale-promo fp-reveal fp-reveal--delay-<?php echo $i + 1; ?>"
				href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"
				<?php if ( $img_url ) : ?>
					style="background-image: url('<?php echo esc_url( $img_url ); ?>')"
				<?php endif; ?>>

				<div class="lx-sale-promo__top">
					<span class="lx-sale-promo__collection"><?php echo esc_html( $cat ); ?></span>
					<span class="lx-sale-promo__due"><?php echo esc_html( $due_label ); ?></span>
				</div>

				<div class="lx-sale-promo__bottom">
					<h3 class="lx-sale-promo__title"><?php echo esc_html( $product->get_name() ); ?></h3>
					<span class="lx-btn lx-btn--white lx-btn--sm">
						<?php esc_html_e( 'Check It Out', 'enhanced' ); ?>
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6h8M7 3l3 3-3 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
				</div>

				<?php if ( ! $img_url ) : ?>
					<div class="lx-sale-promo__fallback"></div>
				<?php endif; ?>
			</a>
		<?php endforeach; ?>
	</div>
</section>
