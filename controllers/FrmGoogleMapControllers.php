<?php 
class GoogleMapController{

    function GoogleMapController(){
        add_filter('frm_pro_available_fields', array(&$this, 'add_field')); 
        add_filter('frm_before_field_created',array(&$this, 'set_defaults')); 
        add_action('frm_display_added_fields', array(&$this, 'admin_field')); 
        add_action('frm_form_fields', array(&$this, 'front_field'), 10, 2);

    }
    
    function add_field($fields){ 
        $fields['google-map-address'] = __('Map Location');
        $fields['google-map-latitude'] = __('Latitude');
        $fields['google-map-longitude'] = __('Longitude');
        return $fields;
    }
    
    function set_defaults($field_data){ //done
        if($field_data['type'] == 'google-map-address')
            $field_data['name'] = __('Map Location');
        if($field_data['type'] == 'google-map-latitude')
            $field_data['name'] = __('Latitude');
        if($field_data['type'] == 'google-map-longitude')
            $field_data['name'] = __('Longitude');
        return $field_data;
    }
    
    function admin_field($field){
	
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
    
    function front_field( $field, $field_name ){

        global $frm_editing_entry, $wp_scripts, $wp_styles, $frmpro_settings;
        $entry_id = $frm_editing_entry;

        if($field['type'] != 'google-map-address' && $field['type'] != 'google-map-latitude' && $field['type'] != 'google-map-longitude' )
            return;
			
        if( ! isset ($GLOBALS['frm-google-map']) ||  $GLOBALS['frm-google-map'] != 1 ){
            $GLOBALS['frm-google-map'] = 1 ;

			echo '<div id="frm-map-canvas" style="width:100%;height:300px;" class="google-map-it-canvas"></div>';
            echo '<label class="frm_primary_label">'. $field['name'] .'</label>';
			
			$initzoom = get_option('frm_google_map_init_zoom');
			if( strlen( $initzoom ) < 1 || $initzoom < 1 || $initzoom > 25 ){
				$initzoom = 5 ;
			}
			
			$geozoom = get_option('frm_google_map_geocoded_zoom');
			if( strlen( $geozoom  ) < 1 || $geozoom  < 1 || $geozoom  > 25 ){
				$geozoom  = 5 ;
			}
			
			$maptype = get_option('frm_google_map_maptype');
			
			$lat = get_option('frm_google_map_latitude');
			if(strlen($lat)< 1 || ! is_numeric( $lat ) || abs($lat) > 85 ){
				$lat = 0 ; 
			}
				$lat = sprintf( "%1\$.6f" , $lat ) ;
			
			
			$lng = get_option('frm_google_map_longitude');
			if(strlen($lng)< 1 || ! is_numeric( $lng ) || abs($lng ) > 180 ){
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
            if(strlen($value)< 2 ) {
				$value = get_option('frm_google_map_latitude');
				
				if(strlen( $value )< 1 || ! is_numeric( $value ) || abs($value) > 85 ){
					$value = 0 ; 
				}
					$value= sprintf( "%1\$.6f" , $value ) ;
				
			}
           
            echo '<input type="text" name="'. $field_name.'"  value="'.$value.'" id="frm-gmi-lat"  readonly="readonly" />';

        }elseif( $field['type'] == 'google-map-longitude' && ! isset( $GLOBALS['frm_geo_lng'] ) ){
		    $GLOBALS['frm_geo_lng']  = 1 ;
            $value = esc_attr($field['value']);
           if(strlen($value)< 2 ) {
				$value = get_option('frm_google_map_longitude');
				
				if(strlen( $value )< 1 || ! is_numeric( $value ) || abs($value ) > 180 ){
					$value = 0 ; 
				}
					$value= sprintf( "%1\$.6f" , $value ) ;
				
			}
            echo '<input type="text" name="'. $field_name.'"  value="'. $value .'" id="frm-gmi-lng" readonly="readonly" />';

        }
    }
}
?>