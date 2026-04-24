<?php
/**
 * Front page trust badges row.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$badges = array(
	array(
		'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true"><circle cx="16" cy="16" r="13" stroke="currentColor" stroke-width="1.5"/><path d="M10 16l4 4 8-8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'title' => __( '90 Days Return', 'enhanced' ),
		'sub'   => __( '5 days for free return', 'enhanced' ),
	),
	array(
		'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true"><rect x="3" y="11" width="26" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 11V9a9 9 0 0118 0v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M12 18h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
		'title' => __( 'Free Shipping', 'enhanced' ),
		'sub'   => __( 'Free shipping on order', 'enhanced' ),
	),
	array(
		'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true"><path d="M16 3C9.37 3 4 8.37 4 15c0 4.1 1.97 7.74 5 10.06V27l3.5-1.75A12 12 0 0016 27c6.63 0 12-5.37 12-12S22.63 3 16 3z" stroke="currentColor" stroke-width="1.5"/><path d="M12 13h2v6h-2zM18 13h2v6h-2z" fill="currentColor"/></svg>',
		'title' => __( '24/7 Support', 'enhanced' ),
		'sub'   => __( 'info@company.com', 'enhanced' ),
	),
	array(
		'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true"><rect x="6" y="8" width="20" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M16 8V6m-4 2V6m8 2V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M10 16h12M10 20h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
		'title' => __( 'Gift Card', 'enhanced' ),
		'sub'   => __( 'Gift card on purchase', 'enhanced' ),
	),
);
?>

<div class="fp2-trust">
	<div class="container">
		<div class="fp2-trust__grid">
			<?php foreach ( $badges as $badge ) : ?>
				<div class="fp2-trust__item fp-reveal">
					<div class="fp2-trust__icon"><?php echo $badge['icon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
					<div class="fp2-trust__text">
						<strong><?php echo esc_html( $badge['title'] ); ?></strong>
						<span><?php echo esc_html( $badge['sub'] ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
