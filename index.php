<?php
/**
 * Fallback template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="page-banner">
	<div class="container">
		<h1 class="page-banner__title"><?php single_post_title(); ?></h1>
	</div>
</div>
<main id="primary" class="site-main">
	<div class="container" style="padding-top:40px;padding-bottom:80px;">
		<?php if ( have_posts() ) : ?>
			<div class="fp-section__grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'product-card' ); ?> style="display:block;">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="product-card__media">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'enhanced-card' ); ?></a>
							</div>
						<?php endif; ?>
						<div class="product-card__body">
							<h2 class="product-card__name" style="font-size:16px;">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<div style="font-size:13px;color:var(--en-muted);"><?php the_excerpt(); ?></div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No content found.', 'enhanced' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
