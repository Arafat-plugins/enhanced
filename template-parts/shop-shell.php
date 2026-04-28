<?php
/**
 * Reusable shop shell: sidebar, toolbar, products, pagination.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$show_sidebar = get_theme_mod( 'enhanced_shop_sidebar', true );
$active_n     = enhanced_count_active_filters();
$clear_args   = function_exists( 'enhanced_get_shop_clear_query_args' )
	? enhanced_get_shop_clear_query_args()
	: array( 's', 'filter_cat', 'filter_color', 'filter_size', 'min_price', 'max_price', 'paged' );
?>

<div class="shop-shell <?php echo $show_sidebar ? '' : 'shop-shell--no-sidebar'; ?>" data-shop-shell>

	<?php if ( $show_sidebar ) : ?>
		<?php get_template_part( 'template-parts/shop-sidebar' ); ?>
		<div class="shop-sidebar-overlay" data-sidebar-overlay></div>
	<?php endif; ?>

	<div class="shop-content" data-shop-content>

		<div class="shop-toolbar">
			<div class="shop-toolbar__left">
				<?php if ( $show_sidebar ) : ?>
				<button class="shop-toolbar__filter-toggle" type="button" data-sidebar-toggle aria-expanded="false" aria-controls="shop-sidebar">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M1 3h14M4 8h8M7 13h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
					<?php esc_html_e( 'Filters', 'enhanced' ); ?>
					<?php if ( $active_n ) : ?>
						<span class="shop-toolbar__filter-badge"><?php echo esc_html( $active_n ); ?></span>
					<?php endif; ?>
				</button>
				<?php endif; ?>
				<div class="shop-toolbar__views" aria-label="<?php esc_attr_e( 'Product view', 'enhanced' ); ?>">
					<button class="shop-toolbar__view is-active" type="button" data-shop-view-button="grid" aria-label="<?php esc_attr_e( 'Grid view', 'enhanced' ); ?>" aria-pressed="true">
						<svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
							<path d="M2 2h4v4H2V2Zm7 0h4v4H9V2ZM2 9h4v4H2V9Zm7 0h4v4H9V9Z" stroke="currentColor" stroke-width="1.4"/>
						</svg>
					</button>
					<button class="shop-toolbar__view" type="button" data-shop-view-button="list" aria-label="<?php esc_attr_e( 'List view', 'enhanced' ); ?>" aria-pressed="false">
						<svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
							<path d="M2 3h11M2 7.5h11M2 12h11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
						</svg>
					</button>
				</div>
			</div>
			<div class="shop-toolbar__right">
				<?php woocommerce_catalog_ordering(); ?>
			</div>
		</div>

		<?php woocommerce_output_all_notices(); ?>

		<?php if ( woocommerce_product_loop() ) : ?>
			<div class="shop-product-grid is-grid-view" data-shop-products data-shop-view="grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>
				<?php endwhile; ?>
			</div>
			<?php do_action( 'woocommerce_after_shop_loop' ); ?>
		<?php else : ?>
			<div class="shop-empty">
				<svg width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true">
					<circle cx="22" cy="22" r="14" stroke="currentColor" stroke-width="2"/>
					<path d="M32 32l8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
				<h3 class="shop-empty__title"><?php esc_html_e( 'No products match your filters', 'enhanced' ); ?></h3>
				<p class="shop-empty__desc"><?php esc_html_e( 'Try removing some filters or clearing them all to see more products.', 'enhanced' ); ?></p>
				<?php if ( $active_n ) : ?>
					<a class="btn" href="<?php echo esc_url( remove_query_arg( $clear_args ) ); ?>">
						<?php esc_html_e( 'Clear all filters', 'enhanced' ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div><!-- .shop-content -->
</div><!-- .shop-shell -->
