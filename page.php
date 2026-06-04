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
				<div class="commerce-content entry-content">
					<?php the_content(); ?>
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
