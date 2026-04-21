<?php
/**
 * Front page.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url = enhanced_is_woo() ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
?>

<main id="primary" class="site-main">

	<!-- ── Hero ──────────────────────────────────────────── -->
	<section class="hero">
		<div class="hero__bg">
			<img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1600&q=80" alt="" loading="eager">
		</div>
		<div class="hero__overlay"></div>
		<div class="container">
			<div class="hero__content">
				<span class="hero__eyebrow"><?php esc_html_e( 'New Collection — Spring 2026', 'enhanced' ); ?></span>
				<h1 class="hero__title"><?php esc_html_e( 'New Collection', 'enhanced' ); ?></h1>
				<p class="hero__desc">
					<?php esc_html_e( 'Discover the latest styles with premium fabrics and timeless design. Browse our curated selection and find your perfect look.', 'enhanced' ); ?>
				</p>
				<div class="hero__actions">
					<a class="btn btn--accent" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop Now', 'enhanced' ); ?></a>
					<a class="btn btn--outline" style="color:#fff;border-color:rgba(255,255,255,0.5);" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'Our Story', 'enhanced' ); ?></a>
				</div>
			</div>
		</div>
	</section>

	<!-- ── Categories ────────────────────────────────────── -->
	<?php
	$categories = array();
	if ( enhanced_is_woo() ) {
		$categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 5,
			'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
		) );
	}
	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
	?>
	<section class="fp-categories">
		<div class="container">
			<div class="fp-categories__head">
				<h2 class="fp-categories__title"><?php esc_html_e( 'Shop by Category', 'enhanced' ); ?></h2>
				<a class="fp-categories__link" href="<?php echo esc_url( $shop_url ); ?>">
					<?php esc_html_e( 'View all', 'enhanced' ); ?>
					<svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6h8m0 0L7 3m3 3L7 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
			</div>
			<div class="fp-categories__grid">
				<?php foreach ( $categories as $term ) :
					$thumb_id  = get_term_meta( $term->term_id, 'thumbnail_id', true );
					$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'enhanced-card' ) : '';
					?>
					<a class="fp-cat-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
						<div class="fp-cat-card__img">
							<?php if ( $thumb_url ) : ?>
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy">
							<?php endif; ?>
						</div>
						<span class="fp-cat-card__name"><?php echo esc_html( $term->name ); ?></span>
						<span class="fp-cat-card__count">
							<?php printf( esc_html( _n( '%d item', '%d items', $term->count, 'enhanced' ) ), $term->count ); ?>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── New Arrivals ──────────────────────────────────── -->
	<?php
	if ( enhanced_is_woo() ) :
		$new_arrivals = wc_get_products( array(
			'status'  => 'publish',
			'limit'   => 8,
			'orderby' => 'date',
			'order'   => 'DESC',
		) );
		if ( ! empty( $new_arrivals ) ) :
	?>
	<section class="fp-section" style="background:var(--en-white);padding-block:56px;">
		<div class="container">
			<div class="fp-section__head">
				<h2 class="fp-section__title"><?php esc_html_e( 'New Arrivals', 'enhanced' ); ?></h2>
				<a class="fp-section__link" href="<?php echo esc_url( add_query_arg( 'orderby', 'date', $shop_url ) ); ?>">
					<?php esc_html_e( 'View all', 'enhanced' ); ?>
					<svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6h8m0 0L7 3m3 3L7 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
			</div>
			<div class="fp-section__grid">
				<?php foreach ( $new_arrivals as $product ) :
					$permalink  = get_permalink( $product->get_id() );
					$image_id   = $product->get_image_id();
					$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'enhanced-card' ) : wc_placeholder_img_src();
					$on_sale    = $product->is_on_sale();
					$is_new     = ( time() - strtotime( $product->get_date_created() ) ) < ( 21 * DAY_IN_SECONDS );
					$price_html = $product->get_price_html();
					$cats       = wp_strip_all_tags( wc_get_product_category_list( $product->get_id(), ', ' ) );
				?>
					<div class="product-card">
						<div class="product-card__media">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy">
							<div class="product-card__badges">
								<?php if ( $on_sale ) : ?>
									<span class="product-card__badge product-card__badge--sale"><?php esc_html_e( 'Sale', 'enhanced' ); ?></span>
								<?php elseif ( $is_new ) : ?>
									<span class="product-card__badge product-card__badge--new"><?php esc_html_e( 'New', 'enhanced' ); ?></span>
								<?php endif; ?>
							</div>
						</div>
						<div class="product-card__body">
							<div class="product-card__price-wrap">
								<?php if ( $price_html ) : ?>
									<span class="product-card__price"><?php echo wp_kses_post( $price_html ); ?></span>
								<?php endif; ?>
							</div>
							<h3 class="product-card__name">
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
							</h3>
							<?php if ( $cats ) : ?>
								<span class="product-card__brand"><?php echo esc_html( $cats ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; endif; ?>

	<!-- ── Promo banner ──────────────────────────────────── -->
	<section style="background:var(--en-ink);color:#fff;padding:56px 0;">
		<div class="container" style="text-align:center;max-width:720px;">
			<p style="font-size:11px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:rgba(255,255,255,0.6);margin-bottom:12px;">
				<?php esc_html_e( 'Limited offer', 'enhanced' ); ?>
			</p>
			<h2 style="font-family:var(--en-font-display);font-size:clamp(28px,4vw,46px);font-weight:400;margin:0 0 16px;color:#fff;">
				<?php esc_html_e( 'Up to 40% off selected styles.', 'enhanced' ); ?>
			</h2>
			<p style="color:rgba(255,255,255,0.65);margin-bottom:28px;">
				<?php esc_html_e( 'Shop the sale and discover end-of-season pieces at incredible prices. While stocks last.', 'enhanced' ); ?>
			</p>
			<a class="btn btn--accent" href="<?php echo esc_url( add_query_arg( 'orderby', 'price', $shop_url ) ); ?>"><?php esc_html_e( 'Shop the Sale', 'enhanced' ); ?></a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
