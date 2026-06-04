<?php
/**
 * The comments template.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area container">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();

			if ( 1 === (int) $comment_count ) {
				printf(
					/* translators: %s: post title. */
					esc_html__( 'One comment on "%s"', 'enhanced' ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count, 2: post title. */
					esc_html( _nx( '%1$s comment on "%2$s"', '%1$s comments on "%2$s"', $comment_count, 'comments title', 'enhanced' ) ),
					number_format_i18n( $comment_count ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 48,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	if ( ! comments_open() && get_comments_number() ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'enhanced' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>
