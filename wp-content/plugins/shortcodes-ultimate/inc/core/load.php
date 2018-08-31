<?php
class Shortcodes_Ultimate_Load {

	/**
	 * Constructor
	 */
	function __construct() {
		// add_action( 'plugins_loaded', array( __CLASS__, 'init'     )     );
		add_action( 'init',           array( __CLASS__, 'register' )     );
		// add_action( 'init',           array( __CLASS__, 'update'   ), 20 );
	}

	/**
	 * Plugin init
	 */
	public static function init() {

		// Make plugin available for translation
		// load_plugin_textdomain( 'shortcodes-ultimate', false, dirname( plugin_basename( SU_PLUGIN_FILE ) ) . '/languages/' );

		// Shortcodes Ultimate is ready
		// do_action( 'su/init' );

	}

	/**
	 * Plugin update hook
	 */
	public static function update() {
		// $option = get_option( 'su_option_version' );
		// if ( $option !== SU_PLUGIN_VERSION ) {
		// 	update_option( 'su_option_version', SU_PLUGIN_VERSION );
		// 	do_action( 'su/update' );
		// }
	}

	/**
	 * Register shortcodes
	 */
	public static function register() {
		// Prepare compatibility mode prefix
		$prefix = su_cmpt();
		// Loop through shortcodes
		foreach ( ( array ) Su_Data::shortcodes() as $id => $data ) {
			if ( isset( $data['function'] ) && is_callable( $data['function'] ) ) $func = $data['function'];
			elseif ( is_callable( array( 'Su_Shortcodes', $id ) ) ) $func = array( 'Su_Shortcodes', $id );
			elseif ( is_callable( array( 'Su_Shortcodes', 'su_' . $id ) ) ) $func = array( 'Su_Shortcodes', 'su_' . $id );
			else continue;
			// Register shortcode
			add_shortcode( $prefix . $id, $func );
		}
		// Register [media] manually // 3.x
		add_shortcode( $prefix . 'media', array( 'Su_Shortcodes', 'media' ) );
	}

}

new Shortcodes_Ultimate_Load;
