<?php
/**
 * Theme footer - compact storefront footer.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$footer_name = trim( (string) enhanced_get_option( 'footer_logo_text', '' ) );
$footer_name = '' === $footer_name ? get_bloginfo( 'name' ) : $footer_name;
$shop_url    = enhanced_shop_url();
?>

<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<a class="site-footer__brand-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo esc_html( $footer_name ); ?>
			</a>
			<p class="site-footer__tagline">
				<?php esc_html_e( 'Your one-stop shop for quality fashion and lifestyle products.', 'enhanced' ); ?>
			</p>
			<div class="site-footer__social" aria-label="<?php esc_attr_e( 'Social links', 'enhanced' ); ?>">
				<a href="#" aria-label="<?php esc_attr_e( 'Facebook', 'enhanced' ); ?>">f</a>
				<a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'enhanced' ); ?>">ig</a>
				<a href="#" aria-label="<?php esc_attr_e( 'X', 'enhanced' ); ?>">x</a>
			</div>
		</div>

		<nav class="site-footer__col" aria-label="<?php esc_attr_e( 'Shop', 'enhanced' ); ?>">
			<h2><?php esc_html_e( 'Shop', 'enhanced' ); ?></h2>
			<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'All Products', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( 'orderby', 'date', $shop_url ) ); ?>"><?php esc_html_e( 'New Arrivals', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( 'orderby', 'popularity', $shop_url ) ); ?>"><?php esc_html_e( 'Best Sellers', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( 'orderby', 'price', $shop_url ) ); ?>"><?php esc_html_e( 'Sale', 'enhanced' ); ?></a>
		</nav>

		<nav class="site-footer__col" aria-label="<?php esc_attr_e( 'Help', 'enhanced' ); ?>">
			<h2><?php esc_html_e( 'Help', 'enhanced' ); ?></h2>
			<a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/shipping/' ) ); ?>"><?php esc_html_e( 'Shipping', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/returns/' ) ); ?>"><?php esc_html_e( 'Returns', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'enhanced' ); ?></a>
		</nav>

		<nav class="site-footer__col" aria-label="<?php esc_attr_e( 'Account', 'enhanced' ); ?>">
			<h2><?php esc_html_e( 'Account', 'enhanced' ); ?></h2>
			<a href="<?php echo esc_url( enhanced_account_url() ); ?>"><?php esc_html_e( 'My Account', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( enhanced_account_url() ); ?>"><?php esc_html_e( 'Orders', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>"><?php esc_html_e( 'Wishlist', 'enhanced' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/track-order/' ) ); ?>"><?php esc_html_e( 'Track Order', 'enhanced' ); ?></a>
		</nav>
	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p class="site-footer__copy">
				<?php
				printf(
					/* translators: %1$s: current year, %2$s: site name. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'enhanced' ),
					esc_html( date_i18n( 'Y' ) ),
					esc_html( $footer_name )
				);
				?>
			</p>
			<div class="site-footer__payments" aria-label="<?php esc_attr_e( 'Accepted payments', 'enhanced' ); ?>">
				<span><?php esc_html_e( 'Visa', 'enhanced' ); ?></span>
				<span><?php esc_html_e( 'MC', 'enhanced' ); ?></span>
				<span><?php esc_html_e( 'Amex', 'enhanced' ); ?></span>
				<span><?php esc_html_e( 'PayPal', 'enhanced' ); ?></span>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
