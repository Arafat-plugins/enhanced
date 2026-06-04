<?php
/**
 * WooCommerce integration.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_woocommerce_setup_hooks() {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
}
add_action( 'wp', 'enhanced_woocommerce_setup_hooks' );

// Remove default WooCommerce wrappers. Archive and single-product templates provide custom structure.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_filter( 'loop_shop_columns', function() {
	return 4;
} );

add_filter( 'loop_shop_per_page', function() {
	return 12;
} );

add_filter( 'woocommerce_show_page_title', '__return_false' );
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

function enhanced_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'enhanced_related_products_args' );

function enhanced_product_tabs( $tabs ) {
	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['priority'] = 5;
	}

	if ( isset( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['priority'] = 10;
	}

	if ( isset( $tabs['reviews'] ) ) {
		$tabs['reviews']['priority'] = 15;
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'enhanced_product_tabs', 20 );

/**
 * The theme renders its own product gallery, so Woo's default gallery assets are not needed.
 */
function enhanced_trim_single_product_gallery_assets() {
	if ( is_admin() || ! enhanced_is_woo() || ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	foreach ( array( 'photoswipe', 'photoswipe-default-skin' ) as $style_handle ) {
		wp_dequeue_style( $style_handle );
	}

	foreach ( array( 'wc-flexslider', 'wc-photoswipe', 'wc-photoswipe-ui-default', 'wc-single-product', 'wc-zoom' ) as $script_handle ) {
		wp_dequeue_script( $script_handle );
	}

	remove_action( 'wp_footer', 'woocommerce_photoswipe' );
}
add_action( 'wp_enqueue_scripts', 'enhanced_trim_single_product_gallery_assets', 1000 );

function enhanced_render_simple_product_selection_fields() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$fields = enhanced_get_simple_product_selection_fields( $product );

	if ( empty( $fields ) ) {
		return;
	}

	$submitted = isset( $_REQUEST['enhanced_simple_attributes'] ) && is_array( $_REQUEST['enhanced_simple_attributes'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		? wp_unslash( $_REQUEST['enhanced_simple_attributes'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		: array();
	?>
	<div class="simple-option-pickers" data-simple-option-pickers>
		<?php foreach ( $fields as $field ) : ?>
			<?php
			$current_value = isset( $submitted[ $field['key'] ] ) ? sanitize_text_field( $submitted[ $field['key'] ] ) : '';
			?>
			<div class="simple-option-picker" data-simple-option-picker>
				<span class="simple-option-picker__label"><?php echo esc_html( $field['label'] ); ?></span>
				<input
					type="hidden"
					name="enhanced_simple_attributes[<?php echo esc_attr( $field['key'] ); ?>]"
					value="<?php echo esc_attr( $current_value ); ?>"
					data-simple-attribute-input="<?php echo esc_attr( $field['key'] ); ?>"
				>
				<div class="variation-pills variation-pills--<?php echo esc_attr( $field['type'] ); ?>" role="group" aria-label="<?php echo esc_attr( $field['label'] ); ?>">
					<?php foreach ( $field['options'] as $option ) : ?>
						<?php
						$is_active    = $current_value === $option['value'];
						$button_class = 'variation-pills__button';

						if ( 'color' === $field['type'] ) {
							$button_class .= ' variation-pills__button--color';

							if ( enhanced_is_light_hex_color( $option['color'] ) ) {
								$button_class .= ' variation-pills__button--light';
							}
						} else {
							$button_class .= ' variation-pills__button--size';
						}

						if ( $is_active ) {
							$button_class .= ' is-active';
						}
						?>
						<button
							type="button"
							class="<?php echo esc_attr( $button_class ); ?>"
							data-simple-attribute-option="<?php echo esc_attr( $field['key'] ); ?>"
							data-value="<?php echo esc_attr( $option['value'] ); ?>"
							aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
							<?php if ( 'color' === $field['type'] ) : ?>
								aria-label="<?php echo esc_attr( $option['label'] ); ?>"
								title="<?php echo esc_attr( $option['label'] ); ?>"
							<?php endif; ?>
						>
							<?php if ( 'color' === $field['type'] ) : ?>
								<span class="variation-pills__swatch" style="--variation-swatch-color: <?php echo esc_attr( $option['color'] ); ?>;" aria-hidden="true"></span>
								<span class="screen-reader-text"><?php echo esc_html( $option['label'] ); ?></span>
							<?php else : ?>
								<?php echo esc_html( $option['label'] ); ?>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'enhanced_render_simple_product_selection_fields', 5 );

function enhanced_validate_simple_product_selection_fields( $passed, $product_id ) {
	$product = wc_get_product( $product_id );

	if ( ! $product instanceof WC_Product || ! $product->is_type( 'simple' ) ) {
		return $passed;
	}

	$fields = enhanced_get_simple_product_selection_fields( $product );

	if ( empty( $fields ) ) {
		return $passed;
	}

	$submitted = isset( $_REQUEST['enhanced_simple_attributes'] ) && is_array( $_REQUEST['enhanced_simple_attributes'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		? wp_unslash( $_REQUEST['enhanced_simple_attributes'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		: array();

	foreach ( $fields as $field ) {
		$value          = isset( $submitted[ $field['key'] ] ) ? sanitize_text_field( $submitted[ $field['key'] ] ) : '';
		$allowed_values = wp_list_pluck( $field['options'], 'value' );

		if ( '' === $value ) {
			wc_add_notice(
				sprintf(
					/* translators: %s attribute label */
					__( 'Please choose a %s.', 'enhanced' ),
					strtolower( $field['label'] )
				),
				'error'
			);

			return false;
		}

		if ( ! in_array( $value, $allowed_values, true ) ) {
			wc_add_notice(
				sprintf(
					/* translators: %s attribute label */
					__( 'Please choose a valid %s.', 'enhanced' ),
					strtolower( $field['label'] )
				),
				'error'
			);

			return false;
		}
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'enhanced_validate_simple_product_selection_fields', 10, 2 );

function enhanced_add_simple_product_selection_to_cart( $cart_item_data, $product_id ) {
	$product = wc_get_product( $product_id );

	if ( ! $product instanceof WC_Product || ! $product->is_type( 'simple' ) ) {
		return $cart_item_data;
	}

	$fields = enhanced_get_simple_product_selection_fields( $product );

	if ( empty( $fields ) ) {
		return $cart_item_data;
	}

	$submitted = isset( $_REQUEST['enhanced_simple_attributes'] ) && is_array( $_REQUEST['enhanced_simple_attributes'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		? wp_unslash( $_REQUEST['enhanced_simple_attributes'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		: array();
	$selected  = array();

	foreach ( $fields as $field ) {
		$value          = isset( $submitted[ $field['key'] ] ) ? sanitize_text_field( $submitted[ $field['key'] ] ) : '';
		$allowed_values = wp_list_pluck( $field['options'], 'value' );

		if ( '' === $value || ! in_array( $value, $allowed_values, true ) ) {
			continue;
		}

		$selected[ $field['label'] ] = $value;
	}

	if ( ! empty( $selected ) ) {
		$cart_item_data['enhanced_simple_attributes']     = $selected;
		$cart_item_data['enhanced_simple_attributes_key'] = md5( wp_json_encode( $selected ) );
	}

	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'enhanced_add_simple_product_selection_to_cart', 10, 2 );

function enhanced_display_simple_product_selection_in_cart( $item_data, $cart_item ) {
	if ( empty( $cart_item['enhanced_simple_attributes'] ) || ! is_array( $cart_item['enhanced_simple_attributes'] ) ) {
		return $item_data;
	}

	foreach ( $cart_item['enhanced_simple_attributes'] as $label => $value ) {
		$item_data[] = array(
			'key'   => $label,
			'value' => wc_clean( $value ),
		);
	}

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'enhanced_display_simple_product_selection_in_cart', 10, 2 );

function enhanced_add_simple_product_selection_to_order_items( $item, $cart_item_key, $values ) {
	if ( empty( $values['enhanced_simple_attributes'] ) || ! is_array( $values['enhanced_simple_attributes'] ) ) {
		return;
	}

	foreach ( $values['enhanced_simple_attributes'] as $label => $value ) {
		$item->add_meta_data( $label, wc_clean( $value ), true );
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'enhanced_add_simple_product_selection_to_order_items', 10, 3 );

/**
 * Inject wrapper divs to create a reliable 2-column checkout layout.
 * CSS Grid auto-placement cannot handle WooCommerce's mixed sibling DOM,
 * so we use hooks to add explicit wrappers.
 */
add_action(
	'woocommerce_checkout_before_customer_details',
	function () {
		echo '<div class="checkout-cols"><div class="checkout-col--form">';
	},
	5
);

// Priority 9999 runs after WooCommerce's own additional-fields output (priority 10).
add_action(
	'woocommerce_checkout_after_customer_details',
	function () {
		echo '</div><div class="checkout-col--summary">';
	},
	9999
);

// Priority 1000 runs after the order-review template (priority 10), closing both wrappers.
add_action(
	'woocommerce_order_review',
	function () {
		echo '</div></div>';
	},
	1000
);
