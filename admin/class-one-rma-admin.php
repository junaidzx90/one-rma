<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    One_Rma
 * @subpackage One_Rma/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    One_Rma
 * @subpackage One_Rma/admin
 * @author     Md Junayed <admin@easeare.com>
 */
class One_Rma_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in One_Rma_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The One_Rma_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/one-rma-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in One_Rma_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The One_Rma_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/one-rma-admin.js', array( 'jquery' ), $this->version, false );

	}

	function rma_users_menupage(){
		add_options_page( 'One Devoluciones Settings', 'One Devoluciones', 'manage_options', 'one-rma', [$this,'onerma_menu_view'] );

		// options
		add_settings_section( 'onerma_settings_section', '', '', 'onerma_settings_page' );

		add_settings_field( 'onerma_server_url', 'Server URL', [$this,'onerma_server_url_cb'], 'onerma_settings_page', 'onerma_settings_section');
		register_setting( 'onerma_settings_section', 'onerma_server_url');

		add_settings_field( 'onerma_user_keys', 'Api Key', [$this,'onerma_user_keys_cb'], 'onerma_settings_page', 'onerma_settings_section');
		register_setting( 'onerma_settings_section', 'onerma_user_keys');

		add_settings_field( 'onerma_tirmsconditions', 'Terms & Conditions', [$this,'onerma_tirmsconditions_cb'], 'onerma_settings_page', 'onerma_settings_section');
		register_setting( 'onerma_settings_section', 'onerma_tirmsconditions');
	}

	function onerma_server_url_cb(){
		echo '<input class="widefat" type="url" placeholder="Server URL" name="onerma_server_url" value="'.get_option( 'onerma_server_url' ).'">';
	}

	function onerma_user_keys_cb(){
		echo '<input class="widefat" type="text" placeholder="Api Key" name="onerma_user_keys" value="'.get_option( 'onerma_user_keys' ).'">';
	}

	function onerma_tirmsconditions_cb(){
		echo '<textarea class="widefat" name="onerma_tirmsconditions">'.get_option( 'onerma_tirmsconditions' ).'</textarea>';
		echo '<small><b>HTML Supported.</b></small>';
	}

	function onerma_menu_view(){
		echo '<h3>One Devoluciones Settings</h3>';
		echo '<hr>';

		echo '<div style="width: 50%" id="onerma">';

		echo '<form method="post" action="options.php">';
		echo '<table class="widefat">';

		settings_fields( 'onerma_settings_section' );
		do_settings_fields( 'onerma_settings_page', 'onerma_settings_section' );

		echo '</table>';
		submit_button();
		echo '</form>';
		
		echo '</div>';
	}
}