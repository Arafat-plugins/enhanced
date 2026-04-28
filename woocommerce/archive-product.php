<?php
/**
 * Shop archive — custom sidebar filters + 3-col grid.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$shop_title    = woocommerce_page_title( false );
$archive_desc  = wp_strip_all_tags( get_the_archive_description() );
$product_total = isset( $GLOBALS['wp_query']->found_posts ) ? (int) $GLOBALS['wp_query']->found_posts : 0;

get_header();
?>

<div class="page-banner">
	<div class="container page-banner__inner">
		<div>
			<p class="breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'enhanced' ); ?></a>
				<span class="breadcrumb__sep">›</span>
				<span><?php echo esc_html( $shop_title ); ?></span>
			</p>
			<h1 class="page-banner__title"><?php echo esc_html( $shop_title ); ?></h1>
			<?php if ( $archive_desc ) : ?>
				<p class="page-banner__desc"><?php echo esc_html( $archive_desc ); ?></p>
			<?php endif; ?>
		</div>
		<?php echo wp_kses_post( enhanced_get_shop_page_count_html( $product_total ) ); ?>
	</div>
</div>

<main id="primary" class="site-main">
	<div class="container">
		<?php get_template_part( 'template-parts/shop-shell' ); ?>
	</div>
</main>

<?php get_footer(); ?>
