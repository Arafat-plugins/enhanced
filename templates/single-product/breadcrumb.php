<?php
/**
 * Single product breadcrumb.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;
?>

<p class="breadcrumb product-focus__breadcrumb">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'enhanced' ); ?></a>
	<span class="breadcrumb__sep">/</span>
	<a href="<?php echo esc_url( enhanced_shop_url() ); ?>"><?php esc_html_e( 'Shop', 'enhanced' ); ?></a>
	<?php if ( $primary_category ) : ?>
		<span class="breadcrumb__sep">/</span>
		<?php if ( ! is_wp_error( $primary_category_url ) && $primary_category_url ) : ?>
			<a href="<?php echo esc_url( $primary_category_url ); ?>"><?php echo esc_html( $primary_category ); ?></a>
		<?php else : ?>
			<span><?php echo esc_html( $primary_category ); ?></span>
		<?php endif; ?>
	<?php endif; ?>
	<span class="breadcrumb__sep">/</span>
	<span><?php the_title(); ?></span>
</p>
