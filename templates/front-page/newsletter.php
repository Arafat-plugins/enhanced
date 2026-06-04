<?php
/**
 * Front page newsletter — LUXINA split layout.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$newsletter_desc = enhanced_get_option(
	'newsletter_desc',
	__( 'Discover quality fashion that reflects your style and makes everyday living more enjoyable.', 'enhanced' )
);
?>

<section class="fp2-newsletter fp-reveal">
	<div class="container">
		<div class="fp2-newsletter__inner">
			<div class="fp2-newsletter__left">
				<h2 class="fp2-newsletter__title"><?php esc_html_e( 'Subscribe Our Newsletters', 'enhanced' ); ?></h2>
			</div>
			<div class="fp2-newsletter__right">
				<p class="fp2-newsletter__sub"><?php echo esc_html( $newsletter_desc ); ?></p>
				<form class="fp2-newsletter__form" onsubmit="return false;" novalidate>
					<input
						class="fp2-newsletter__input"
						type="email"
						placeholder="<?php esc_attr_e( 'Your E-Mail Address', 'enhanced' ); ?>"
						required
						aria-label="<?php esc_attr_e( 'Email address', 'enhanced' ); ?>">
					<button class="fp2-newsletter__btn" type="submit">
						<?php esc_html_e( 'Subscribe', 'enhanced' ); ?>
					</button>
				</form>
			</div>
		</div>
	</div>
</section>
