<?php  
// add submenu page
add_action( 'admin_menu' , 'frm_google_map_admin_menu');
function frm_google_map_admin_menu(){
    add_options_page( 'Formidable Google Map' , 'Frm Google Map' , 'manage_options' , __FILE__ , 'frm_google_map_admin_menu_page' );	
}

add_action('admin_init' , 'frm_google_map_admin' );

function frm_google_map_admin(){
    if ( isset($_GET['page']) && $_GET['page'] == 'formidable-entries' ){
        FrmGoogleMapController::register_scripts();
    }
}

// adding menu page content
function frm_google_map_admin_menu_page(){?>
<div class="wrap">
	<?php screen_icon('options-general'); ?>
	<h2>Formidable Google Map settings </h2>
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
	return false;
}


function frm_google_map_print_init_level(){?>
	<select name="frm_google_map_init_zoom" style="width:120px;">
		<?php 
		$selected = get_option('frm_google_map_init_zoom');
		if ( empty($selected) ) {
		    $selected = 10;
		}
		
		for ( $i = 1; $i <= 25; $i++ ) { ?>
		<option value="<?php echo $i ?>" <?php selected($selected, $i) ?>><?php echo $i ?></option>
		<?php
		}
		
		?>
	</select>
<?php 
}
  
function frm_google_map_print_geocoded_level() { ?>
	<select name="frm_google_map_geocoded_zoom" style="width:120px;">
		<?php 
		$selected = get_option('frm_google_map_geocoded_zoom');
		if ( empty($selected) ) {
		    $selected = 5;
		}
		
		for ( $i = 1; $i <= 25; $i++ ) { ?>
		<option value="<?php echo $i ?>" <?php selected($selected, $i) ?>><?php echo $i ?></option>
		<?php
		}
		?>
	</select>
<?php
}
 
function frm_google_map_print_maptype(){ ?>
    <select name="frm_google_map_maptype" style="width:120px;">
		<?php
		    $selected = get_option('frm_google_map_maptype');
		    $drop_options = array( 'ROADMAP', 'TERRAIN' , 'HYBRID' , 'SATELLITE');
			
			foreach ( $drop_options as $drop_option ) { ?>
			<option value="<?php echo $drop_option ?>" <?php echo selected($selected, $drop_option) ?>><?php echo $drop_option ?></option>
			<?php
			    unset($drop_option);
			}
		?>
	</select>
<?php 
}
  
function frm_google_map_print_latitude(){ ?>
<input type="number" name="frm_google_map_latitude" minnum="-85" maxnum="85" step="1" style="width:120px;" value="<?php echo (int) get_option('frm_google_map_latitude'); ?>" />
<?php
}

function frm_google_map_print_longitude(){ ?>
<input type="number" name="frm_google_map_longitude" minnum="0" maxnum="180" step="1" style="width:120px;" value="<?php echo (int) get_option('frm_google_map_longitude'); ?>" />
<?php
}

