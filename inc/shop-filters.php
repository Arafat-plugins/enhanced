<?php
/**
 * Shop filter helpers — custom category / color / price / size logic.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

/**
 * Transient query args used only by the AJAX refresh layer.
 */
function enhanced_get_shop_transient_query_args() {
	return array( 'enhanced_shop_ajax' );
}

/**
 * Query args that should be cleared when resetting shop filters.
 */
function enhanced_get_shop_clear_query_args() {
	return array_merge(
		array( 's', 'filter_cat', 'filter_color', 'filter_size', 'min_price', 'max_price', 'paged' ),
		enhanced_get_shop_transient_query_args()
	);
}

/**
 * Get product categories with counts.
 */
function enhanced_get_product_categories() {
	if ( ! enhanced_is_woo() ) {
		return array();
	}
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
	) );
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Default color palette used across filters and product swatches.
 */
function enhanced_get_default_color_options() {
	return array(
		'Black'  => '#000000',
		'White'  => '#ffffff',
		'Red'    => '#e53e3e',
		'Blue'   => '#3b82f6',
		'Navy'   => '#1e3a8a',
		'Green'  => '#16a34a',
		'Olive'  => '#65a30d',
		'Yellow' => '#eab308',
		'Pink'   => '#ec4899',
		'Maroon' => '#9f1239',
		'Brown'  => '#8b5a2b',
		'Beige'  => '#d4c5a1',
		'Grey'   => '#6b7280',
		'Gray'   => '#6b7280',
		'Cream'  => '#f5e6d3',
		'Orange' => '#f97316',
		'Purple' => '#7c3aed',
	);
}

/**
 * Normalize a color label into a stable lookup key.
 */
function enhanced_normalize_color_key( $color_name ) {
	$color_name = strtolower( trim( html_entity_decode( wp_strip_all_tags( (string) $color_name ), ENT_QUOTES, get_bloginfo( 'charset' ) ) ) );
	$color_name = preg_replace( '/[^a-z0-9]+/', '-', $color_name );

	return trim( (string) $color_name, '-' );
}

/**
 * Get registered color attribute terms (from pa_color taxonomy).
 * Falls back to a sensible default palette if no attribute exists.
 */
function enhanced_get_color_options() {
	$defaults = enhanced_get_default_color_options();

	if ( ! enhanced_is_woo() || ! taxonomy_exists( 'pa_color' ) ) {
		return $defaults;
	}

	$terms = get_terms( array( 'taxonomy' => 'pa_color', 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return $defaults;
	}

	$out = array();
	foreach ( $terms as $t ) {
		// Pulls swatch value from term meta if set (e.g. via Woo's attribute color meta).
		$swatch        = get_term_meta( $t->term_id, 'product_attribute_color', true );
		$normalized    = enhanced_normalize_color_key( $t->name );
		$default_swatch = '#cccccc';

		foreach ( $defaults as $default_name => $default_hex ) {
			if ( enhanced_normalize_color_key( $default_name ) === $normalized ) {
				$default_swatch = $default_hex;
				break;
			}
		}

		$out[ $t->name ] = $swatch ?: $default_swatch;
	}
	return $out;
}

/**
 * Get pa_size terms or fallback sizes.
 */
function enhanced_get_size_options() {
	$defaults = array( 'XS', 'S', 'M', 'L', 'XL', 'XXL' );

	if ( ! enhanced_is_woo() || ! taxonomy_exists( 'pa_size' ) ) {
		return $defaults;
	}
	$terms = get_terms( array( 'taxonomy' => 'pa_size', 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return $defaults;
	}
	return wp_list_pluck( $terms, 'name' );
}

/**
 * Get product price range across all published products.
 */
function enhanced_get_price_range() {
	if ( ! enhanced_is_woo() ) {
		return array( 0, 10000 );
	}
	global $wpdb;
	$row = $wpdb->get_row(
		"SELECT MIN(CAST(meta_value AS DECIMAL(15,4))) AS min,
		        MAX(CAST(meta_value AS DECIMAL(15,4))) AS max
		 FROM {$wpdb->postmeta} pm
		 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		 WHERE pm.meta_key = '_price'
		 AND p.post_status = 'publish'
		 AND p.post_type IN ('product','product_variation')"
	);
	$min = $row ? (int) floor( (float) $row->min ) : 0;
	$max = $row ? (int) ceil(  (float) $row->max ) : 10000;
	if ( $max <= $min ) {
		$max = $min + 1000;
	}
	return array( $min, $max );
}

/**
 * Get currently active filters from the URL.
 */
function enhanced_get_active_filters() {
	return array(
		'search'   => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '',
		'category' => isset( $_GET['filter_cat'] ) ? array_filter( array_map( 'sanitize_title', (array) wp_unslash( $_GET['filter_cat'] ) ) ) : array(),
		'color'    => isset( $_GET['filter_color'] ) ? array_filter( array_map( 'sanitize_text_field', (array) wp_unslash( $_GET['filter_color'] ) ) ) : array(),
		'size'     => isset( $_GET['filter_size'] ) ? array_filter( array_map( 'sanitize_text_field', (array) wp_unslash( $_GET['filter_size'] ) ) ) : array(),
		'min'      => isset( $_GET['min_price'] ) ? (int) $_GET['min_price'] : null,
		'max'      => isset( $_GET['max_price'] ) ? (int) $_GET['max_price'] : null,
	);
}

/**
 * Count active filters for the "Clear" button badge.
 */
function enhanced_count_active_filters() {
	$a = enhanced_get_active_filters();
	$n = 0;
	if ( $a['search'] )    $n++;
	$n += count( $a['category'] );
	$n += count( $a['color'] );
	$n += count( $a['size'] );
	if ( $a['min'] !== null || $a['max'] !== null ) $n++;
	return $n;
}

/**
 * Apply filters to the shop archive product query.
 */
function enhanced_filter_shop_query( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) return;
	if ( ! ( $q->is_post_type_archive( 'product' ) || $q->is_tax( 'product_cat' ) || $q->is_tax( 'product_tag' ) ) ) return;

	$f = enhanced_get_active_filters();

	// Tax query — category + color + size.
	$tax = array();

	if ( ! empty( $f['category'] ) ) {
		$tax[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $f['category'],
			'operator' => 'IN',
		);
	}

	if ( ! empty( $f['color'] ) && taxonomy_exists( 'pa_color' ) ) {
		$tax[] = array(
			'taxonomy' => 'pa_color',
			'field'    => 'name',
			'terms'    => $f['color'],
			'operator' => 'IN',
		);
	}

	if ( ! empty( $f['size'] ) && taxonomy_exists( 'pa_size' ) ) {
		$tax[] = array(
			'taxonomy' => 'pa_size',
			'field'    => 'name',
			'terms'    => $f['size'],
			'operator' => 'IN',
		);
	}

	if ( count( $tax ) > 1 ) {
		$tax['relation'] = 'AND';
	}
	if ( ! empty( $tax ) ) {
		$q->set( 'tax_query', $tax );
	}

	// Price meta query.
	if ( $f['min'] !== null || $f['max'] !== null ) {
		$min = $f['min'] !== null ? $f['min'] : 0;
		$max = $f['max'] !== null ? $f['max'] : PHP_INT_MAX;
		$meta = (array) $q->get( 'meta_query' );
		$meta[] = array(
			'key'     => '_price',
			'value'   => array( $min, $max ),
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
		);
		$q->set( 'meta_query', $meta );
	}
}
add_action( 'pre_get_posts', 'enhanced_filter_shop_query' );

/**
 * Build an <a href> that toggles a filter value.
 */
function enhanced_filter_toggle_url( $key, $value ) {
	$active = enhanced_get_active_filters();
	$key_map = array(
		'category' => 'filter_cat',
		'color'    => 'filter_color',
		'size'     => 'filter_size',
	);
	$param = $key_map[ $key ] ?? $key;
	$current = (array) ( $active[ $key ] ?? array() );
	if ( in_array( $value, $current, true ) ) {
		$current = array_diff( $current, array( $value ) );
	} else {
		$current[] = $value;
	}
	$base = remove_query_arg( array_merge( array( $param, 'paged' ), enhanced_get_shop_transient_query_args() ) );
	if ( empty( $current ) ) {
		return $base;
	}
	return add_query_arg( array( $param => array_values( $current ) ), $base );
}

/**
 * URL with a single filter removed.
 */
function enhanced_filter_remove_url( $key, $value = null ) {
	$key_map = array(
		'category' => 'filter_cat',
		'color'    => 'filter_color',
		'size'     => 'filter_size',
		'search'   => 's',
		'price'    => array( 'min_price', 'max_price' ),
	);
	$target = $key_map[ $key ] ?? $key;

	if ( is_array( $target ) ) {
		return remove_query_arg( array_merge( $target, array( 'paged' ), enhanced_get_shop_transient_query_args() ) );
	}

	if ( $value === null || $key === 'search' || $key === 'price' ) {
		return remove_query_arg( array_merge( array( $target, 'paged' ), enhanced_get_shop_transient_query_args() ) );
	}

	$active = enhanced_get_active_filters();
	$current = (array) ( $active[ $key ] ?? array() );
	$current = array_values( array_diff( $current, array( $value ) ) );
	$base = remove_query_arg( array_merge( array( $target, 'paged' ), enhanced_get_shop_transient_query_args() ) );
	if ( empty( $current ) ) return $base;
	return add_query_arg( array( $target => $current ), $base );
}

/**
 * Count how many published products fall inside a single term.
 */
function enhanced_term_product_count( $taxonomy, $term_slug ) {
	$q = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'no_found_rows'  => false,
		'tax_query'      => array( array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $term_slug,
		) ),
	) );
	return (int) $q->found_posts;
}

/**
 * Remove transient AJAX-only query args from URLs rendered inside the shop UI.
 */
function enhanced_strip_shop_transient_args_from_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return $url;
	}

	return remove_query_arg( enhanced_get_shop_transient_query_args(), $url );
}
add_filter( 'woocommerce_product_add_to_cart_url', 'enhanced_strip_shop_transient_args_from_url' );

/**
 * Render the top-right count shown in the archive banner.
 */
function enhanced_get_shop_page_count_html( $product_total = null ) {
	if ( null === $product_total ) {
		$product_total = isset( $GLOBALS['wp_query']->found_posts ) ? (int) $GLOBALS['wp_query']->found_posts : 0;
	}

	if ( ! $product_total ) {
		return '';
	}

	ob_start();
	?>
	<p class="page-banner__count">
		<?php
		printf(
			/* translators: %d product count */
			esc_html( _n( '%d product', '%d products', $product_total, 'enhanced' ) ),
			$product_total
		);
		?>
	</p>
	<?php

	return ob_get_clean();
}

/**
 * Reuse the current shop template markup for the AJAX filter response.
 */
function enhanced_get_shop_shell_html() {
	ob_start();
	get_template_part( 'template-parts/shop-shell' );

	return ob_get_clean();
}

/**
 * JSON responder used by the enhanced shop AJAX layer.
 */
function enhanced_maybe_send_shop_ajax_response() {
	if ( is_admin() || ! enhanced_is_shop_context() || empty( $_GET['enhanced_shop_ajax'] ) ) {
		return;
	}

	unset( $_GET['enhanced_shop_ajax'], $_REQUEST['enhanced_shop_ajax'] );

	wp_send_json_success(
		array(
			'shopShell'     => enhanced_get_shop_shell_html(),
			'pageCountHtml' => enhanced_get_shop_page_count_html(),
		)
	);
}
add_action( 'template_redirect', 'enhanced_maybe_send_shop_ajax_response' );
