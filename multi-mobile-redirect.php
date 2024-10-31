<?php
/**
 * Plugin Name: Multi Mobile Redirect
 *
 * Description: Simple redirect to handle redirect for mobile pages
 * Version: 1.0
 * Author: Yevhen Kotliar <a href="http://leverage.it">@LeverageIT</a>
 * @package mobile-redirect
 */

$GLOBALS['multi_mr_redirect'] = new multi_mr_redirect;
require_once 'includes/Mobile_Detect.php';
$GLOBALS['multi_mr_mobile_detect'] = new Mobile_Detect;

class multi_mr_redirect{
	protected $loaded_textdomain = false;
	public function __construct() {
		
		add_action('admin_menu', 'multi_mr_admin_actions' );
		add_action( 'template_redirect',array($this, 'multi_mr_handler' ));

		// ajax
	    add_action( 'wp_ajax_multi_mr_update_redirects', array($this, 'multi_mr_update_redirects') );
	    add_action( 'wp_ajax_multi_mr_clear_redirects', array($this, 'multi_mr_clear_redirects') );

		function multi_mr_admin_actions(){
			$option_page = add_options_page("Mobile Redirect", "Mobile Redirect", 'edit_users', "mobile-redirect", 'multi_mr_redirects_page');
			add_action( 'load-' . $option_page, 'mr_load_admin_js' );
		}

		function mr_load_admin_js(){
	        add_action( 'admin_enqueue_scripts', 'mr_enqueue_admin_js' );
	    }

	    function mr_enqueue_admin_js(){
			wp_enqueue_style( 'multi_mr_fa', plugins_url( 'css/font-awesome.min.css', __FILE__ ));
  			wp_enqueue_style( 'multi_mr_bootstrap', plugins_url( 'css/bootstrap.min.css', __FILE__ ));
        	wp_enqueue_style( 'multi_mr_css', plugins_url('css/styles.css',__FILE__ ));
        	wp_enqueue_style( 'multi_mr_alertify', plugins_url('css/alertify.min.css',__FILE__ ));
        	wp_enqueue_style( 'multi_mr_alertify-theme', plugins_url('css/alertitify-theme-default.min.css',__FILE__ ));
  			wp_enqueue_script( 'multi_mr_bootstrap_js', plugins_url( 'js/bootstrap.min.js', __FILE__ ));
	        wp_enqueue_script( 'multi_mr_alertify_js', plugins_url( 'js/alertify.min.js', __FILE__ ));
	        wp_enqueue_script( 'mr_main_js', plugins_url( 'js/main.js', __FILE__ ));

	    }

		function multi_mr_redirects_page(){
	    	include('includes/page.php');
	    }

        function get_multi_mr_redirects(){
            $options = get_option('get_multi_mr_redirects');
            if(is_array($options)){
                return $options;
            }else{
                return array();
            }
        }
	}

	public function multi_mr_handler(){
	    	
    	if( $GLOBALS['multi_mr_mobile_detect']->isMobile() || $GLOBALS['multi_mr_mobile_detect']->isTablet() ){
    		$get_multi_mr_redirects = get_multi_mr_redirects();
    		if(!$get_multi_mr_redirects){
    			return;
    		}
    		$actual_request = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    		foreach ($get_multi_mr_redirects as $value) {
    			if(strtolower($value['request']) == strtolower($actual_request)){
    				wp_redirect($value['redirect']);
    				exit;
    			}
    		}
    	}
    }

    public function multi_mr_update_redirects(){
    	if(current_user_can( 'activate_plugins')){
    		if(!empty($_POST['request_url']) || !empty($_POST['redirect_url'])){
		    	$output = array_map(function($a1, $a2){
		    		return array(
		    			'request' => esc_url($a1),
		    			'redirect' => esc_url($a2)
		    		);
		    	}, $_POST['request_url'], $_POST['redirect_url']);
		    	update_option('get_multi_mr_redirects', $output);
		    	exit(json_encode(['success'=>true, 'data'=>$output ] ));
	    	}else{
	    		exit(json_encode(['success'=>false, 'data'=>'No redirects was provided' ] ));
	    	}
    	}else{
    		exit(json_encode(['success'=>false, 'data'=>'Unathorized' ] ));
    	}
    	
    }

    public function multi_mr_clear_redirects(){
    	if(current_user_can( 'activate_plugins')) {
    		update_option('get_multi_mr_redirects', array());
    		exit(json_encode(['success'=>true, 'data'=>'Cleared' ] ));
    	}else{
    		exit(json_encode(['success'=>false, 'data'=>'Unathorized'] ));
    	}
    }

}