<?php 
/*
Plugin Name: Formidable Google Map
Plugin URI: http://www.web-directory-service.com/downloads-2/
Description: Adds google map and latitude - longitude field to Formidable Pro ( FRM-001 )
Author:Crazy Hungarian
Version:1.0 
*/


define ( 'CH_GOOGLE_MAP_JS' ,  plugins_url( 'frm-google-map/js/' ));
define ( 'CH_GOOGLE_MAP_PATH', WP_PLUGIN_DIR.'/frm-google-map');
if(is_admin()){ require_once CH_GOOGLE_MAP_PATH .'/admin/settings.php'; }

// formidable functions

include_once(CH_GOOGLE_MAP_PATH . "/controllers/FrmGoogleMapControllers.php");
global $frm_google_map_controller;
$frm_google_map_controller = new GoogleMapController();

add_action('init' , 'frm_google_map_register');

function frm_google_map_register(){
    wp_register_script('frm_google_api' , 'https://maps.googleapis.com/maps/api/js?sensor=false' , array() , true );
    wp_register_script('frm_google_script' , CH_GOOGLE_MAP_JS . 'frmmap.js' , array( 'frm_google_api' ) , true ) ;
}

add_action('wp_footer' , 'frm_google_map_print');

function frm_google_map_print(){ 
    if(  isset ($GLOBALS['frm-google-map']) &&  $GLOBALS['frm-google-map'] == 1 ){
        wp_enqueue_script('frm_google_api' );
        wp_enqueue_script('frm_google_script' );
	
    }
}

add_action('admin_init' , 'frm_google_map_admin' );

function frm_google_map_admin(){ 


	
    
    if ( isset($_GET['page']) && $_GET['page'] == 'formidable-entries' ){
	    wp_enqueue_script('frm_google_api_admin' , 'https://maps.googleapis.com/maps/api/js?sensor=false' , array() , true );
        wp_enqueue_script('frm_google_script_admin' , CH_GOOGLE_MAP_JS . 'frmmap.js' , array( 'frm_google_api' ) , true ) ;
    }
}

register_activation_hook( __FILE__ , 'frm_google_map_activation' );

function frm_google_map_activation(){	
    add_option('frm_google_map_init_zoom' , 10 );
    add_option( 'frm_google_map_geocoded_zoom' , 6 );
	add_option( 'frm_google_map_maptype' , 'ROADMAP ');
	add_option(  'frm_google_map_latitude' , 0 );
	add_option(  'frm_google_map_longitude' , 0  );


}

add_action('wp_head', 'frm_gmi_style');

function frm_gmi_style(){?>

<style>
.google-map-it-canvas img {
    border: none !important;
    max-width: none !important;
}
.gmnoprint img { max-width: none !important ;}

</style>

<?php
}
?>