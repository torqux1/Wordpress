<?php 
/*
Plugin Name:  Interactive Maps
Plugin URI: http://simplemaps.com/wordpress
Description:  Easily add an JavaScript-powered HTML5 interactive map to your WordPress site.<br /> Free World Continent Map.  Also, premium commercial World, US, Canada, County, North America, Europe, UK maps available.
Author:  Simplemaps.com
Author URI: http://simplemaps.com
Version: 0.8
*/

add_action( 'admin_menu', 'my_plugin_menu' );
function my_plugin_menu() {	add_options_page( 'My Plugin Options', 'Interactive Maps', 'manage_options', 'simplemaps', 'my_plugin_options' );}
function my_plugin_options() {	if ( !current_user_can( 'manage_options' ) )  {		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );	}   

/*Start of Options*/  
add_action( 'admin_init', 'sm_register_settings' );  
function sm_register_settings(){register_setting('sm_settings_group', 'sm_settings');} 	

$mapdata_url = get_option('simplemaps_mapdata_url');  
$mapfile_url = get_option('simplemaps_mapfile_url'); 

if($_FILES['file']['name']) {    
  $upload_mapfile= wp_upload_bits($_FILES["file"]["name"], null, file_get_contents($_FILES["file"]["tmp_name"]));   
  if (isset($mapfile_url)){update_option('simplemaps_mapfile_url', $upload_mapfile['url']);}    
  else{add_option( 'simplemaps_mapfile_url', $upload_mapfile['url'], '', 'yes' );}    
  $mapfile_url = get_option('simplemaps_mapfile_url');    
}	

if($_FILES['data']['name']) {   
  $upload_mapdata= wp_upload_bits($_FILES["data"]["name"], null, file_get_contents($_FILES["data"]["tmp_name"]));   
  if (isset($mapdata_url)){
   update_option('simplemaps_mapdata_url', $upload_mapdata['url']);
  }    
  else{add_option('simplemaps_mapdata_url', $upload_mapdata['url'], '', 'yes' );}   
  $mapdata_url = get_option('simplemaps_mapdata_url');  
}  

	
if($_POST['replace']=='data'){$replace_data=true;}	else{$replace_data=false;}		
if($_POST['replace']=='file'){$replace_file=true;}	else{$replace_file=false;}  
 
 
 echo    '      <div id="icon-edit-pages" class="icon32"></div>    <div class="wrap">    <h1>Installation Instructions - Interactive Maps</h1>    <h3>Provided by: <a href="http://simplemaps.com">Simplemaps.com</a></h3>';   
 
 echo  '  <ol>    <li>Download the <strong>free</strong> World Continent map (<a href="http://simplemaps.com/resources/free-continent-map">here</a>).  Also available, <strong>paid</strong> <a href="http://simplemaps.com/us">US</a>, <a href="http://simplemaps.com/world">World</a>, <a href="http://simplemaps.com/europe">Europe</a>, <a href="http://simplemaps.com/canada">Canada</a>, <a href="http://simplemaps.com/north-america">North America</a>, <a href="http://simplemaps.com/county">County</a>, <a href="http://simplemaps.com/uk">UK</a> maps.</li>    <li>Unzip the map and open the folder.</li>    <li><a href="http://simplemaps.com/docs/">Customize</a> the map using our online tool or by editing the mapdata.js file and refreshing the test.html file.  <strong>For help</strong> see our <a href="http://simplemaps.com/docs">Documentation</a> or <a href="http://simplemaps.com/contact">contact us</a>.</li>    <li>Upload the files for your map <span style="color: red">(one at a time)</span>:</li>  '; 

if (isset($mapdata_url) & $mapdata_url!=null){$mapdata_exists = true;}  
else {$mapdata_exists = false;}  
if (isset($mapfile_url) & $mapfile_url!=null){$mapfile_exists = true;}  
else {$mapfile_exists = false;}    

 function file_form(){?> <form action="" method="post" enctype="multipart/form-data">   <b>Upload the map file</b><i> (continentmap.js)</i><br /> <input type="file" name="file"> <input type="submit" class="button" value="Submit"></form>	<br />  <?php   } 

 function data_form(){?>   <form action="" method="post" enctype="multipart/form-data">   <b>Upload the data file</b> <i>(mapdata.js)</i>  <br /> <input type="file" name="data"> <input type="submit" class="button" value="Submit"></form>	<br />  <?php   }  
 
 function replace_form($value){  ?>  <form name="test" action="" method="post" enctype="multipart/form-data">  <input type="hidden" name="replace" value="<?php echo $value ?>">   <input type="submit" class="button-secondary" value="Replace this File">  </form>    <br />  <?php  }   

 if (!$mapdata_exists || $replace_data){data_form();}	if ($mapdata_exists && !$replace_data){		echo 'You have uploaded your data file to: '. $mapdata_url;		replace_form('data');	}	
 
 if (!$mapfile_exists || $replace_file){file_form();}	if ($mapfile_exists && !$replace_file){		echo 'You have uploaded your map file to: '. $mapfile_url;		replace_form('file');	}  

 echo '			<li>Paste <span style="color: red;"> [simplemaps]</span> into the post/page where you want the map to be located.</li>		<li>Publish/Preview your post and your map should be visible and look something like: <br />		<img src="'.plugins_url().'/interactive-maps/includes/screenshot.png" />		</li>	</ol></div>';     /*End of options*/    }

function insert_code($atts){   $mapdata_url = get_option('simplemaps_mapdata_url');  $mapfile_url = get_option('simplemaps_mapfile_url');	
  return '<script type="text/javascript" src="'. $mapdata_url .'"></script><script type="text/javascript" src="'.$mapfile_url.'"></script><div id="map"></div>';
}

add_shortcode('simplemap', 'insert_code' );
add_shortcode('simplemaps', 'insert_code' );

