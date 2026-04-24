<?php
/**
 * Front page tabbed product grid — New Arrivals.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

// Gather tab data
$all_products = ! empty( $featured ) ? $featured : ( ! empty( $arrivals ) ? $arrivals : array() );
$all_products = array_slice( $all_products, 0, 8 );

// Build category tabs from products
$tab_cats = array();
foreach ( $all_products as $p ) {
	$cats = get_the_terms( $p->get_id(), 'product_cat' );
	if ( $cats && ! is_wp_error( $cats ) ) {
		foreach ( $cats as $c ) {
			if ( ! isset( $tab_cats[ $c->term_id ] ) ) {
				$tab_cats[ $c->term_id ] = $c->name;
			}
		}
	}
}
$tab_cats = array_slice( $tab_cats, 0, 4, true );
?>

<section class="fp2-arrivals fp-reveal">
	<div class="container">

		<div class="fp2-section-head">
			<h2 class="fp2-section-title"><?php esc_html_e( 'New Arrival', 'enhanced' ); ?></h2>
			<p class="fp2-section-sub"><?php esc_html_e( 'Discover the latest in our curated collection of elevated ethnic menswear.', 'enhanced' ); ?></p>

			<?php if ( ! empty( $tab_cats ) ) : ?>
				<div class="fp2-tabs" data-fp2-tabs>
					<button class="fp2-tab is-active" data-fp2-tab="all"><?php esc_html_e( 'All', 'enhanced' ); ?></button>
					<?php foreach ( $tab_cats as $term_id => $term_name ) : ?>
						<button class="fp2-tab" data-fp2-tab="<?php echo esc_attr( $term_id ); ?>">
							<?php echo esc_html( $term_name ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="fp2-grid" data-fp2-grid>
			<?php foreach ( $all_products as $i => $p ) :
				$img_id  = $p->get_image_id();
				$img_url = $img_id
					? wp_get_attachment_image_url( $img_id, 'enhanced-card' )
					: ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'enhanced-card' ) : '' );
				$is_sale = $p->is_on_sale();
				$is_new  = ( time() - $p->get_date_created()->getTimestamp() ) < ( 30 * DAY_IN_SECONDS );
				$cats    = get_the_terms( $p->get_id(), 'product_cat' );
				$cat_ids = array();
				if ( $cats && ! is_wp_error( $cats ) ) {
					foreach ( $cats as $c ) { $cat_ids[] = $c->term_id; }
				}
			?>
				<div class="fp2-product-card fp-reveal fp-reveal--delay-<?php echo min( ( $i % 4 ) + 1, 4 ); ?>"
					data-fp2-product-cats="<?php echo esc_attr( implode( ',', $cat_ids ) ); ?>">

					<div class="fp2-product-card__media">
						<?php if ( $img_url ) : ?>
							<img src="<?php echo esc_url( $img_url ); ?>"
								alt="<?php echo esc_attr( $p->get_name() ); ?>"
								loading="<?php echo $i < 4 ? 'eager' : 'lazy'; ?>">
						<?php else : ?>
							<div class="fp2-product-card__fallback">
								<?php echo esc_html( $p->get_name() ); ?><br>
								<small><?php esc_html_e( '[product image]', 'enhanced' ); ?></small>
							</div>
						<?php endif; ?>

						<?php if ( $is_sale ) : ?>
							<span class="fp2-badge fp2-badge--sale"><?php esc_html_e( 'Sale', 'enhanced' ); ?></span>
						<?php elseif ( $is_new ) : ?>
							<span class="fp2-badge fp2-badge--new"><?php esc_html_e( 'New', 'enhanced' ); ?></span>
						<?php endif; ?>
					</div>

					<div class="fp2-product-card__body">
						<h3 class="fp2-product-card__name">
							<a href="<?php echo esc_url( get_permalink( $p->get_id() ) ); ?>">
								<?php echo esc_html( $p->get_name() ); ?>
							</a>
						</h3>
						<div class="fp2-product-card__price">
							<?php echo wp_kses_post( $p->get_price_html() ); ?>
						</div>
						<a class="fp2-product-card__atc"
							href="<?php echo esc_url( get_permalink( $p->get_id() ) ); ?>">
							<?php esc_html_e( 'Add To Cart', 'enhanced' ); ?>
						</a>
					</div>

				</div>
			<?php endforeach; ?>

			<?php if ( empty( $all_products ) ) : ?>
				<p style="grid-column:1/-1;text-align:center;color:var(--en-muted);padding:60px 0;">
					<?php esc_html_e( 'No products found. Add products in WooCommerce to display them here.', 'enhanced' ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .fp2-grid -->

		<div class="fp2-arrivals__footer">
			<a class="fp2-view-all" href="<?php echo esc_url( $shop_url ); ?>">
				<?php esc_html_e( 'View All Products', 'enhanced' ); ?>
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
					<path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>

	</div>
</section>
