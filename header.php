<?php
/**
 * Theme header — utility bar + main nav.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$util_text = enhanced_get_option( 'utility_text', __( 'Free shipping on orders over $100', 'enhanced' ) );
$util_link = enhanced_get_option( 'utility_link', '' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'enhanced' ); ?></a>

<!-- ── Utility bar ────────────────────────────────────────── -->
<div class="utility-bar">
	<div class="container utility-bar__inner">
		<div class="utility-bar__left">
			<div class="utility-bar__icon-links">
				<a href="mailto:hello@enhanced.store">✉ hello@enhanced.store</a>
				<a href="tel:+10000000000">✆ +1 000 000 0000</a>
			</div>
		</div>

		<div class="utility-bar__promo">
			<?php if ( $util_link ) : ?>
				<a href="<?php echo esc_url( $util_link ); ?>"><?php echo esc_html( $util_text ); ?></a>
			<?php else : ?>
				<?php echo esc_html( $util_text ); ?>
			<?php endif; ?>
		</div>

		<div class="utility-bar__right">
			<?php if ( has_nav_menu( 'utility' ) ) : ?>
				<?php wp_nav_menu( array(
					'theme_location' => 'utility',
					'menu_class'     => 'utility-nav',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
					'items_wrap'     => '<nav class="utility-bar__icon-links">%3$s</nav>',
					'walker'         => null,
				) ); ?>
			<?php else : ?>
				<a class="utility-bar__link" href="<?php echo esc_url( enhanced_account_url() ); ?>">
					<svg width="13" height="13" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5.5" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
					<?php esc_html_e( 'My Account', 'enhanced' ); ?>
				</a>
				<a class="utility-bar__link" href="<?php echo esc_url( enhanced_cart_url() ); ?>">
					<?php esc_html_e( 'Cart', 'enhanced' ); ?>
					(<?php echo esc_html( enhanced_cart_count() ); ?>)
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- ── Main header ───────────────────────────────────────── -->
<header class="site-header" data-site-header>
	<div class="container site-header__inner">

		<!-- Mobile toggle -->
		<button
			class="site-header__toggle"
			type="button"
			aria-expanded="false"
			aria-controls="primary-nav"
			data-menu-toggle
		>
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'enhanced' ); ?></span>
			<span></span><span></span><span></span>
		</button>

		<!-- Logo -->
		<div class="site-header__logo">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<svg class="site-header__logo-icon" viewBox="0 0 28 28" fill="none" aria-hidden="true">
						<path d="M14 2l3.09 8.26L24 12l-6.91 1.74L14 22l-3.09-8.26L4 12l6.91-1.74L14 2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
					</svg>
					<span class="site-header__logo-text"><?php bloginfo( 'name' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<!-- Primary nav -->
		<nav id="primary-nav" class="site-header__nav" data-menu-panel aria-label="<?php esc_attr_e( 'Primary', 'enhanced' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_class'     => 'primary-nav',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => false,
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'after'          => '',
				) );
			} else {
				// Fallback
				echo '<ul class="primary-nav">';
				echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'enhanced' ) . '</a></li>';
				if ( enhanced_is_woo() ) {
					$shop_id = wc_get_page_id( 'shop' );
					if ( $shop_id > 0 ) {
						echo '<li><a href="' . esc_url( get_permalink( $shop_id ) ) . '">' . esc_html__( 'Shop', 'enhanced' ) . '</a></li>';
					}
				}
				echo '<li><a href="' . esc_url( home_url( '/collections/' ) ) . '">' . esc_html__( 'Collections', 'enhanced' ) . '</a></li>';
				echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html__( 'About', 'enhanced' ) . '</a></li>';
				echo '</ul>';
			}
			?>
		</nav>

		<!-- Actions -->
		<div class="site-header__actions">
			<button
				class="header-icon-btn"
				type="button"
				aria-label="<?php esc_attr_e( 'Search', 'enhanced' ); ?>"
				data-search-trigger
			>
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
					<circle cx="8" cy="8" r="5.5" stroke="currentColor" stroke-width="1.5"/>
					<path d="M12.5 12.5L16 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
			</button>

			<a class="header-icon-btn" href="<?php echo esc_url( enhanced_account_url() ); ?>" aria-label="<?php esc_attr_e( 'My Account', 'enhanced' ); ?>">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
					<circle cx="9" cy="6" r="3" stroke="currentColor" stroke-width="1.5"/>
					<path d="M3 16c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
			</a>

			<a class="header-icon-btn" href="<?php echo esc_url( enhanced_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'enhanced' ); ?>">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
					<path d="M4 6h10l-1 8H5L4 6Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
					<path d="M7 6V5a2 2 0 0 1 4 0v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<span class="header-cart__count" data-cart-count><?php echo esc_html( enhanced_cart_count() ); ?></span>
			</a>
		</div>

	</div>
</header>

<!-- ── Search drawer ─────────────────────────────────────── -->
<div class="search-drawer" id="search-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'enhanced' ); ?>" hidden data-search-drawer>
	<div class="search-drawer__backdrop" data-search-close></div>
	<div class="search-drawer__box">
		<form class="search-drawer__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<svg width="20" height="20" viewBox="0 0 18 18" fill="none" style="color:var(--en-muted);flex-shrink:0" aria-hidden="true">
				<circle cx="8" cy="8" r="5.5" stroke="currentColor" stroke-width="1.5"/>
				<path d="M12.5 12.5L16 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
			</svg>
			<input
				class="search-drawer__input"
				type="search"
				name="s"
				placeholder="<?php esc_attr_e( 'Search for products, brands, collections…', 'enhanced' ); ?>"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				autocomplete="off"
				data-search-input
			>
			<?php if ( enhanced_is_woo() ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<button class="search-drawer__submit" type="submit"><?php esc_html_e( 'Search', 'enhanced' ); ?></button>
			<button class="search-drawer__close" type="button" aria-label="<?php esc_attr_e( 'Close', 'enhanced' ); ?>" data-search-close>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
					<path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
				</svg>
			</button>
		</form>
	</div>
</div>
