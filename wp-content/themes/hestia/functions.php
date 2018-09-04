<?php
/**
 * Hestia functions and definitions
 *
 * @package Hestia
 * @since   Hestia 1.0
 */
// this function initializes the iframe elements 

function add_iframe($initArray) {
$initArray['extended_valid_elements'] = "iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]";
return $initArray;
}

// this function alters the way the WordPress editor filters your code
add_filter('tiny_mce_before_init', 'add_iframe');

define( 'HESTIA_VERSION', '1.1.85' );
define( 'HESTIA_VENDOR_VERSION', '1.0.1' );
define( 'HESTIA_PHP_INCLUDE', trailingslashit( get_template_directory() ) . 'inc/' );
define( 'HESTIA_CORE_DIR', HESTIA_PHP_INCLUDE . 'core/' );

// Load hooks
require_once( HESTIA_PHP_INCLUDE . 'hooks/hooks.php' );

// Load Helper Globally Scoped Functions
require_once( HESTIA_PHP_INCLUDE . 'helpers/sanitize-functions.php' );
require_once( HESTIA_PHP_INCLUDE . 'helpers/layout-functions.php' );

if ( class_exists( 'WooCommerce' ) ) {
	require_once( HESTIA_PHP_INCLUDE . 'compatibility/woocommerce/functions.php' );
}

if ( function_exists( 'max_mega_menu_is_enabled' ) ) {
	require_once( HESTIA_PHP_INCLUDE . 'compatibility/max-mega-menu/functions.php' );
}
/**
 * Adds notice for PHP < 5.3.29 hosts.
 */
function hestia_no_support_5_3() {
	$message = __( 'Hey, we\'ve noticed that you\'re running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by upgrading PHP to the latest version.', 'hestia' );

	printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );
}


if ( version_compare( PHP_VERSION, '5.3.29' ) < 0 ) {
	/**
	 * Add notice for PHP upgrade.
	 */
	add_filter( 'template_include', '__return_null', 99 );
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'hestia_no_support_5_3' );

	return;
}
/*function new_section_2(){
	
echo do_shortcode('<span id="aboutus"></span>
<div class="container fullscreen">
<div class="row"><div class="about-center">
<div class="col-md-8 col-md-offset-2 hestia-testimonials-title-area ">

<h2 class="hestia-title">About Us</h2>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur tincidunt, felis ac ullamcorper eleifend, neque tortor consectetur magna, sed maximus massa ex molestie erat. Nunc suscipit tincidunt erat vel dignissim. Morbi aliquet vel felis nec venenatis. Ut aliquam, augue a mollis fringilla, leo est hendrerit risus, ac sagittis mi tortor at est.
<blockquote>Morbi quis venenatis lorem. Quisque varius, erat quis pellentesque feugiat, magna erat aliquet orci, sit amet porttitor enim est eu metus.</blockquote>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sollicitudin libero vulputate mi finibus, ac vulputate elit laoreet. Curabitur varius, sapien eget condimentum convallis, arcu libero sollicitudin mi, ut suscipit augue tellus et enim.
<div class="arrow bounce"><a class="fa fa-arrow-down fa-2x" href="#features"></a></div>
</div>

</div>

</div>
</div>');
	
}

add_action( 'hestia_after_subscribe_section_hook', 'new_section_2' );*/
/*function new_section_1(){
	
echo do_shortcode('<div id="video" style="position: relative; top: -70px;"></div><div class="fullscreen-video"><div class="video">[su_youtube url="https://www.youtube.com/watch?v=ZQhClXsxVcArel=0" id="play"]</div></div></div>');
	
}

add_action( 'hestia_before_testimonials_section_hook', 'new_section_1' );*/

/**
 * Begins execution of the theme core.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function hestia_run() {
	new Hestia_Core();

	$vendor_file = trailingslashit( get_template_directory() ) . 'vendor/autoload.php';
	if ( is_readable( $vendor_file ) ) {
		require_once $vendor_file;
	}
	add_filter( 'themeisle_sdk_products', 'hestia_load_sdk' );
}

/**
 * Loads products array.
 *
 * @param array $products All products.
 *
 * @return array Products array.
 */
function hestia_load_sdk( $products ) {
	$products[] = get_template_directory() . '/style.css';

	return $products;
}

require_once( HESTIA_CORE_DIR . 'class-hestia-autoloader.php' );
Hestia_Autoloader::set_path( HESTIA_PHP_INCLUDE );
Hestia_Autoloader::define_excluded_files(
	array(
		'node_modules',
		'hooks',
		'helpers',
	)
);
Hestia_Autoloader::define_namespaces( array( 'Hestia' ) );

/**
 * Invocation of the Autoloader::loader method.
 *
 * @since   1.0.0
 */
spl_autoload_register( 'Hestia_Autoloader::loader' );

/**
 * The start of the app.
 *
 * @since   1.0.0
 */
hestia_run();

/**
 * Upgrade link to BeaverBuilder
 */
function hestia_bb_upgrade_link() {
	return 'https://www.wpbeaverbuilder.com/?fla=101&campaign=hestia';
}

add_filter( 'fl_builder_upgrade_url', 'hestia_bb_upgrade_link' );

if ( ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
	define( 'ELEMENTOR_PARTNER_ID', 2112 );
}

/**
 * Append theme name to the upgrade link
 * If the active theme is child theme of Hestia
 *
 * @param string $link - Current link.
 *
 * @return string $link - New upgrade link.
 * @package hestia
 * @since   1.1.75
 */
function hestia_upgrade_link( $link ) {

	$theme_name = wp_get_theme()->get_stylesheet();

	$hestia_child_themes = array(
		'orfeo',
		'fagri',
		'tiny-hestia',
		'christmas-hestia',
	);

	if ( $theme_name === 'hestia' ) {
		return $link;
	}

	if ( ! in_array( $theme_name, $hestia_child_themes ) ) {
		return $link;
	}

	$link = add_query_arg(
		array(
			'theme' => $theme_name,
		), $link
	);

	return $link;
}

add_filter( 'hestia_upgrade_link_from_child_theme_filter', 'hestia_upgrade_link' );

/**
 * Check if $no_seconds have passed since theme was activated.
 * Used to perform certain actions, like displaying upsells or add a new recommended action in About Hestia page.
 *
 * @param integer $no_seconds number of seconds.
 *
 * @return bool
 * @since  1.1.45
 * @access public
 */
function hestia_check_passed_time( $no_seconds ) {
	$activation_time = get_option( 'hestia_time_activated' );
	if ( ! empty( $activation_time ) ) {
		$current_time    = time();
		$time_difference = (int) $no_seconds;
		if ( $current_time >= $activation_time + $time_difference ) {
			return true;
		} else {
			return false;
		}
	}

	return true;
}

/**
 * Legacy code function.
 */
function hestia_setup_theme() {
	return;
}

