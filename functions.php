<?php
/**
 * Enhanced theme bootstrap.
 *
 * @package Enhanced
 */

defined( 'ABSPATH' ) || exit;

define( 'ENHANCED_VERSION', '1.0.3' );
define( 'ENHANCED_DIR', trailingslashit( get_template_directory() ) );
define( 'ENHANCED_URI', trailingslashit( get_template_directory_uri() ) );

require ENHANCED_DIR . 'inc/setup.php';
require ENHANCED_DIR . 'inc/helpers.php';
require ENHANCED_DIR . 'inc/enqueue.php';
require ENHANCED_DIR . 'inc/woocommerce.php';
require ENHANCED_DIR . 'inc/shop-filters.php';
require ENHANCED_DIR . 'inc/customizer.php';
