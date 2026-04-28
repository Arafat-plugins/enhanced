<?php
/**
 * Blog section — 3-column grid with description in header.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $blog_posts ) ) return;

$blog_desc = enhanced_get_option(
	'blog_section_desc',
	__( 'Discover quality of our blog, that better reflects your style and makes everything living more enjoyable.', 'enhanced' )
);
?>

<section class="lx-blog fp-reveal">
	<div class="container">
		<div class="lx-sec-head lx-blog__head">
			<h2 class="lx-sec-title"><?php esc_html_e( 'From The Blog', 'enhanced' ); ?></h2>
			<?php if ( $blog_desc ) : ?>
				<p class="lx-blog__desc"><?php echo esc_html( $blog_desc ); ?></p>
			<?php endif; ?>
		</div>
		<div class="lx-blog-grid">
			<?php foreach ( $blog_posts as $i => $post ) :
				setup_postdata( $post );
				$thumb   = get_the_post_thumbnail_url( $post, 'enhanced-card' );
				$cats    = get_the_category( $post->ID );
				$cat     = ! empty( $cats ) ? $cats[0]->name : '';
				$excerpt = wp_trim_words( get_the_excerpt( $post ), 16, '...' );
			?>
				<article class="lx-blog-card fp-reveal fp-reveal--delay-<?php echo min( $i + 1, 3 ); ?>">
					<a class="lx-blog-card__img" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
						<?php if ( $thumb ) : ?>
							<img src="<?php echo esc_url( $thumb ); ?>"
								alt="<?php echo esc_attr( get_the_title( $post ) ); ?>"
								loading="lazy">
						<?php else : ?>
							<div class="lx-blog-card__fallback">
								<?php echo esc_html( get_the_title( $post ) ); ?>
							</div>
						<?php endif; ?>
					</a>
					<div class="lx-blog-card__body">
						<?php if ( $cat ) : ?>
							<span class="lx-blog-card__cat"><?php echo esc_html( $cat ); ?></span>
						<?php endif; ?>
						<h3 class="lx-blog-card__title">
							<a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
								<?php echo esc_html( get_the_title( $post ) ); ?>
							</a>
						</h3>
						<?php if ( $excerpt ) : ?>
							<p class="lx-blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
						<?php endif; ?>
						<a class="lx-blog-card__details" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
							<?php esc_html_e( 'Read More', 'enhanced' ); ?>
						</a>
					</div>
				</article>
			<?php endforeach;
			wp_reset_postdata(); ?>
		</div>
	</div>
</section>
