<?php
/**
 * Single product template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();

		wc_get_template_part( 'content', 'single-product' );
	endwhile;
	?>
</main>

<?php get_footer(); ?>
