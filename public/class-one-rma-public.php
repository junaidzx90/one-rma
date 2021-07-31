<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    One_Rma
 * @subpackage One_Rma/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    One_Rma
 * @subpackage One_Rma/public
 * @author     Md Junayed <admin@easeare.com>
 */
class One_Rma_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'products_returns', [$this,'onerma_products_returns'] );
		add_shortcode( 'products_wizard', [$this,'onerma_products_wizard'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/one-rma-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( 'rmaVue', plugin_dir_url( __FILE__ ) . 'js/vue.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'md5', plugin_dir_url( __FILE__ ) . 'js/md5.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/one-rma-public.js', array( 'jquery' ), $this->version, true );
		
		wp_localize_script($this->plugin_name, "get_product_detail", array(
			'ajaxurl' 	=> admin_url('admin-ajax.php'),
			'nonce'		=> wp_create_nonce( 'rma_nonce' ),
			'email'		=> get_user_by( 'ID', get_current_user_id(  ) )->user_email,
			'url'		=> (!empty(get_option( 'onerma_server_url' ))?get_option( 'onerma_server_url' ):''),
			'key'		=> (!empty(get_option( 'onerma_user_keys' ))?get_option( 'onerma_user_keys' ):'')
		));

	}

	function get_self_sale_ids(){
		if ( ! wp_verify_nonce( $_GET['nonce'], 'rma_nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

		try {
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$server_url = '';
				$api_key = '';
				$id = sanitize_text_field( $_GET['id'] );

				if(get_option( 'onerma_server_url' )){
					$server_url = esc_url_raw( get_option( 'onerma_server_url' ) );
				}
	
				if(get_option( 'onerma_user_keys' )){
					$api_key = sanitize_text_field( get_option( 'onerma_user_keys' ) );
				}
	
				if(!empty($server_url) && !empty($api_key)){
					if(substr($server_url , -1)=='/'){
						$server_url = rtrim($server_url,"/");
					}
	
					$rest_url = $server_url.PRODUCT_ENDPOINT.$api_key.'?id='.$id;

					$response = wp_remote_get( $rest_url, ['method' => 'GET'] );
					$results = wp_remote_retrieve_body($response);

					if($results):
						echo $results;
						die;
					else:
						echo 'null';
						die;
					endif;
				}
				die;
			}
			die;
		} catch (Exception $th) {
			//throw $th;
		}
	}

	function get_sale_product_detail(){
		if ( ! wp_verify_nonce( $_GET['nonce'], 'rma_nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

		if(isset($_GET['sale_id']) && !empty($_GET['sale_id'])){
			$server_url = '';
			$api_key = '';
			$sale_id = sanitize_text_field( $_GET['sale_id'] );

			if(get_option( 'onerma_server_url' )){
				$server_url = esc_url_raw( get_option( 'onerma_server_url' ) );
			}

			if(get_option( 'onerma_user_keys' )){
				$api_key = sanitize_text_field( get_option( 'onerma_user_keys' ) );
			}

			if(!empty($server_url) && !empty($api_key)){
				if(substr($server_url , -1)=='/'){
					$server_url = rtrim($server_url,"/");
				}

				$rest_url = $server_url.SALES_PRODUCT_ENDPOINT.$api_key.'?sale_id='.$sale_id;

				$response = wp_remote_get( $rest_url, ['method' => 'GET'] );
				$results = wp_remote_retrieve_body($response);

				if($results):
					echo $results;
					die;
				else:
					echo 'null';
					die;
				endif;
			}
			die;
		}
		die;
	}

	function get_sales_product_option(){
		$motives = [
			'Comprado por error' => 'Comprado por error',
			'Funcionamiento o calidad no adecuados' => 'Funcionamiento o calidad no adecuados',
			'El producto está dañado, pero el embalaje está bien' => 'El producto está dañado, pero el embalaje está bien',
			'El producto y embalaje exterior están dañados' => 'El producto y embalaje exterior están dañados',
			'No es el producto que pedí' => 'No es el producto que pedí',
			'Defectuoso/ No funciona bien' => 'Defectuoso/ No funciona bien',
			'Ya no lo quiero' => 'Ya no lo quiero',
			'Compra no autorizada' => 'Compra no autorizada',
		];
		return $motives;
	}

	function get_products_list(){
		try {
			$server_url = '';
			$api_key = '';
			if(get_option( 'onerma_server_url' )){
				$server_url = esc_url_raw( get_option( 'onerma_server_url' ) );
			}

			if(get_option( 'onerma_user_keys' )){
				$api_key = sanitize_text_field( get_option( 'onerma_user_keys' ) );
			}
			
			if(!empty($server_url) && !empty($api_key)){

				if(substr($server_url , -1)=='/'){
					$server_url = rtrim($server_url,"/");
				}

				$user = get_user_by( 'ID', get_current_user_id(  ) );
				$user_email = $user->user_email;

				$rest_url = $server_url.LIST_ENDPOINT.$api_key.'?email='.$user_email;

				$response = wp_remote_get( $rest_url, ['method' => 'GET'] );
				$results = json_decode(wp_remote_retrieve_body($response));

				if($results):
					return $results;
				else:
					return 'Null';
				endif;
			}
		} catch (Exception $th) {
			//throw $th;
		}
	}

	function onerma_products_returns(){
		ob_start();

		require_once plugin_dir_path( __FILE__ )."partials/one-rma-products-returns.php";

		$output = ob_get_contents();
		ob_get_clean();
		return $output;
	}
	
	function onerma_products_wizard(){
		ob_start();

		require_once plugin_dir_path( __FILE__ )."partials/one-rma-products-wizard.php";

		$output = ob_get_contents();
		ob_get_clean();
		return $output;
	}

}
