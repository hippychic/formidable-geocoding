<?php  
// add submenu page
add_action( 'admin_menu' , 'frm_google_map_admin_menu');
function frm_google_map_admin_menu(){
    add_options_page( 'Frm Google Map' , 'Frm Google Map' , 'manage_options' , __FILE__ , 'frm_google_map_admin_menu_page' );	
}
// adding menu page content
 function frm_google_map_admin_menu_page(){?>
 <div class="wrap">
	<?php screen_icon('options-general'); ?>
	<h2> Frm Google Map settings </h2>
	<form action="options.php" method="post">
			<?php settings_fields('frm_google_map'); ?>
			<?php do_settings_sections('frm_google_map'); ?>
			<div style="clear:both;" ></div>
			<br />
			<p>Valid <b>latitude</b> values are between -85 and +85</p>
			<p>Valid <b>longitude</b> values are between 0 and 180 </p>
			<input  name="Submit" type="submit" value="Save Changes" class="button-primary"/>
	</form>
 
</div>
 
 
 <?php
 }
 
 
 
 add_action('admin_init' , 'frm_google_admin_init');
 function frm_google_admin_init(){
	register_setting('frm_google_map' , 'frm_google_map_init_zoom' );
	register_setting('frm_google_map' , 'frm_google_map_geocoded_zoom' );
	register_setting('frm_google_map' , 'frm_google_map_maptype' );
	register_setting('frm_google_map' , 'frm_google_map_latitude' );
	register_setting('frm_google_map' , 'frm_google_map_longitude' );
	
	
	add_settings_field( 'frm_gmi_init' , 'Map Zoom Level' , 'frm_google_map_print_init_level' , 'frm_google_map' , 'frm_google_map_section' );
	
	add_settings_field( 'frm_gmi_geococed' , 'Map Zoom Level After Geocoding' , 'frm_google_map_print_geocoded_level' , 'frm_google_map' , 'frm_google_map_section' );
	add_settings_field( 'frm_gmi_maptype' , 'Select Maptype' , 'frm_google_map_print_maptype' , 'frm_google_map' , 'frm_google_map_section' );
	add_settings_field( 'frm_gmi_lat' , 'Enter Map Center Latitude' , 'frm_google_map_print_latitude' , 'frm_google_map' , 'frm_google_map_section' );
	add_settings_field( 'frm_gmi_lmg' , 'Enter Map Center Longitude' , 'frm_google_map_print_longitude' , 'frm_google_map' , 'frm_google_map_section' );
	
	add_settings_section( 'frm_google_map_section' , '' , 'frmgmi_section_text' , 'frm_google_map' );
 }
 function frmgmi_section_text(){
	return false ;
 
 }
 function frm_google_map_print_init_level(){?>
	<select name="frm_google_map_init_zoom" style="width:120px;">
		<?php $selected = get_option('frm_google_map_init_zoom'); $drop_options = array( 1 , 2 , 3 , 4 , 5 , 6 , 7 , 8 , 9 , 10 , 11 , 12 , 13 , 14 ,15 , 16 , 17 , 18 , 19 , 20 , 21, 22, 23 , 24 , 25 );
			foreach($drop_options as $drop_option){
				if($drop_option == $selected){ echo '<option value="'.$drop_option.'" selected="selected">'.$drop_option.'</option>'."\n";
				}
			}
			foreach($drop_options as $drop_option){
				if($drop_option != $selected){ echo '<option value="'.$drop_option.'" >'.$drop_option.'</option>'."\n";}
				
			}
			
		
		?>
		
	</select>
  <?php }
  
function frm_google_map_print_geocoded_level() { ?>
	<select name="frm_google_map_geocoded_zoom" style="width:120px;">
		<?php $selected = get_option('frm_google_map_geocoded_zoom'); $drop_options = array( 1 , 2 , 3 , 4 , 5 , 6 , 7 , 8 , 9 , 10 , 11 , 12 , 13 , 14 ,15 , 16 , 17 , 18 , 19 , 20 , 21, 22, 23 , 24 , 25 );
			foreach($drop_options as $drop_option){
				if($drop_option == $selected){ echo '<option value="'.$drop_option.'" selected="selected">'.$drop_option.'</option>'."\n";
				}
			}
			foreach($drop_options as $drop_option){
				if($drop_option != $selected){ echo '<option value="'.$drop_option.'" >'.$drop_option.'</option>'."\n";}
				
			}
		?>
	</select>
  <?php }
 
 function frm_google_map_print_maptype(){ ?>
	<select name="frm_google_map_maptype" style="width:120px;">
		<?php $selected = get_option('frm_google_map_maptype'); $drop_options = array( 'ROADMAP', 'TERRAIN' , 'HYBRID' , 'SATELLITE');
			foreach($drop_options as $drop_option){
				if($drop_option == $selected){ echo '<option value="'.$drop_option.'" selected="selected">'.$drop_option.'</option>'."\n";
				}
			}
			foreach($drop_options as $drop_option){
				if($drop_option != $selected){ echo '<option value="'.$drop_option.'" >'.$drop_option.'</option>'."\n";}
				
			}
		?>
	</select>
  <?php }
  function frm_google_map_print_latitude(){?>
		<input type="text" name="frm_google_map_latitude"  style="width:120px;"  value="<?php echo get_option('frm_google_map_latitude'); ?>" />
  <?php
  }
   function frm_google_map_print_longitude(){?>
		<input type="text" name="frm_google_map_longitude"  style="width:120px;"  value="<?php echo get_option('frm_google_map_longitude'); ?>" />
  <?php
  }

  ?>