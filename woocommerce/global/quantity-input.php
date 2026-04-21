<?php
/**
 * Quantity input with plus and minus controls.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$inputmode    = isset( $inputmode ) ? $inputmode : 'numeric';
$autocomplete = isset( $autocomplete ) ? $autocomplete : 'off';

if ( $max_value && $min_value === $max_value ) :
	?>
	<div class="quantity hidden">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>">
	</div>
	<?php
else :
	$label = ! empty( $args['product_name'] )
		? sprintf(
			/* translators: %s product name */
			esc_html__( '%s quantity', 'woocommerce' ),
			wp_strip_all_tags( $args['product_name'] )
		)
		: esc_html__( 'Quantity', 'woocommerce' );
	?>
	<div class="quantity-wrap">
		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $label ); ?></label>
		<?php if ( ! $readonly ) : ?>
			<button type="button" class="quantity-wrap__btn" data-dir="minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'enhanced' ); ?>">-</button>
		<?php endif; ?>

		<input
			type="<?php echo esc_attr( $type ); ?>"
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ); ?>"
			aria-label="<?php echo esc_attr( $label ); ?>"
			<?php if ( in_array( $type, array( 'text', 'search', 'tel', 'url', 'email', 'password' ), true ) ) : ?>
				size="4"
			<?php endif; ?>
			min="<?php echo esc_attr( $min_value ); ?>"
			<?php if ( 0 < $max_value ) : ?>
				max="<?php echo esc_attr( $max_value ); ?>"
			<?php endif; ?>
			<?php if ( ! $readonly ) : ?>
				step="<?php echo esc_attr( $step ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				inputmode="<?php echo esc_attr( $inputmode ); ?>"
				autocomplete="<?php echo esc_attr( $autocomplete ); ?>"
			<?php else : ?>
				readonly="readonly"
			<?php endif; ?>
		>

		<?php if ( ! $readonly ) : ?>
			<button type="button" class="quantity-wrap__btn" data-dir="plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'enhanced' ); ?>">+</button>
		<?php endif; ?>
	</div>
<?php endif; ?>
