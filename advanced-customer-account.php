<?php
/*
 * @link              
 * @since             1.0.0
 * @package           Advanced Customer Account
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Customer Account
 * Plugin URI:        
 * Description:       Display butifully your customer account page by this plugin.
 * Version:           1.0.0
 * Author:            Noor alam
 * Author URI:        http://wpthemespace.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-customer-account
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


final class advancedCustomerAccount
{
	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';
	/**
	 * Instance
	 *
	 * @since 1.0.0
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
	 * @access public
	 * @static
	 * 
	 */
	public static function instance()
	{

		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct()
	{

		if ($this->is_compatible()) {
			$this->plugin_constants();
			add_action('init', [$this, 'i18n']);
			add_action('plugin_loaded', [$this, 'init_plugin']);
		}
	}



	/** 
	 *
	 * Constants fucntion
	 */

	public function plugin_constants()
	{
		define('AD_CUSTOMER_ACCOUNT_VERSION', self::VERSION);
		define('AD_CUSTOMER_ACCOUNT_PATH', trailingslashit(plugin_dir_path(__FILE__)));
		define('AD_CUSTOMER_ACCOUNT_URL', trailingslashit(plugins_url('/', __FILE__)));
	}


	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n()
	{

		load_plugin_textdomain('advanced-customer-account');
	}


	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the plugin requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible()
	{
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			return true;
		}

		return false;
	}



	/*
*
* Plugin init
*/

	public function init_plugin()
	{
		$this->enqueue_scripts();
		require_once AD_CUSTOMER_ACCOUNT_PATH . 'inc/account-page.php';
	}


	/*
*
* enqueue scripts
*/
	public function enqueue_scripts()
	{

		add_action('wp_enqueue_scripts', [$this, 'register_public_scripts']);
	}


	public function register_public_scripts()
	{
		wp_enqueue_style('advanced-customer-account-style', AD_CUSTOMER_ACCOUNT_URL . 'assets/adva-style.css', array(), '1.0', 'all');
	}
}


/**
 * init main plugin
 */

function advanced_customer_account_init()
{
	return advancedCustomerAccount::instance();
}
advanced_customer_account_init();
