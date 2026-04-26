<?php
/**
 * Theme footer — LUXINA: nav bar + giant logotype.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$footer_name = trim( (string) enhanced_get_option( 'footer_logo_text', '' ) );

if ( '' === $footer_name ) {
	$footer_name = get_bloginfo( 'name' );
}
?>

<footer class="site-footer">

	<!-- Nav row -->
	<div class="site-footer__nav-row">
		<div class="container site-footer__nav-inner">
			<?php
			$footer_menu_location = has_nav_menu( 'footer' ) ? 'footer' : 'primary';

			if ( has_nav_menu( $footer_menu_location ) ) {
				wp_nav_menu( array(
					'theme_location' => $footer_menu_location,
					'menu_class'     => 'footer-nav',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
					'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
				) );
			} else {
				$pages = wp_list_pages( array(
					'title_li' => '',
					'depth'    => 1,
					'echo'     => 0,
				) );
				if ( $pages ) {
					echo '<ul class="footer-nav">' . $pages . '</ul>';
				}
			}
			?>
		</div>
	</div>

	<!-- Giant logotype -->
	<p class="site-footer__logotype"><?php echo esc_html( $footer_name ); ?></p>

</footer>

<?php wp_footer(); ?>
</body>
</html>
