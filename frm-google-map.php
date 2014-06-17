<?php 
/*
Plugin Name: Formidable Geocoding
Plugin URI: http://www.web-directory-service.com/downloads-2/
Description: Adds google map and latitude - longitude field to Formidable Pro ( FRM-001 )
Author:Crazy Hungarian
Version: 1.0 
*/


if ( is_admin() ) {
    require_once( dirname( __FILE__ ) .'/admin/settings.php');
}

// formidable functions

include_once(dirname( __FILE__ ) . '/controllers/FrmGoogleMapControllers.php');
$frm_google_map_controller = new FrmGoogleMapController();

