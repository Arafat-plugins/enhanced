<?php
/**
 * Default page template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_intro       = enhanced_get_page_intro_data();
$is_commerce_page = enhanced_is_woo() && (
	( function_exists( 'is_cart' ) && is_cart() ) ||
	( function_exists( 'is_checkout' ) && is_checkout() ) ||
	( function_exists( 'is_account_page' ) && is_account_page() )
);
?>

<div class="page-banner<?php echo $is_commerce_page ? ' page-banner--commerce' : ''; ?>">
	<div class="container page-banner__inner<?php echo $is_commerce_page ? ' page-banner__inner--split' : ''; ?>">
		<div>
			<?php if ( $page_intro['eyebrow'] ) : ?>
				<span class="section-heading__eyebrow"><?php echo esc_html( $page_intro['eyebrow'] ); ?></span>
			<?php endif; ?>
			<h1 class="page-banner__title"><?php echo esc_html( $page_intro['title'] ); ?></h1>
			<?php if ( $page_intro['description'] ) : ?>
				<p class="page-banner__desc"><?php echo esc_html( $page_intro['description'] ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $is_commerce_page && ! empty( $page_intro['highlights'] ) ) : ?>
			<div class="page-banner__highlights">
				<?php foreach ( $page_intro['highlights'] as $highlight ) : ?>
					<span><?php echo esc_html( $highlight ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<main id="primary" class="site-main">
	<div class="container page-shell<?php echo $is_commerce_page ? ' page-shell--commerce' : ''; ?>">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( $is_commerce_page ) : ?>
				<div class="commerce-layout">
					<aside class="commerce-layout__aside">
						<div class="commerce-note-card">
							<span class="section-heading__eyebrow"><?php esc_html_e( 'Why this feels better', 'enhanced' ); ?></span>
							<h2><?php esc_html_e( 'A cleaner commerce shell for important customer actions.', 'enhanced' ); ?></h2>
							<p><?php esc_html_e( 'This theme update gives WooCommerce account, cart, and checkout screens stronger spacing, better readability, and a more premium layout rhythm across breakpoints.', 'enhanced' ); ?></p>
						</div>

						<div class="commerce-note-card commerce-note-card--muted">
							<ul class="commerce-note-list">
								<li><?php esc_html_e( 'Better stacked layout on tablets and phones', 'enhanced' ); ?></li>
								<li><?php esc_html_e( 'More balanced form spacing and order summaries', 'enhanced' ); ?></li>
								<li><?php esc_html_e( 'A storefront flow that feels intentional instead of plugin-default', 'enhanced' ); ?></li>
							</ul>
						</div>
					</aside>

					<div class="commerce-layout__content entry-content">
						<?php the_content(); ?>
					</div>
				</div>
			<?php else : ?>
				<article class="page-content-shell entry-content">
					<?php the_content(); ?>
				</article>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</main>

<?php get_footer(); ?>
