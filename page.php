<?php
/**
 * Default page template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="page-banner">
	<div class="container">
		<h1 class="page-banner__title"><?php the_title(); ?></h1>
	</div>
</div>
<main id="primary" class="site-main">
	<div class="container" style="max-width:860px;padding-top:48px;padding-bottom:80px;">
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="entry-content"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
