<?php
/**
 * Enhanced – Admin Settings Page.
 * Replaces Appearance → Customize with a dedicated top-level menu page.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

/* ── Menu registration ────────────────────────────────────── */

add_action( 'admin_menu', 'enhanced_register_admin_menu' );
function enhanced_register_admin_menu() {
	add_menu_page(
		__( 'Enhanced Settings', 'enhanced' ),
		__( 'Enhanced', 'enhanced' ),
		'manage_options',
		'enhanced-settings',
		'enhanced_admin_render_page',
		'dashicons-store',
		3
	);
}

/* ── Assets ───────────────────────────────────────────────── */

add_action( 'admin_enqueue_scripts', 'enhanced_admin_enqueue' );
function enhanced_admin_enqueue( $hook ) {
	if ( 'toplevel_page_enhanced-settings' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script(
		'enhanced-admin',
		ENHANCED_URI . 'assets/js/admin.js',
		array( 'jquery', 'wp-color-picker' ),
		ENHANCED_VERSION,
		true
	);
}

/* ── Save handler ─────────────────────────────────────────── */

add_action( 'admin_post_enhanced_save', 'enhanced_admin_save' );
function enhanced_admin_save() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'enhanced_save', 'enhanced_nonce' );

	$tab = isset( $_POST['enhanced_tab'] ) ? sanitize_key( $_POST['enhanced_tab'] ) : 'hero';

	/* Text / URL / textarea / number / select / color fields */
	$fields = array(
		// Header
		'utility_text'   => 'sanitize_text_field',
		'utility_link'   => 'esc_url_raw',
		'contact_email'  => 'sanitize_email',
		'contact_phone'  => 'sanitize_text_field',
		// Colors
		'accent_color'   => 'sanitize_hex_color',
		'utility_bg'     => 'sanitize_hex_color',
		'footer_bg'      => 'sanitize_hex_color',
		// Hero – left panel
		'hero_overlay_color'   => 'sanitize_hex_color',
		'hero_overlay_opacity' => 'absint',
		'hero_eyebrow'         => 'sanitize_text_field',
		'hero_discount'        => 'sanitize_text_field',
		'hero_description'     => 'sanitize_textarea_field',
		'hero_primary_label'   => 'sanitize_text_field',
		'hero_primary_url'     => 'esc_url_raw',
		'hero_secondary_label' => 'sanitize_text_field',
		'hero_secondary_url'   => 'esc_url_raw',
		'hero_media_type'      => 'sanitize_key',
		'hero_image'           => 'absint',
		'hero_video_upload'    => 'absint',
		'hero_video_url'       => 'esc_url_raw',
		// Right panel slides
		'rp_tr_interval' => 'absint',
		'rp_br_interval' => 'absint',
		'rp_tr_title'    => 'sanitize_text_field',
		'rp_br_title'    => 'sanitize_text_field',
		'rp_tr_opacity'  => 'absint',
		'rp_br_opacity'  => 'absint',
		// Browse categories
		'browse_model_image'      => 'absint',
		'browse_panel_bg'         => 'absint',
		'browse_overlay_opacity'  => 'floatval',
		// Sale Is On
		'sale_due_label' => 'sanitize_text_field',
		// Sale banner
		'sale_banner_eyebrow' => 'sanitize_text_field',
		'sale_banner_title'   => 'sanitize_text_field',
		'sale_banner_btn'     => 'sanitize_text_field',
		'sale_banner_url'     => 'esc_url_raw',
		'sale_banner_image'   => 'absint',
		// Blog / Newsletter
		'blog_section_desc' => 'sanitize_textarea_field',
		'newsletter_desc'   => 'sanitize_textarea_field',
		// Single product
		'product_collection_label'  => 'sanitize_text_field',
		'product_size_guide_label'  => 'sanitize_text_field',
		'product_size_guide_url'    => 'esc_url_raw',
		'product_details_heading'   => 'sanitize_text_field',
		'product_material_heading'  => 'sanitize_text_field',
		'product_material_items'    => 'sanitize_textarea_field',
		'product_seller_heading'    => 'sanitize_text_field',
		'product_seller_name'       => 'sanitize_text_field',
		'product_seller_meta'       => 'sanitize_text_field',
		'product_seller_points'     => 'sanitize_textarea_field',
		'product_related_eyebrow'   => 'sanitize_text_field',
		'product_related_title'     => 'sanitize_text_field',
		'product_related_count'     => 'absint',
		'product_related_autoplay'  => 'absint',
		'product_related_columns'   => 'sanitize_key',
		// Footer
		'footer_logo_text' => 'sanitize_text_field',
		'footer_tagline'   => 'sanitize_textarea_field',
	);

	foreach ( $fields as $key => $cb ) {
		// Skip fields not submitted — they belong to a different tab.
		if ( ! array_key_exists( 'enhanced_' . $key, $_POST ) ) {
			continue;
		}
		set_theme_mod( 'enhanced_' . $key, call_user_func( $cb, $_POST[ 'enhanced_' . $key ] ) );
	}

	/* Right panel image lists — only save if the slides tab was submitted */
	foreach ( array( 'tr', 'br' ) as $panel ) {
		if ( ! array_key_exists( "enhanced_rp_{$panel}_images", $_POST ) ) {
			continue;
		}
		$ids = array_filter( array_map( 'absint', explode( ',', $_POST[ "enhanced_rp_{$panel}_images" ] ) ) );
		set_theme_mod( "enhanced_rp_{$panel}_images", implode( ',', $ids ) );
	}

	/* Checkbox — only update if the shop tab was submitted */
	if ( array_key_exists( 'enhanced_shop_sidebar', $_POST ) || 'shop' === $tab ) {
		set_theme_mod( 'enhanced_shop_sidebar', isset( $_POST['enhanced_shop_sidebar'] ) );
	}

	wp_redirect( add_query_arg(
		array( 'page' => 'enhanced-settings', 'tab' => $tab, 'saved' => '1' ),
		admin_url( 'admin.php' )
	) );
	exit;
}

/* ── Helper: get current theme mod value ──────────────────── */

function _en_mod( $key, $default = '' ) {
	return get_theme_mod( 'enhanced_' . $key, $default );
}

/* ── Helper: render a text / url / email / number / textarea input ── */

function en_field( $key, $label, $type = 'text', $default = '', $desc = '' ) {
	$id  = 'enhanced_' . $key;
	$val = _en_mod( $key, $default );
	echo '<div class="en-field">';
	echo '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
	if ( 'textarea' === $type ) {
		echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" rows="3">'
			. esc_textarea( $val ) . '</textarea>';
	} else {
		echo '<input type="' . esc_attr( $type ) . '" id="' . esc_attr( $id ) . '"'
			. ' name="' . esc_attr( $id ) . '" value="' . esc_attr( $val ) . '">';
	}
	if ( $desc ) {
		echo '<p class="en-desc">' . esc_html( $desc ) . '</p>';
	}
	echo '</div>';
}

/* ── Helper: render a colour picker input ─────────────────── */

function en_color( $key, $label, $default = '#000000' ) {
	$id  = 'enhanced_' . $key;
	$val = _en_mod( $key, $default );
	echo '<div class="en-field">';
	echo '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
	echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '"'
		. ' value="' . esc_attr( $val ) . '" class="en-color-picker" data-default-color="' . esc_attr( $default ) . '">';
	echo '</div>';
}

/* ── Helper: render an image upload field ─────────────────── */

function en_image( $key, $label, $desc = '' ) {
	$id         = 'enhanced_' . $key;
	$attach_id  = (int) _en_mod( $key, 0 );
	$preview    = $attach_id ? wp_get_attachment_image_url( $attach_id, 'medium' ) : '';
	echo '<div class="en-field en-img-field">';
	echo '<label>' . esc_html( $label ) . '</label>';
	echo '<div class="en-img-wrap">';
	echo '<div class="en-img-preview' . ( $preview ? '' : ' en-img-preview--empty' ) . '">';
	if ( $preview ) {
		echo '<img src="' . esc_url( $preview ) . '" alt="">';
	}
	echo '</div>';
	echo '<input type="hidden" class="en-img-input" name="' . esc_attr( $id ) . '" value="' . esc_attr( $attach_id ?: '' ) . '">';
	echo '<div class="en-img-btns">';
	echo '<button type="button" class="button en-upload-btn">' . esc_html__( 'Select Image', 'enhanced' ) . '</button>';
	echo '<button type="button" class="button en-remove-btn"' . ( $preview ? '' : ' style="display:none"' ) . '>'
		. esc_html__( 'Remove', 'enhanced' ) . '</button>';
	echo '</div>';
	echo '</div>';
	if ( $desc ) {
		echo '<p class="en-desc">' . esc_html( $desc ) . '</p>';
	}
	echo '</div>';
}

/* ── Helper: select dropdown ──────────────────────────────── */

function en_select( $key, $label, $choices, $default = '' ) {
	$id  = 'enhanced_' . $key;
	$val = _en_mod( $key, $default );
	echo '<div class="en-field">';
	echo '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
	echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '">';
	foreach ( $choices as $option_val => $option_label ) {
		echo '<option value="' . esc_attr( $option_val ) . '"' . selected( $val, $option_val, false ) . '>'
			. esc_html( $option_label ) . '</option>';
	}
	echo '</select></div>';
}

/* ── Helper: checkbox ─────────────────────────────────────── */

function en_checkbox( $key, $label, $default = true ) {
	$id  = 'enhanced_' . $key;
	$val = get_theme_mod( 'enhanced_' . $key, $default );
	echo '<div class="en-field en-field--check">';
	echo '<label>';
	echo '<input type="checkbox" name="' . esc_attr( $id ) . '" value="1"' . checked( $val, true, false ) . '>';
	echo ' ' . esc_html( $label );
	echo '</label></div>';
}

/* ── Helper: section heading ──────────────────────────────── */

function en_heading( $title, $desc = '' ) {
	echo '<div class="en-section-head"><h2>' . esc_html( $title ) . '</h2>';
	if ( $desc ) echo '<p>' . esc_html( $desc ) . '</p>';
	echo '</div>';
}

/* ── Main render function ─────────────────────────────────── */

function enhanced_admin_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;

	$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'hero';
	$saved      = isset( $_GET['saved'] );

	$tabs = array(
		'hero'    => __( 'Hero', 'enhanced' ),
		'slides'  => __( 'Right Panel Slides', 'enhanced' ),
		'shop'    => __( 'Shop & Banners', 'enhanced' ),
		'product' => __( 'Single Product', 'enhanced' ),
		'design'  => __( 'Design', 'enhanced' ),
	);
	?>
	<div class="wrap en-wrap">

	<div class="en-header">
		<span class="dashicons dashicons-store"></span>
		<h1><?php esc_html_e( 'Enhanced Settings', 'enhanced' ); ?></h1>
	</div>

	<?php if ( $saved ) : ?>
	<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'enhanced' ); ?></p></div>
	<?php endif; ?>

	<nav class="en-tabs">
		<?php foreach ( $tabs as $slug => $label ) : ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=enhanced-settings&tab=' . $slug ) ); ?>"
		   class="en-tab<?php echo $active_tab === $slug ? ' en-tab--active' : ''; ?>">
			<?php echo esc_html( $label ); ?>
		</a>
		<?php endforeach; ?>
	</nav>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="en-form">
		<?php wp_nonce_field( 'enhanced_save', 'enhanced_nonce' ); ?>
		<input type="hidden" name="action" value="enhanced_save">
		<input type="hidden" name="enhanced_tab" value="<?php echo esc_attr( $active_tab ); ?>">

		<div class="en-panel">

		<?php if ( 'hero' === $active_tab ) : ?>
			<?php enhanced_admin_tab_hero(); ?>

		<?php elseif ( 'slides' === $active_tab ) : ?>
			<?php enhanced_admin_tab_slides(); ?>

		<?php elseif ( 'shop' === $active_tab ) : ?>
			<?php enhanced_admin_tab_shop(); ?>

		<?php elseif ( 'product' === $active_tab ) : ?>
			<?php enhanced_admin_tab_product(); ?>

		<?php elseif ( 'design' === $active_tab ) : ?>
			<?php enhanced_admin_tab_design(); ?>
		<?php endif; ?>

		</div>

		<div class="en-footer">
			<?php submit_button( __( 'Save Settings', 'enhanced' ), 'primary large', 'submit', false ); ?>
		</div>
	</form>

	</div>
	<?php
	/* Inline CSS – avoids a separate HTTP request */
	echo '<style>' . enhanced_admin_inline_css() . '</style>';
}

/* ── Tab: Hero ────────────────────────────────────────────── */

function enhanced_admin_tab_hero() {
	en_heading( __( 'Hero – Left Panel', 'enhanced' ), __( 'The large panel on the left side of the homepage hero.', 'enhanced' ) );

	en_field( 'hero_eyebrow',       __( 'Eyebrow / Badge text', 'enhanced' ),  'text',     'Limited Offers' );
	en_field( 'hero_discount',      __( 'Discount number', 'enhanced' ),        'text',     '50', __( 'Number only, e.g. 50', 'enhanced' ) );
	en_field( 'hero_description',   __( 'Description', 'enhanced' ),            'textarea', 'Discover quality fashion…' );
	en_field( 'hero_primary_label', __( 'Button label', 'enhanced' ),           'text',     'Explore Product' );
	en_field( 'hero_primary_url',   __( 'Button URL', 'enhanced' ),             'url' );

	echo '<hr class="en-divider">';
	en_heading( __( 'Hero Media', 'enhanced' ) );

	en_select( 'hero_media_type', __( 'Media type', 'enhanced' ), array(
		'image'          => __( 'Image', 'enhanced' ),
		'video'          => __( 'Uploaded video', 'enhanced' ),
		'external-video' => __( 'External video URL', 'enhanced' ),
	), 'image' );
	en_image( 'hero_image', __( 'Hero image', 'enhanced' ), __( 'Used when Media type is Image.', 'enhanced' ) );
	en_image( 'hero_video_upload', __( 'Uploaded video', 'enhanced' ), __( 'Used when Media type is Uploaded video.', 'enhanced' ) );
	en_field( 'hero_video_url', __( 'External video URL', 'enhanced' ), 'url', '', __( 'YouTube, Vimeo, or direct .mp4 URL.', 'enhanced' ) );

	echo '<hr class="en-divider">';
	en_heading( __( 'Hero Overlay Filter', 'enhanced' ), __( 'Adds a colour tint over the hero background. Set opacity to 0 to disable.', 'enhanced' ) );
	echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">';
	en_color( 'hero_overlay_color', __( 'Overlay colour', 'enhanced' ), '#000000' );
	?>
	<div class="en-field">
		<label for="enhanced_hero_overlay_opacity">
			<?php esc_html_e( 'Overlay opacity (0 = off · 100 = fully opaque)', 'enhanced' ); ?>
			<output id="enhanced_hero_overlay_opacity_val" style="font-weight:700;margin-left:6px;">
				<?php echo esc_html( min( 100, max( 0, (int) _en_mod( 'hero_overlay_opacity', 0 ) ) ) ); ?>
			</output>
		</label>
		<input type="range" min="0" max="100"
		       id="enhanced_hero_overlay_opacity"
		       name="enhanced_hero_overlay_opacity"
		       value="<?php echo esc_attr( min( 100, max( 0, (int) _en_mod( 'hero_overlay_opacity', 0 ) ) ) ); ?>"
		       style="width:100%;accent-color:#c00;"
		       oninput="document.getElementById('enhanced_hero_overlay_opacity_val').textContent=this.value">
	</div>
	<?php
	echo '</div>';
}

/* ── Tab: Right Panel Slides ──────────────────────────────── */

function enhanced_admin_tab_slides() {
	$panels = array(
		'tr' => array( 'label' => __( 'TOP-RIGHT PANEL', 'enhanced' ),    'default_interval' => 4000 ),
		'br' => array( 'label' => __( 'BOTTOM-RIGHT PANEL', 'enhanced' ), 'default_interval' => 5000 ),
	);

	foreach ( $panels as $panel_key => $cfg ) :
		$interval  = (int) _en_mod( "rp_{$panel_key}_interval", $cfg['default_interval'] ) ?: $cfg['default_interval'];
		$title     = esc_attr( _en_mod( "rp_{$panel_key}_title", '' ) );
		$opacity   = (int) _en_mod( "rp_{$panel_key}_opacity", 25 );
		$ids_raw   = _en_mod( "rp_{$panel_key}_images", '' );
		$image_ids = array_filter( array_map( 'intval', explode( ',', $ids_raw ) ) );
		?>
		<div class="en-rp-panel">

			<div class="en-rp-panel__head">
				<span class="en-rp-panel__title"><?php echo esc_html( $cfg['label'] ); ?></span>
				<div class="en-rp-panel__actions">
					<button type="button"
					        class="button button-primary en-rp-upload-btn"
					        data-panel="<?php echo esc_attr( $panel_key ); ?>">
						&#43; <?php esc_html_e( 'Upload Images', 'enhanced' ); ?>
					</button>
					<label class="en-rp-interval-wrap">
						<?php esc_html_e( 'Interval', 'enhanced' ); ?>
						<input type="number"
						       name="enhanced_rp_<?php echo esc_attr( $panel_key ); ?>_interval"
						       value="<?php echo esc_attr( $interval ); ?>"
						       class="en-rp-interval">
						ms
					</label>
				</div>
			</div>

			<?php /* Hidden field stores comma-separated attachment IDs */ ?>
			<input type="hidden"
			       class="en-rp-ids-input"
			       name="enhanced_rp_<?php echo esc_attr( $panel_key ); ?>_images"
			       value="<?php echo esc_attr( implode( ',', $image_ids ) ); ?>">

			<?php /* Thumbnail strip */ ?>
			<div class="en-rp-thumbs" data-panel="<?php echo esc_attr( $panel_key ); ?>">
				<?php if ( empty( $image_ids ) ) : ?>
					<div class="en-rp-empty">
						<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
						<p><?php esc_html_e( 'No images yet. Click "+ Upload Images" to add 1–3 slides.', 'enhanced' ); ?></p>
					</div>
				<?php else : ?>
					<?php foreach ( $image_ids as $img_id ) :
						$url = wp_get_attachment_image_url( $img_id, 'thumbnail' );
						if ( ! $url ) continue;
					?>
					<div class="en-rp-thumb" data-id="<?php echo esc_attr( $img_id ); ?>">
						<img src="<?php echo esc_url( $url ); ?>" alt="">
						<button type="button" class="en-rp-thumb-del" title="<?php esc_attr_e( 'Remove', 'enhanced' ); ?>">&#215;</button>
					</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<?php /* Shared controls — apply to all slides in this panel */ ?>
			<div class="en-rp-shared">
				<div class="en-rp-shared__field">
					<label for="en_rp_<?php echo esc_attr( $panel_key ); ?>_title">
						<?php esc_html_e( 'Overlay text', 'enhanced' ); ?>
					</label>
					<input type="text"
					       id="en_rp_<?php echo esc_attr( $panel_key ); ?>_title"
					       name="enhanced_rp_<?php echo esc_attr( $panel_key ); ?>_title"
					       value="<?php echo $title; ?>"
					       placeholder="<?php esc_attr_e( 'Text shown on top of every slide…', 'enhanced' ); ?>">
				</div>
				<div class="en-rp-shared__field">
					<label>
						<?php esc_html_e( 'Overlay opacity — applies to all slides', 'enhanced' ); ?>
						<output class="en-rp-val"><?php echo esc_html( $opacity ); ?></output>
					</label>
					<input type="range" min="0" max="100"
					       class="en-rp-range"
					       name="enhanced_rp_<?php echo esc_attr( $panel_key ); ?>_opacity"
					       value="<?php echo esc_attr( $opacity ); ?>">
				</div>
			</div>

		</div>
		<?php if ( 'tr' === $panel_key ) echo '<hr class="en-divider">'; ?>
		<?php
	endforeach;
}

/* ── Tab: Shop & Banners ──────────────────────────────────── */

function enhanced_admin_tab_shop() {
	en_heading( __( 'Browse Categories', 'enhanced' ) );
	en_image( 'browse_model_image', __( 'Model image (right panel)', 'enhanced' ), __( 'Right-side image in the Browse Categories section.', 'enhanced' ) );
	en_image( 'browse_panel_bg', __( 'Left panel background image', 'enhanced' ), __( 'Sits behind the dark overlay on the left text panel. Leave empty for solid black.', 'enhanced' ) );

	$_bop = max( 0, min( 1, (float) _en_mod( 'browse_overlay_opacity', 0.78 ) ) );
	?>
	<div class="en-field">
		<label for="enhanced_browse_overlay_opacity">
			<?php esc_html_e( 'Left panel overlay opacity', 'enhanced' ); ?>
			&nbsp;<output id="en_bop_val" style="font-weight:700;"><?php echo esc_html( number_format( $_bop, 2 ) ); ?></output>
			<span style="color:#888;font-size:11px;font-weight:400;">&nbsp;(0 = image only &nbsp;·&nbsp; 1 = fully black)</span>
		</label>
		<input type="range" min="0" max="1" step="0.05"
		       id="enhanced_browse_overlay_opacity"
		       name="enhanced_browse_overlay_opacity"
		       value="<?php echo esc_attr( $_bop ); ?>"
		       style="width:100%;max-width:500px;accent-color:#c00;display:block;margin-top:4px;"
		       oninput="document.getElementById('en_bop_val').textContent=parseFloat(this.value).toFixed(2)">
	</div>
	<?php

	echo '<hr class="en-divider">';
	en_heading( __( 'Sale Is On', 'enhanced' ) );
	en_field( 'sale_due_label', __( 'Due-date badge', 'enhanced' ), 'text', 'Due Aug 24', __( 'Shown on promo cards.', 'enhanced' ) );

	echo '<hr class="en-divider">';
	en_heading( __( 'Sale Banner', 'enhanced' ) );
	en_field( 'sale_banner_eyebrow', __( 'Eyebrow', 'enhanced' ),     'text',     'Last Chance' );
	en_field( 'sale_banner_title',   __( 'Title', 'enhanced' ),        'text',     'End of Season Sale Up to 50% Off' );
	en_field( 'sale_banner_btn',     __( 'Button label', 'enhanced' ), 'text',     'Check It Out' );
	en_field( 'sale_banner_url',     __( 'Button URL', 'enhanced' ),   'url' );
	en_image( 'sale_banner_image',   __( 'Banner image', 'enhanced' ) );

	echo '<hr class="en-divider">';
	en_heading( __( 'Shop Page', 'enhanced' ) );
	en_checkbox( 'shop_sidebar', __( 'Show sidebar filters on shop page', 'enhanced' ), true );

	echo '<hr class="en-divider">';
	en_heading( __( 'Blog Section', 'enhanced' ) );
	en_field( 'blog_section_desc', __( 'Blog section description', 'enhanced' ), 'textarea' );

	echo '<hr class="en-divider">';
	en_heading( __( 'Newsletter', 'enhanced' ) );
	en_field( 'newsletter_desc', __( 'Newsletter description', 'enhanced' ), 'textarea' );
}

/* ── Tab: Single Product ──────────────────────────────────── */

function enhanced_admin_tab_product() {
	en_heading( __( 'Single Product Page', 'enhanced' ) );

	en_field( 'product_collection_label', __( 'Collection label', 'enhanced' ),  'text', 'Minimal modern collection' );
	en_field( 'product_size_guide_label', __( 'Size guide label', 'enhanced' ),   'text', 'Size guide' );
	en_field( 'product_size_guide_url',   __( 'Size guide URL', 'enhanced' ),     'url' );

	echo '<hr class="en-divider">';
	en_heading( __( 'Product Details', 'enhanced' ) );
	en_field( 'product_details_heading',  __( 'Details heading', 'enhanced' ),    'text', 'Product details' );
	en_field( 'product_material_heading', __( 'Material heading', 'enhanced' ),   'text', 'Material & care' );
	en_field( 'product_material_items',   __( 'Material items (one per line)', 'enhanced' ), 'textarea', "Premium fabric blend\nMachine wash or dry clean" );

	echo '<hr class="en-divider">';
	en_heading( __( 'Seller Info', 'enhanced' ) );
	en_field( 'product_seller_heading', __( 'Sold by label', 'enhanced' ),   'text', 'Sold by' );
	en_field( 'product_seller_name',    __( 'Seller name', 'enhanced' ),     'text', get_bloginfo( 'name' ) );
	en_field( 'product_seller_meta',    __( 'Seller meta line', 'enhanced' ),'text', 'Fast dispatch and careful packaging.' );
	en_field( 'product_seller_points',  __( 'Seller bullet points (one per line)', 'enhanced' ), 'textarea', "Secure checkout\nCarefully packed orders\nResponsive customer support" );

	echo '<hr class="en-divider">';
	en_heading( __( 'Related Products', 'enhanced' ) );
	en_field( 'product_related_eyebrow', __( 'Eyebrow', 'enhanced' ),         'text',   'Similar products' );
	en_field( 'product_related_title',   __( 'Title', 'enhanced' ),            'text',   'You may also like' );
	en_field( 'product_related_count',   __( 'Product count', 'enhanced' ),    'number', 8 );
	en_field( 'product_related_autoplay',__( 'Autoplay (ms)', 'enhanced' ),    'number', 2000 );
	en_select( 'product_related_columns', __( 'Columns on desktop', 'enhanced' ), array( '4' => '4', '3' => '3' ), '4' );
}

/* ── Tab: Design ──────────────────────────────────────────── */

function enhanced_admin_tab_design() {
	en_heading( __( 'Colors', 'enhanced' ) );
	en_color( 'accent_color', __( 'Accent / Sale colour', 'enhanced' ), '#cc2222' );
	en_color( 'utility_bg',   __( 'Utility bar background', 'enhanced' ), '#111111' );
	en_color( 'footer_bg',    __( 'Footer background', 'enhanced' ), '#1a1a1a' );

	echo '<hr class="en-divider">';
	en_heading( __( 'Header', 'enhanced' ) );
	en_field( 'utility_text',  __( 'Utility bar text', 'enhanced' ),  'text',  'Free shipping on orders over $100' );
	en_field( 'utility_link',  __( 'Utility bar link URL', 'enhanced' ), 'url' );
	en_field( 'contact_email', __( 'Header email', 'enhanced' ),      'email', 'hello@enhanced.store' );
	en_field( 'contact_phone', __( 'Header phone', 'enhanced' ),      'text',  '+1 (800) 000-0000' );

	echo '<hr class="en-divider">';
	en_heading( __( 'Footer', 'enhanced' ) );
	en_field( 'footer_logo_text', __( 'Footer logotype text', 'enhanced' ), 'text',     'LUXINA' );
	en_field( 'footer_tagline',   __( 'Footer tagline', 'enhanced' ),       'textarea', 'Premium fashion - crafted for modern life.' );
}

/* ── Inline CSS ───────────────────────────────────────────── */

function enhanced_admin_inline_css() {
	return '
/* Wrap */
.en-wrap { max-width: 900px; margin-top: 20px; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }

/* Header */
.en-header { display:flex; align-items:center; gap:10px; margin-bottom:18px; }
.en-header .dashicons { font-size:28px; width:28px; height:28px; color:#c00; }
.en-header h1 { margin:0; font-size:22px; font-weight:700; }

/* Tabs */
.en-tabs { display:flex; gap:2px; border-bottom:2px solid #ddd; margin-bottom:0; flex-wrap:wrap; }
.en-tab { padding:9px 18px; text-decoration:none; color:#555; font-size:13px; font-weight:500;
          border:1px solid transparent; border-bottom:none; border-radius:4px 4px 0 0;
          background:#f7f7f7; transition:background .15s; }
.en-tab:hover { background:#fff; color:#111; }
.en-tab--active { background:#fff; color:#c00; border-color:#ddd; border-bottom-color:#fff;
                  margin-bottom:-2px; font-weight:600; }

/* Panel */
.en-panel { background:#fff; border:1px solid #ddd; border-top:none; padding:28px 32px; }

/* Section heading */
.en-section-head { margin-bottom:18px; }
.en-section-head h2 { font-size:15px; font-weight:700; color:#111; margin:0 0 4px; }
.en-section-head p { color:#666; font-size:12px; margin:0; }

.en-divider { border:none; border-top:1px solid #eee; margin:28px 0; }

/* Fields */
.en-field { margin-bottom:20px; }
.en-field label { display:block; font-size:13px; font-weight:600; color:#333; margin-bottom:5px; }
.en-field input[type=text],
.en-field input[type=url],
.en-field input[type=email],
.en-field input[type=number],
.en-field select,
.en-field textarea { width:100%; max-width:500px; padding:7px 10px;
                      border:1px solid #ccc; border-radius:4px; font-size:13px; }
.en-field textarea { resize:vertical; }
.en-field--check label { display:flex; align-items:center; gap:8px; font-weight:400; }
.en-desc { margin:4px 0 0; font-size:11px; color:#888; }

/* Image field */
.en-img-preview { width:160px; height:100px; border:2px dashed #ccc; border-radius:6px;
                   overflow:hidden; background:#f9f9f9; display:flex;
                   align-items:center; justify-content:center; margin-bottom:8px; }
.en-img-preview--empty::after { content:"No image"; font-size:11px; color:#aaa; }
.en-img-preview img { width:100%; height:100%; object-fit:cover; display:block; }
.en-img-btns { display:flex; gap:8px; }
.en-remove-btn { color:#c00 !important; }

/* ── Right Panel Slides ───────── */
.en-rp-panel { margin-bottom:8px; }
.en-rp-panel__head { display:flex; align-items:center; justify-content:space-between;
                      padding:14px 0 12px; border-bottom:2px solid #f0f0f0; margin-bottom:16px; }
.en-rp-panel__title { font-size:13px; font-weight:800; letter-spacing:.08em;
                       color:#111; text-transform:uppercase; }
.en-rp-panel__actions { display:flex; align-items:center; gap:12px; }
.en-rp-interval-wrap { display:flex; align-items:center; gap:5px; font-size:12px; color:#888; }
.en-rp-interval { width:68px !important; padding:4px 6px !important; text-align:center; }

/* Thumbnail strip */
.en-rp-thumbs { display:flex; flex-wrap:wrap; gap:10px;
                min-height:90px; align-items:flex-start;
                padding:14px; background:#f8f9fa; border:1.5px dashed #dce1e7;
                border-radius:8px; margin-bottom:14px; }

/* Empty state inside strip */
.en-rp-empty { display:flex; flex-direction:column; align-items:center;
               justify-content:center; gap:8px; width:100%; padding:8px 0; }
.en-rp-empty p { margin:0; font-size:12px; color:#bbb; }

/* Each thumb tile */
.en-rp-thumb { position:relative; width:80px; height:80px; border-radius:6px;
               overflow:hidden; border:2px solid #dce1e7; flex-shrink:0; }
.en-rp-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.en-rp-thumb-del { position:absolute; top:3px; right:3px; width:20px; height:20px;
  border-radius:50%; background:rgba(0,0,0,.6); color:#fff; border:none;
  cursor:pointer; font-size:13px; line-height:1; display:flex;
  align-items:center; justify-content:center; transition:background .15s; }
.en-rp-thumb-del:hover { background:#c00; }

/* Shared settings below the strip */
.en-rp-shared { display:grid; grid-template-columns:1fr 1fr; gap:16px;
                padding:14px; background:#fff; border:1px solid #edf0f3;
                border-radius:8px; }
.en-rp-shared__field label { display:block; font-size:12px; font-weight:600;
                               color:#555; margin-bottom:6px; }
.en-rp-shared__field input[type=text] { width:100%; border:1px solid #dce1e7;
  border-radius:5px; padding:6px 9px; font-size:12px; }
.en-rp-range { width:100%; accent-color:#c00; margin-top:4px; display:block; }
.en-rp-val { font-weight:700; color:#333; font-size:12px; margin-left:4px; }

/* Footer */
.en-form .en-footer { margin-top:0; background:#fff; border:1px solid #ddd;
                        border-top:none; padding:16px 32px; }
	';
}
