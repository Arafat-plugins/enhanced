<?php
/**
 * Theme header — LUXINA layout: logo | search | icons + nav bar.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

$contact_email  = enhanced_get_option( 'contact_email', 'hello@enhanced.store' );
$contact_phone  = enhanced_get_option( 'contact_phone', '+1 (800) 000-0000' );
$contact_mailto = $contact_email ? sanitize_email( $contact_email ) : '';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'enhanced' ); ?></a>

<div class="utility-bar">
	<div class="container utility-bar__inner">
		<p class="utility-bar__promo"><?php esc_html_e( 'Free shipping on orders over $50', 'enhanced' ); ?></p>
		<div class="utility-bar__right">
			<a class="utility-bar__link" href="<?php echo esc_url( enhanced_account_url() ); ?>">
				<?php esc_html_e( 'Account', 'enhanced' ); ?>
			</a>
			<a class="utility-bar__link" href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>">
				<?php esc_html_e( 'Wishlist', 'enhanced' ); ?>
			</a>
			<a class="utility-bar__link" href="<?php echo esc_url( enhanced_cart_url() ); ?>">
				<?php esc_html_e( 'Cart', 'enhanced' ); ?>
			</a>
		</div>
	</div>
</div>

<header class="site-header" data-site-header>

	<!-- Top row: logo | inline search | icons -->
	<div class="container site-header__inner">

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

		<div class="site-header__logo">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="site-header__logo-text"><?php bloginfo( 'name' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<!-- Inline visible search -->
		<form class="site-header__inline-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true">
				<circle cx="8" cy="8" r="5.5" stroke="currentColor" stroke-width="1.5"/>
				<path d="M12.5 12.5L16 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
			</svg>
			<input
				class="site-header__inline-search__input"
				type="search"
				name="s"
				placeholder="<?php esc_attr_e( 'Search', 'enhanced' ); ?>"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				autocomplete="off"
			>
			<?php if ( enhanced_is_woo() ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
		</form>

		<div class="site-header__actions">
			<a class="header-icon-btn" href="<?php echo esc_url( enhanced_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'enhanced' ); ?>">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
					<path d="M4 7h12l-1.5 9h-9L4 7Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
					<path d="M8 7V6a2 2 0 0 1 4 0v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<span class="header-cart__count" data-cart-count><?php echo esc_html( enhanced_cart_count() ); ?></span>
			</a>

			<a class="header-icon-btn header-icon-btn--account" href="<?php echo esc_url( enhanced_account_url() ); ?>" aria-label="<?php esc_attr_e( 'My Account', 'enhanced' ); ?>">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
					<circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/>
					<path d="M3 18c0-3.9 3.1-7 7-7s7 3.1 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<span class="header-account-label"><?php esc_html_e( 'Account', 'enhanced' ); ?></span>
			</a>
		</div>
	</div>

	<!-- Nav bar row -->
	<div class="site-header__nav-bar">
		<nav id="primary-nav" class="container site-header__nav" data-menu-panel aria-label="<?php esc_attr_e( 'Primary', 'enhanced' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_class'     => 'primary-nav',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => false,
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				) );
			} else {
				$pages = wp_list_pages( array(
					'title_li' => '',
					'depth'    => 2,
					'echo'     => 0,
				) );
				if ( $pages ) {
					echo '<ul class="primary-nav">' . $pages . '</ul>';
				}
			}
			?>
		</nav>
	</div>

</header>

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
				placeholder="<?php esc_attr_e( 'Search for products, brands, collections...', 'enhanced' ); ?>"
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
