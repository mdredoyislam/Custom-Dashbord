<?php
/*
Plugin Name: Custom Dashbord
Plugin URI: https://redoyit.com/
Description: Custom Dashbord Include In WordPress
Version: 5.3
Requires at least: 5.8
Requires PHP: 5.6.20
Author: Md. Redoy Islam
Author URI: https://redoyit.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: customdashbord
Domain Path: /languages
*/
//Plugin File Directory Constants Define
define('CMDB_ASSETS_DIR', plugin_dir_url(__FILE__).'assets/');
define('CMDB_ASSETS_PUBLIC_DIR', CMDB_ASSETS_DIR.'public/');
define('CMDB_ASSETS_PUBLIC_JS_DIR', CMDB_ASSETS_PUBLIC_DIR.'js/');
define('CMDB_ASSETS_PUBLIC_CSS_DIR', CMDB_ASSETS_PUBLIC_DIR.'css/');
define('CMDB_ASSETS_PUBLIC_IMG_DIR', CMDB_ASSETS_PUBLIC_DIR.'images/');
define('CMDB_ASSETS_ADMIN_DIR', CMDB_ASSETS_DIR.'admin/');
define('CMDB_ASSETS_ADMIN_JS_DIR', CMDB_ASSETS_ADMIN_DIR.'js/');
define('CMDB_ASSETS_ADMIN_CSS_DIR', CMDB_ASSETS_ADMIN_DIR.'css/');
define('CMDB_ASSETS_ADMIN_IMG_DIR', CMDB_ASSETS_ADMIN_DIR.'images/');

define('CMDB_VERSION', time());

class CustomDashbord{
    private $version;
    public function __construct(){
        $this->version = time();
        //add_action('admin_post_cmdb_admin_page', array($this, 'cmdb_save_form'));
        add_action('plugin_loaded', array($this, 'cmdb_load_textdomain'));
        add_action('admin_enqueue_scripts', array($this, 'cmdb_load_admin_assets'));
        add_action('admin_menu', array($this, 'cmdb_create_admin_page'));
        add_action('admin_menu', array($this, 'cmdb_create_project_page'));
    }

    function cmdb_create_project_page(){
        $page_title = __('CMDB Project', 'customdashbord');
        $menu_title = __('CMDB Project', 'customdashbord');
        $capability = 'manage_options';
        $slug = 'cmdbproject';
        $callback = array($this, 'cmdb_project_content');
        add_menu_page($page_title, $menu_title, $capability, $slug, $callback);
    }
    function cmdb_project_content(){
        require_once plugin_dir_path(__FILE__)."/templates/cmdb-project.php";
    }
    function cmdb_create_admin_page(){
        $page_title = __('CMDB Dashbord', 'customdashbord');
        $menu_title = __('CMDB Dashbord', 'customdashbord');
        $capability = 'manage_options';
        $slug = 'cmdbmain';
        $callback = array($this, 'cmdb_settings_content');
        add_menu_page($page_title, $menu_title, $capability, $slug, $callback);
    }
    function cmdb_settings_content(){
        require_once plugin_dir_path(__FILE__)."/templates/cmdb-main.php";
    }

    /*function cmdb_save_form(){
        check_admin_referer('optiondemo');

        if(isset($_POST['opd_latitude']) || isset($_POST['opd_longitude']) || isset($_POST['opd_zoom_label']) || isset($_POST['opd_api_key']) || isset($_POST['opd_extarnal_css']) || isset($_POST['opd_expiry_date'])){
            update_option('opd_latitude', sanitize_text_field($_POST['opd_latitude']));
            update_option('opd_longitude', sanitize_text_field($_POST['opd_longitude']));
            update_option('opd_zoom_label', sanitize_text_field($_POST['opd_zoom_label']));
            update_option('opd_api_key', sanitize_text_field($_POST['opd_api_key']));
            update_option('opd_extarnal_css', sanitize_text_field($_POST['opd_extarnal_css']));
            update_option('opd_expiry_date', sanitize_text_field($_POST['opd_expiry_date']));
        }
        wp_redirect('admin.php?page=optiondemopage');
    }*/

    function cmdb_load_admin_assets($screen){
        if('toplevel_page_cmdbmain' == $screen || 'toplevel_page_cmdbproject' == $screen){

            $css_files = array(
                'pace-style' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/pace/pace-theme-minimal.css'),
                'bootstrap-style' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/bootstrap/css/bootstrap.css'),
                'font-awesome-style' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/font-awesome/css/font-awesome.css'),
                'animate-style' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/animate.css/animate.css'),
                'toastr-style' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/toastr/toastr.min.css'),
                'magnific-popup-style' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/magnific-popup/magnific-popup.css'),
                'template-style' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'stylesheets/css/style.css'),
                'cmdb-main-style' => array('path'=>CMDB_ASSETS_ADMIN_CSS_DIR.'cmdb-main.css'),
            );
            foreach($css_files as $handle=>$fileinfo){
                wp_enqueue_style($handle, $fileinfo['path'], $this->version, false);
            }
    
            $js_files = array(
                'pace' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/pace/pace.min.js', 'dep'=> array('jquery')),
                'jquery-1.12.3' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/jquery/jquery-1.12.3.min.js', 'dep'=> null),
                'bootstrap' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/bootstrap/js/bootstrap.min.js', 'dep'=> array('jquery')),
                'nano-scroller' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/nano-scroller/nano-scroller.js', 'dep'=> array('jquery')),
                'template-script' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'javascripts/template-script.min.js', 'dep'=> array('jquery')),
                'template-init' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'javascripts/template-init.min.js', 'dep'=> array('jquery')),
                'toastr' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/toastr/toastr.min.js', 'dep'=> array('jquery')),
                'chart-js' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/chart-js/chart.min.js', 'dep'=> array('jquery')),
                'magnific-popup' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'vendor/magnific-popup/jquery.magnific-popup.min.js', 'dep'=> array('jquery')),
                'template-dashboard' => array('path'=>CMDB_ASSETS_ADMIN_DIR.'javascripts/examples/dashboard.js', 'dep'=> array('jquery')),
                'cmdb-main' => array('path'=>CMDB_ASSETS_ADMIN_JS_DIR.'cmdb-main.js', 'dep'=> array('jquery')),
            );
            foreach($js_files as $handle=>$fileinfo){
                wp_enqueue_script($handle, $fileinfo['path'], $fileinfo['dep'], $this->version, true);
            }
        }
    }

    function cmdb_load_textdomain(){ 
        load_plugin_textdomain('customdashbord', false, dirname(__FILE__) . '/languages');
    }
}
new CustomDashbord();