<?php
/**
 * Custom product tabs.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $product_tabs ) ) {
	return;
}
?>

<div class="product-tabs" data-product-tabs>
	<div class="product-tabs__nav" role="tablist" aria-label="<?php esc_attr_e( 'Product details', 'enhanced' ); ?>">
		<?php $index = 0; ?>
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
			<?php
			$is_active = 0 === $index;
			$tab_id    = 'product-tab-' . sanitize_html_class( $key );
			$panel_id  = 'product-panel-' . sanitize_html_class( $key );
			?>
			<button
				class="product-tabs__tab<?php echo $is_active ? ' is-active' : ''; ?>"
				type="button"
				role="tab"
				id="<?php echo esc_attr( $tab_id ); ?>"
				aria-controls="<?php echo esc_attr( $panel_id ); ?>"
				aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
			>
				<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
			</button>
			<?php $index++; ?>
		<?php endforeach; ?>
	</div>

	<?php $index = 0; ?>
	<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
		<?php
		$is_active = 0 === $index;
		$tab_id    = 'product-tab-' . sanitize_html_class( $key );
		$panel_id  = 'product-panel-' . sanitize_html_class( $key );
		?>
		<div
			class="product-tabs__panel<?php echo $is_active ? ' is-active' : ''; ?>"
			role="tabpanel"
			id="<?php echo esc_attr( $panel_id ); ?>"
			aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
			<?php echo $is_active ? '' : 'hidden'; ?>
		>
			<?php
			if ( isset( $product_tab['callback'] ) ) {
				call_user_func( $product_tab['callback'], $key, $product_tab );
			}
			?>
		</div>
		<?php $index++; ?>
	<?php endforeach; ?>
</div>
