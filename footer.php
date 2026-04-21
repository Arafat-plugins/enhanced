<?php
/**
 * Theme footer — 4-column widgets + large logotype.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$footer_name    = enhanced_get_option( 'footer_logo_text', get_bloginfo( 'name' ) );
$footer_tagline = enhanced_get_option( 'footer_tagline', __( 'Premium fashion — crafted for modern life.', 'enhanced' ) );
?>

<footer class="site-footer">
	<div class="site-footer__top">
		<div class="container">
			<div class="site-footer__top-inner">

				<div class="site-footer__brand-col">
					<?php if ( has_custom_logo() ) : ?>
						<div style="margin-bottom:14px;"><?php the_custom_logo(); ?></div>
					<?php else : ?>
						<p class="site-footer__brand-name"><?php echo esc_html( $footer_name ); ?></p>
					<?php endif; ?>
					<p class="site-footer__tagline"><?php echo esc_html( $footer_tagline ); ?></p>
				</div>

				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
						<div class="footer-widget-col">
							<?php dynamic_sidebar( 'footer-' . $i ); ?>
						</div>
					<?php else : ?>
						<div class="footer-widget-col">
							<div class="footer-widget">
								<h4 class="widget__title">
									<?php
									$titles = array(
										1 => __( 'Shop', 'enhanced' ),
										2 => __( 'Help', 'enhanced' ),
										3 => __( 'Company', 'enhanced' ),
										4 => __( 'Follow', 'enhanced' ),
									);
									echo esc_html( $titles[ $i ] ?? '' );
									?>
								</h4>
								<ul>
									<?php if ( $i === 1 ) : ?>
										<li><a href="<?php echo esc_url( enhanced_is_woo() ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'All Products', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'New Arrivals', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Best Sellers', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Sale', 'enhanced' ); ?></a></li>
									<?php elseif ( $i === 2 ) : ?>
										<li><a href="#"><?php esc_html_e( 'FAQs', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Shipping & Returns', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Size Guide', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Contact Us', 'enhanced' ); ?></a></li>
									<?php elseif ( $i === 3 ) : ?>
										<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Careers', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Press', 'enhanced' ); ?></a></li>
										<li><a href="#"><?php esc_html_e( 'Sustainability', 'enhanced' ); ?></a></li>
									<?php elseif ( $i === 4 ) : ?>
										<li><a href="#">Instagram</a></li>
										<li><a href="#">Pinterest</a></li>
										<li><a href="#">TikTok</a></li>
										<li><a href="#">Facebook</a></li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>
				<?php endfor; ?>

			</div>
		</div>
	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p class="site-footer__copy">
				&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?> &mdash;
				<?php esc_html_e( 'All rights reserved.', 'enhanced' ); ?>
			</p>
			<div class="site-footer__bottom-links">
				<a href="#"><?php esc_html_e( 'Privacy Policy', 'enhanced' ); ?></a>
				<a href="#"><?php esc_html_e( 'Terms of Service', 'enhanced' ); ?></a>
				<a href="#"><?php esc_html_e( 'Cookies', 'enhanced' ); ?></a>
			</div>
		</div>
	</div>

	<p class="site-footer__logotype container"><?php echo esc_html( $footer_name ); ?></p>
</footer>

<?php wp_footer(); ?>
</body>
</html>
