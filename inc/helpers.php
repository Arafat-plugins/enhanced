<?php
/**
 * Helper functions.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

function enhanced_is_woo() {
	return class_exists( 'WooCommerce' );
}

function enhanced_is_shop_context() {
	if ( ! enhanced_is_woo() || ! function_exists( 'is_shop' ) ) {
		return false;
	}

	return is_shop() || is_product_taxonomy() || is_post_type_archive( 'product' );
}

function enhanced_should_load_woo_assets() {
	if ( ! enhanced_is_woo() || is_admin() ) {
		return false;
	}

	if ( enhanced_is_shop_context() ) {
		return true;
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		return true;
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		return true;
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return true;
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		return true;
	}

	return false;
}

function enhanced_get_option( $key, $default = '' ) {
	return get_theme_mod( 'enhanced_' . $key, $default );
}

function enhanced_shop_url() {
	if ( enhanced_is_woo() ) {
		$shop_id = wc_get_page_id( 'shop' );

		if ( $shop_id > 0 ) {
			return get_permalink( $shop_id );
		}
	}

	return home_url( '/shop/' );
}

function enhanced_cart_count() {
	if ( enhanced_is_woo() && function_exists( 'WC' ) && WC()->cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}
	return 0;
}

function enhanced_cart_url() {
	return enhanced_is_woo() ? wc_get_cart_url() : home_url( '/cart/' );
}

function enhanced_account_url() {
	return enhanced_is_woo() ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
}

function enhanced_get_product_primary_category_name( $product_id = 0 ) {
	if ( ! enhanced_is_woo() ) {
		return '';
	}

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$terms      = get_the_terms( $product_id, 'product_cat' );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	$default_category = (int) get_option( 'default_product_cat', 0 );

	foreach ( $terms as $term ) {
		if ( (int) $term->term_id === $default_category ) {
			continue;
		}

		return $term->name;
	}

	return $terms[0]->name;
}

function enhanced_get_product_secondary_image_url( $product, $size = 'enhanced-card' ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return '';
	}

	$gallery_ids = $product->get_gallery_image_ids();

	if ( empty( $gallery_ids ) ) {
		return '';
	}

	$secondary_image = wp_get_attachment_image_url( (int) $gallery_ids[0], $size );

	return $secondary_image ? $secondary_image : '';
}

function enhanced_get_product_gallery_images( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return array();
	}

	$image_ids = array_unique(
		array_filter(
			array_merge(
				array( (int) $product->get_image_id() ),
				array_map( 'intval', $product->get_gallery_image_ids() )
			)
		)
	);

	$images = array();

	foreach ( $image_ids as $image_id ) {
		$large = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
		$thumb = wp_get_attachment_image_url( $image_id, 'enhanced-thumb' );

		if ( ! $large || ! $thumb ) {
			continue;
		}

		$images[] = array(
			'id'    => $image_id,
			'large' => $large,
			'thumb' => $thumb,
			'alt'   => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
		);
	}

	if ( ! empty( $images ) ) {
		return $images;
	}

	return array(
		array(
			'id'    => 0,
			'large' => wc_placeholder_img_src( 'woocommerce_single' ),
			'thumb' => wc_placeholder_img_src( 'enhanced-thumb' ),
			'alt'   => $product->get_name(),
		),
	);
}

function enhanced_get_product_stock_badge( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return array(
			'class' => 'is-neutral',
			'label' => __( 'Available now', 'enhanced' ),
		);
	}

	if ( ! $product->is_in_stock() ) {
		return array(
			'class' => 'is-out',
			'label' => __( 'Currently unavailable', 'enhanced' ),
		);
	}

	if ( $product->is_on_backorder( 1 ) ) {
		return array(
			'class' => 'is-backorder',
			'label' => __( 'Available on backorder', 'enhanced' ),
		);
	}

	if ( $product->managing_stock() && null !== $product->get_stock_quantity() ) {
		$quantity = (int) $product->get_stock_quantity();

		if ( $quantity > 0 && $quantity <= 6 ) {
			return array(
				'class' => 'is-low',
				'label' => sprintf(
					/* translators: %d remaining stock quantity */
					__( 'Only %d left in stock', 'enhanced' ),
					$quantity
				),
			);
		}
	}

	return array(
		'class' => 'is-in',
		'label' => __( 'Ready to dispatch', 'enhanced' ),
	);
}

function enhanced_get_page_intro_data() {
	$title       = get_the_title();
	$eyebrow     = '';
	$description = '';
	$highlights  = array();

	if ( enhanced_is_woo() ) {
		if ( function_exists( 'is_cart' ) && is_cart() ) {
			$eyebrow     = __( 'Bag review', 'enhanced' );
			$title       = __( 'Your shopping bag', 'enhanced' );
			$description = __( 'Review your pieces, update quantities, and move to checkout with a cleaner premium flow on every screen size.', 'enhanced' );
			$highlights  = array(
				__( 'Flexible quantity controls', 'enhanced' ),
				__( 'Responsive totals summary', 'enhanced' ),
				__( 'Fast route to checkout', 'enhanced' ),
			);
		} elseif ( function_exists( 'is_checkout' ) && is_checkout() && ! is_order_received_page() ) {
			$eyebrow     = __( 'Secure checkout', 'enhanced' );
			$title       = __( 'Finish your order', 'enhanced' );
			$description = __( 'A calmer, more luxurious checkout with clear form groupings, secure payment messaging, and better mobile spacing.', 'enhanced' );
			$highlights  = array(
				__( 'Protected payment flow', 'enhanced' ),
				__( 'Clear order review panel', 'enhanced' ),
				__( 'Mobile-friendly fields', 'enhanced' ),
			);
		} elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
			$eyebrow     = __( 'Client account', 'enhanced' );
			$title       = __( 'Your personal account', 'enhanced' );
			$description = __( 'Track orders, manage addresses, and keep your customer dashboard feeling polished instead of looking like a default plugin page.', 'enhanced' );
			$highlights  = array(
				__( 'Better dashboard navigation', 'enhanced' ),
				__( 'Readable order history', 'enhanced' ),
				__( 'Comfortable mobile layout', 'enhanced' ),
			);
		}
	}

	if ( ! $description && has_excerpt() ) {
		$description = get_the_excerpt();
	}

	if ( ! $description ) {
		$content = wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', get_the_ID() ) ) );

		if ( $content ) {
			$description = wp_trim_words( $content, 26 );
		}
	}

	return array(
		'eyebrow'     => $eyebrow,
		'title'       => $title,
		'description' => $description,
		'highlights'  => $highlights,
	);
}

function enhanced_render_product_card( $product ) {
	if ( ! enhanced_is_woo() || ! $product instanceof WC_Product ) {
		return;
	}

	$previous_product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
	$GLOBALS['product'] = $product;

	wc_get_template_part( 'content', 'product' );

	if ( $previous_product instanceof WC_Product ) {
		$GLOBALS['product'] = $previous_product;
	} else {
		unset( $GLOBALS['product'] );
	}
}

/**
 * WooCommerce cart fragment for AJAX count update.
 */
function enhanced_cart_fragment( $fragments ) {
	$count = enhanced_cart_count();
	ob_start();
	?>
	<span class="header-cart__count" data-cart-count><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['span.header-cart__count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'enhanced_cart_fragment' );
