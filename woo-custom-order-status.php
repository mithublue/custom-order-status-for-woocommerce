<?php
/**
 * Plugin Name:       Custom order status for WooCommerce
 * Description:       Create custom order status as per your need
 * Author:            CyberCraft
 * Author URI:
 * Version:           1.0
 * Text Domain:       woocos
 * Domain Path:       /languages/
 * License:           GPLv2 or later (license.txt)
 */

define('WOOCOS_NAME', 'Backend and Waitlist for WooCommerce');
define('WOOCOS_ROOT', dirname(__FILE__));
define('WOOCOS_PLUGIN_FILE', __FILE__);
define('WOOCOS_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('WOOCOS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WOOCOS_PLUGIN_URL', plugins_url('/', __FILE__));
define('WOOCOS_ASSET_PATH', WOOCOS_PLUGIN_PATH . '/assets');
define('WOOCOS_ASSET_URL', WOOCOS_PLUGIN_URL . '/assets');

class WOOCOS_Init{

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return ${ClassName} An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
        $this->includes();
    }

    public function includes() {
        foreach ( glob( WOOCOS_ROOT . '/inc/*.php' ) as $k => $filename ) {
            include_once $filename;
        }
    }

    public function wp_enqueue_scripts() {
        wp_enqueue_style( 'woocos-style', WOOCOS_ASSET_URL . '/css/app.css' );
    }
}

WOOCOS_Init::instance();
