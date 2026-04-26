<?php
/**
 * Custom shop sidebar — search + category + color + size + price filters.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$active      = enhanced_get_active_filters();
$active_n    = enhanced_count_active_filters();
$categories  = enhanced_get_product_categories();
$colors      = enhanced_get_color_options();
$sizes       = enhanced_get_size_options();
list( $price_min, $price_max ) = enhanced_get_price_range();
$category_open = ! empty( $active['category'] );

$cur_min = $active['min'] !== null ? max( $price_min, (int) $active['min'] ) : $price_min;
$cur_max = $active['max'] !== null ? min( $price_max, (int) $active['max'] ) : $price_max;

$shop_url = enhanced_is_woo() ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
$clear_url = remove_query_arg(
	function_exists( 'enhanced_get_shop_clear_query_args' )
		? enhanced_get_shop_clear_query_args()
		: array( 's', 'filter_cat', 'filter_color', 'filter_size', 'min_price', 'max_price', 'paged', 'enhanced_shop_ajax' )
);
?>

<aside id="shop-sidebar" class="en-filters" aria-label="<?php esc_attr_e( 'Product filters', 'enhanced' ); ?>" data-filter-root>

	<!-- Sidebar header -->
	<header class="en-filters__head">
		<div class="en-filters__title-wrap">
			<span class="en-filters__icon" aria-hidden="true">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M1.5 3h13M4 8h8M6.5 13h3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
			</span>
			<h2 class="en-filters__title"><?php esc_html_e( 'Refine', 'enhanced' ); ?></h2>
			<?php if ( $active_n > 0 ) : ?>
				<span class="en-filters__badge"><?php echo esc_html( $active_n ); ?></span>
			<?php endif; ?>
		</div>
		<button type="button" class="en-filters__close" data-sidebar-close aria-label="<?php esc_attr_e( 'Close filters', 'enhanced' ); ?>">
			<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
		</button>
	</header>

	<!-- Live search -->
	<form class="en-search" role="search" method="get" action="<?php echo esc_url( $shop_url ); ?>" data-filter-search>
		<label class="en-search__label" for="en-shop-search">
			<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
				<circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.6"/>
				<path d="M14 14l-3.2-3.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
			</svg>
		</label>
		<input
			id="en-shop-search"
			class="en-search__input"
			type="search"
			name="s"
			value="<?php echo esc_attr( $active['search'] ); ?>"
			placeholder="<?php esc_attr_e( 'Search products…', 'enhanced' ); ?>"
			autocomplete="off"
		>
		<?php foreach ( array( 'filter_cat', 'filter_color', 'filter_size' ) as $carry ) :
			if ( empty( $_GET[ $carry ] ) ) continue;
			foreach ( (array) $_GET[ $carry ] as $v ) : ?>
				<input type="hidden" name="<?php echo esc_attr( $carry ); ?>[]" value="<?php echo esc_attr( wp_unslash( $v ) ); ?>">
			<?php endforeach;
		endforeach; ?>
		<?php if ( ! empty( $_GET['orderby'] ) ) : ?>
			<input type="hidden" name="orderby" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) ); ?>">
		<?php endif; ?>
		<?php if ( $active['min'] !== null ) : ?>
			<input type="hidden" name="min_price" value="<?php echo esc_attr( $active['min'] ); ?>">
		<?php endif; ?>
		<?php if ( $active['max'] !== null ) : ?>
			<input type="hidden" name="max_price" value="<?php echo esc_attr( $active['max'] ); ?>">
		<?php endif; ?>
		<?php if ( $active['search'] ) : ?>
			<button type="button" class="en-search__clear" data-search-clear aria-label="<?php esc_attr_e( 'Clear search', 'enhanced' ); ?>">
				<svg width="12" height="12" viewBox="0 0 16 16" fill="none"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
			</button>
		<?php endif; ?>
	</form>

	<!-- Active chips -->
	<?php if ( $active_n > 0 ) : ?>
	<div class="en-chips" aria-label="<?php esc_attr_e( 'Active filters', 'enhanced' ); ?>">
		<?php if ( $active['search'] ) : ?>
			<a class="en-chip" href="<?php echo esc_url( enhanced_filter_remove_url( 'search' ) ); ?>">
				<span class="en-chip__k"><?php esc_html_e( 'Search', 'enhanced' ); ?>:</span>
				<span class="en-chip__v"><?php echo esc_html( $active['search'] ); ?></span>
				<svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</a>
		<?php endif; ?>

		<?php foreach ( $active['category'] as $slug ) :
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( ! $term ) continue; ?>
			<a class="en-chip" href="<?php echo esc_url( enhanced_filter_remove_url( 'category', $slug ) ); ?>">
				<span class="en-chip__v"><?php echo esc_html( $term->name ); ?></span>
				<svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</a>
		<?php endforeach; ?>

		<?php foreach ( $active['color'] as $cname ) :
			$sw = $colors[ $cname ] ?? '#ccc'; ?>
			<a class="en-chip" href="<?php echo esc_url( enhanced_filter_remove_url( 'color', $cname ) ); ?>">
				<span class="en-chip__dot" style="background:<?php echo esc_attr( $sw ); ?>"></span>
				<span class="en-chip__v"><?php echo esc_html( $cname ); ?></span>
				<svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</a>
		<?php endforeach; ?>

		<?php foreach ( $active['size'] as $sname ) : ?>
			<a class="en-chip" href="<?php echo esc_url( enhanced_filter_remove_url( 'size', $sname ) ); ?>">
				<span class="en-chip__v"><?php echo esc_html( $sname ); ?></span>
				<svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</a>
		<?php endforeach; ?>

		<?php if ( $active['min'] !== null || $active['max'] !== null ) : ?>
			<a class="en-chip" href="<?php echo esc_url( enhanced_filter_remove_url( 'price' ) ); ?>">
				<span class="en-chip__v">
					<?php echo wp_kses_post( wc_price( $cur_min ) ); ?> – <?php echo wp_kses_post( wc_price( $cur_max ) ); ?>
				</span>
				<svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</a>
		<?php endif; ?>

		<a class="en-chips__clear" href="<?php echo esc_url( $clear_url ); ?>"><?php esc_html_e( 'Clear all', 'enhanced' ); ?></a>
	</div>
	<?php endif; ?>

	<!-- Groups -->
	<div class="en-filters__groups">

		<!-- Category -->
		<?php if ( ! empty( $categories ) ) : ?>
		<section class="en-group" data-open="<?php echo $category_open ? 'true' : 'false'; ?>">
			<button type="button" class="en-group__head" aria-expanded="<?php echo $category_open ? 'true' : 'false'; ?>">
				<span class="en-group__label"><?php esc_html_e( 'Category', 'enhanced' ); ?></span>
				<span class="en-group__count"><?php echo count( $categories ); ?></span>
				<svg class="en-group__chev" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5l3 3 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
			<div class="en-group__body">
				<ul class="en-list">
					<?php foreach ( $categories as $term ) :
						$checked = in_array( $term->slug, $active['category'], true );
						$href    = enhanced_filter_toggle_url( 'category', $term->slug ); ?>
						<li class="en-list__item<?php echo $checked ? ' is-active' : ''; ?>">
							<a href="<?php echo esc_url( $href ); ?>" class="en-checkbox">
								<span class="en-checkbox__box" aria-hidden="true">
									<svg width="10" height="10" viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
								</span>
								<span class="en-checkbox__name"><?php echo esc_html( $term->name ); ?></span>
								<span class="en-checkbox__count"><?php echo (int) $term->count; ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php endif; ?>

		<!-- Color -->
		<?php if ( ! empty( $colors ) ) : ?>
		<section class="en-group" data-open="true">
			<button type="button" class="en-group__head" aria-expanded="true">
				<span class="en-group__label"><?php esc_html_e( 'Color', 'enhanced' ); ?></span>
				<span class="en-group__count"><?php echo count( $colors ); ?></span>
				<svg class="en-group__chev" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5l3 3 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
			<div class="en-group__body">
				<div class="en-swatches">
					<?php foreach ( $colors as $cname => $hex ) :
						$active_color = in_array( $cname, $active['color'], true );
						$href         = enhanced_filter_toggle_url( 'color', $cname );
						$is_light     = in_array( strtolower( $hex ), array( '#ffffff', '#fff', '#f5e6d3', '#d4c5a1' ), true ); ?>
						<a class="en-swatch<?php echo $active_color ? ' is-active' : ''; ?><?php echo $is_light ? ' is-light' : ''; ?>"
						   href="<?php echo esc_url( $href ); ?>"
						   title="<?php echo esc_attr( $cname ); ?>"
						   aria-label="<?php echo esc_attr( $cname ); ?>">
							<span class="en-swatch__dot" style="background:<?php echo esc_attr( $hex ); ?>"></span>
							<span class="en-swatch__name"><?php echo esc_html( $cname ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<!-- Price -->
		<section class="en-group" data-open="true">
			<button type="button" class="en-group__head" aria-expanded="true">
				<span class="en-group__label"><?php esc_html_e( 'Price', 'enhanced' ); ?></span>
				<svg class="en-group__chev" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5l3 3 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
			<div class="en-group__body">
				<form class="en-price"
				      method="get"
				      action="<?php echo esc_url( $shop_url ); ?>"
				      data-price-form
				      data-min="<?php echo esc_attr( $price_min ); ?>"
				      data-max="<?php echo esc_attr( $price_max ); ?>"
				      data-cur-min="<?php echo esc_attr( $cur_min ); ?>"
				      data-cur-max="<?php echo esc_attr( $cur_max ); ?>"
				      data-currency="<?php echo esc_attr( function_exists( 'get_woocommerce_currency_symbol' ) ? html_entity_decode( get_woocommerce_currency_symbol() ) : '$' ); ?>">

					<div class="en-price__track" data-price-track>
						<div class="en-price__rail"></div>
						<div class="en-price__fill" data-price-fill></div>
						<button type="button" class="en-price__handle en-price__handle--min" data-price-handle="min" aria-label="<?php esc_attr_e( 'Minimum price', 'enhanced' ); ?>"></button>
						<button type="button" class="en-price__handle en-price__handle--max" data-price-handle="max" aria-label="<?php esc_attr_e( 'Maximum price', 'enhanced' ); ?>"></button>
					</div>

					<div class="en-price__values">
						<div class="en-price__field">
							<span class="en-price__field-label"><?php esc_html_e( 'Min', 'enhanced' ); ?></span>
							<span class="en-price__field-value" data-price-display="min">
								<?php echo wp_kses_post( wc_price( $cur_min ) ); ?>
							</span>
						</div>
						<div class="en-price__divider"></div>
						<div class="en-price__field">
							<span class="en-price__field-label"><?php esc_html_e( 'Max', 'enhanced' ); ?></span>
							<span class="en-price__field-value" data-price-display="max">
								<?php echo wp_kses_post( wc_price( $cur_max ) ); ?>
							</span>
						</div>
					</div>

					<input type="hidden" name="min_price" value="<?php echo esc_attr( $cur_min ); ?>" data-price-input="min">
					<input type="hidden" name="max_price" value="<?php echo esc_attr( $cur_max ); ?>" data-price-input="max">

					<?php if ( $active['search'] ) : ?>
						<input type="hidden" name="s" value="<?php echo esc_attr( $active['search'] ); ?>">
					<?php endif; ?>
					<?php foreach ( array( 'filter_cat', 'filter_color', 'filter_size' ) as $carry ) :
						if ( empty( $_GET[ $carry ] ) ) continue;
						foreach ( (array) $_GET[ $carry ] as $v ) : ?>
							<input type="hidden" name="<?php echo esc_attr( $carry ); ?>[]" value="<?php echo esc_attr( wp_unslash( $v ) ); ?>">
						<?php endforeach;
					endforeach; ?>
					<?php if ( ! empty( $_GET['orderby'] ) ) : ?>
						<input type="hidden" name="orderby" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) ); ?>">
					<?php endif; ?>

					<button type="submit" class="en-price__apply"><?php esc_html_e( 'Apply', 'enhanced' ); ?></button>
				</form>
			</div>
		</section>

		<!-- Size -->
		<?php if ( ! empty( $sizes ) ) : ?>
		<section class="en-group" data-open="true">
			<button type="button" class="en-group__head" aria-expanded="true">
				<span class="en-group__label"><?php esc_html_e( 'Size', 'enhanced' ); ?></span>
				<span class="en-group__count"><?php echo count( $sizes ); ?></span>
				<svg class="en-group__chev" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 4.5l3 3 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
			<div class="en-group__body">
				<div class="en-pills">
					<?php foreach ( $sizes as $sname ) :
						$active_size = in_array( $sname, $active['size'], true );
						$href        = enhanced_filter_toggle_url( 'size', $sname ); ?>
						<a class="en-pill<?php echo $active_size ? ' is-active' : ''; ?>" href="<?php echo esc_url( $href ); ?>">
							<?php echo esc_html( $sname ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php endif; ?>

	</div>

	<?php if ( $active_n > 0 ) : ?>
	<footer class="en-filters__foot">
		<a class="en-filters__reset" href="<?php echo esc_url( $clear_url ); ?>">
			<svg width="12" height="12" viewBox="0 0 16 16" fill="none" aria-hidden="true">
				<path d="M3 8a5 5 0 1 0 1.5-3.5M3 3v3h3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<?php esc_html_e( 'Reset all filters', 'enhanced' ); ?>
		</a>
	</footer>
	<?php endif; ?>

</aside>
