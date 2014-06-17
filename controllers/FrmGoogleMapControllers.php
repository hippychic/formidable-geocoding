<?php 
class FrmGoogleMapController{

    function __construct() {
        add_action('init', array('FrmGoogleMapController', 'register_scripts'));
        add_action('wp_head', array('FrmGoogleMapController', 'style'));
        add_action('wp_footer' , array('FrmGoogleMapController', 'print_map'));
        
        add_filter('frm_pro_available_fields', array('FrmGoogleMapController', 'add_field')); 
        add_filter('frm_before_field_created', array('FrmGoogleMapController', 'set_defaults')); 
        add_action('frm_display_added_fields', array('FrmGoogleMapController', 'admin_field')); 
        add_action('frm_form_fields', array('FrmGoogleMapController', 'front_field'), 10, 2);

    }
    
    public static function register_scripts() {
        wp_register_script('frm_google_api', 'https://maps.googleapis.com/maps/api/js?sensor=false', array(), true );
        wp_register_script('frm_google_script',  dirname(dirname(__FILE__)) . '/js/frmmap.js', array( 'frm_google_api' ), true );
    }
    
    public static function style() {
?>
<style>
.google-map-it-canvas img {
    border: none !important;
    max-width: none !important;
}
.gmnoprint img { max-width: none !important;}
</style>
<?php
    }
    
    public static function print_map() { 
        if ( isset ($GLOBALS['frm-google-map']) &&  $GLOBALS['frm-google-map'] == 1 ) {
            wp_enqueue_script('frm_google_api' );
            wp_enqueue_script('frm_google_script' );
        }
    }
    
    public static function add_field($fields) { 
        $fields['google-map-address'] = __('Map Location');
        $fields['google-map-latitude'] = __('Latitude');
        $fields['google-map-longitude'] = __('Longitude');
        return $fields;
    }
    
    public static function set_defaults($field_data) { //done
        if($field_data['type'] == 'google-map-address')
            $field_data['name'] = __('Map Location');
        if($field_data['type'] == 'google-map-latitude')
            $field_data['name'] = __('Latitude');
        if($field_data['type'] == 'google-map-longitude')
            $field_data['name'] = __('Longitude');
        return $field_data;
    }
    
    public static function admin_field($field) {
	
        if($field['type'] == 'google-map-address' ){
           $field_name = "item_meta[". $field['id'] ."]";?>
		   
        <div style="width:100%;margin-bottom:10px;text-align:center;">
            <div class="howto button-secondary frm_html_field"><?php _e('This is a placeholder for your google map address field. <br/>View your form to see it in action.', 'formidable') ?></div>   
        </div>
    <?php
    }

        if($field['type'] == 'google-map-latitude' ){
           $field_name = "item_meta[". $field['id'] ."]";?>
		   
        <div style="width:100%;margin-bottom:10px;text-align:center;">
            <div class="howto button-secondary frm_html_field"><?php _e('This is a placeholder for your google map latitude field. <br/>View your form to see it in action.', 'formidable') ?></div> 
        </div>
        <?php
    }

        if($field['type'] == 'google-map-longitude' ){
           $field_name = "item_meta[". $field['id'] ."]";?>
		   
        <div style="width:100%;margin-bottom:10px;text-align:center;">
            <div class="howto button-secondary frm_html_field"><?php _e('This is a placeholder for your google map longitude field. <br/>View your form to see it in action.', 'formidable') ?></div>  
        </div>
    <?php
    }
    }
    
    public static function front_field( $field, $field_name ){

        global $frm_editing_entry, $wp_scripts, $wp_styles, $frmpro_settings;
        $entry_id = $frm_editing_entry;

        if($field['type'] != 'google-map-address' && $field['type'] != 'google-map-latitude' && $field['type'] != 'google-map-longitude' )
            return;
			
        if( ! isset ($GLOBALS['frm-google-map']) ||  $GLOBALS['frm-google-map'] != 1 ){
            $GLOBALS['frm-google-map'] = 1 ;

			echo '<div id="frm-map-canvas" style="width:100%;height:300px;" class="google-map-it-canvas"></div>';
            echo '<label class="frm_primary_label">'. $field['name'] .'</label>';
			
			$initzoom = get_option('frm_google_map_init_zoom');
			if( empty( $initzoom ) || $initzoom > 25 ){
				$initzoom = 10;
			}
			
			$geozoom = get_option('frm_google_map_geocoded_zoom');
			if ( empty( $geozoom  ) || $geozoom  > 25 ) {
				$geozoom  = 5;
			}
			
			$maptype = get_option('frm_google_map_maptype');
			if ( empty($maptype) ) {
			    $maptype = 'ROADMAP';
			}
			
			$lat = (int) get_option('frm_google_map_latitude');
			if ( abs($lat) > 85 ) {
				$lat = 0 ; 
			}
			$lat = sprintf( "%1\$.6f" , $lat ) ;
			
			
			$lng = (int) get_option('frm_google_map_longitude');
			if ( abs($lng ) > 180 ) {
				$lng = 0 ; 
			}
			$lng = sprintf( "%1\$.6f" , $lng ) ;
			
		    echo '<input type="hidden"  id="frm-gm-init-zoom" value="'. $initzoom .'" />';
			echo '<input type="hidden"  id="frm-gm-geo-zoom" value="'.$geozoom.'" />';
			echo '<input type="hidden"  id="frm-gm-maptype" value="'.$maptype.'" />';
			echo '<input type="hidden"  id="frm-gm-lat" value="'.$lat.'" />';
			echo '<input type="hidden"  id="frm-gm-lng" value="'.$lng.'" />';
		
		}
		
		
        if($entry_id){
            global $frm_entry; 
 
            //make sure entry is for this form
            $entry = $frm_entry->getOne((int)$entry_id);
            if(!$entry or $entry->form_id != $field['form_id'])
                $entry_id = false;
            unset($entry);
        }

		if( $field['type'] == 'google-map-address' && ! isset( $GLOBALS['frm_geo_add'] )) {
			$GLOBALS['frm_geo_add'] = 1 ;
            $value = esc_attr($field['value']);
			
            echo '<input type="text" name="'. $field_name.'"  value="'.$value.'" id="frm-gmi-loc" onfocus="reset_map_location( this );" onkeyup="ch_pp_address_onkeyup(event);" onblur="ch_pp_addressinput();" />';

        }elseif($field['type'] == 'google-map-latitude' && ! isset( $GLOBALS['frm_geo_lat'] )){
			$GLOBALS['frm_geo_lat'] = 1 ;
            $value = esc_attr($field['value']);
            if ( strlen($value)< 2 ) {
				$value = (int) get_option('frm_google_map_latitude');
				
				if ( abs($value) > 85 ) {
					$value = 0; 
				}
				$value = sprintf( "%1\$.6f" , $value );
			}
           
            echo '<input type="text" name="'. $field_name.'"  value="'.$value.'" id="frm-gmi-lat"  readonly="readonly" />';

        }elseif( $field['type'] == 'google-map-longitude' && ! isset( $GLOBALS['frm_geo_lng'] ) ){
		    $GLOBALS['frm_geo_lng']  = 1 ;
            $value = esc_attr($field['value']);
            if ( strlen($value) < 2 ) {
				$value = (int) get_option('frm_google_map_longitude');
				
				if ( abs($value ) > 180 ) {
					$value = 0; 
				}
				$value = sprintf( "%1\$.6f" , $value ) ;
				
			}
            echo '<input type="text" name="'. $field_name.'"  value="'. $value .'" id="frm-gmi-lng" readonly="readonly" />';

        }
    }
}
