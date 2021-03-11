<?php
/**s
 * Plugin Name:       SWS Directory/Interactive Map
 * Plugin URI:        https://ccharacter.com/custom-plugins/sws-dir-map/
 * Description:       Builds on the DBI to display interactive map/directory
 * Version:           1.3
 * Requires at least: 5.2
 * Requires PHP:      5.5
 * Author:            Sharon Stromberg
 * Author URI:        https://ccharacter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sws-dir-map
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path(__FILE__).'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/ccharacter/sws-dir-map/main/plugin.json',
	__FILE__,
	'sws_dir_map'
);

//require_once plugin_dir_path(__FILE__)."inc/dir/assets/Db.php";
//require_once plugin_dir_path(__FILE__)."inc/dir/assets/functions_sws.php";
//require_once plugin_dir_path(__FILE__)."inc/dir/assets/dir_functions.php";


// SHORTCODE FOR directories  
function sws_dir_show($atts) {
	
	$vars=array();
	
	$vars['themedir']=get_template_directory_uri();
	$vars['themedir2']=get_stylesheet_directory_uri();

	$a=shortcode_atts(array(
	  'group' => 'conf_asam',
	  'group2' => 'X',
	  'all_conf'=> 'Y',
	  'group_by_conf'=>'Y',
	  'min_title' => 'ASAM',
	  'show_prefixes' => "Y"
	), $atts);
	
	foreach ($a as $key=>$value) { 
		$vars[$key]=$value;
	} // NOTE TO SELF: SHORTCODE_ATTS DOESN'T LIKE UPPERCASE!!!!
	
	//error_log(print_r($vars,true),0);
	// construct the string being passed to the initial page
	$getVar=urlencode(base64_encode(json_encode($vars)));
	//error_log($getVar);
	
	ob_start(); 
	if ($vars['group_by_conf']=="Y") { 
		echo "<iframe src='".plugins_url( '/inc/dir/dir_unions.php?vars='.$getVar, __FILE__ )."' style='width: 100%; height: 80vh; min-height: 50em;' frameborder='no' scrolling='no'></iframe>"; 
	} else {
		echo "<iframe src='".plugins_url( '/inc/dir/dir_page.php?vars='.$getVar, __FILE__ )."' style='width: 100%; height: 80vh; min-height: 50em;' frameborder='no' scrolling='no'></iframe>"; 		
	}
	return ob_get_clean();
}

// register shortcode
add_shortcode('sws_dir_listing', 'sws_dir_show'); 


// SHORTCODE FOR Searchable Counselor Directory 
function sws_search_show($atts) {
	
	$vars=array();
	
	$vars['themedir']=get_template_directory_uri();
	$vars['themedir2']=get_stylesheet_directory_uri();

	$a=shortcode_atts(array(
	  'group' => 'conf_asam',
	  'min_title' => 'ASAM',
	  //'show_prefixes' => "Y"
	), $atts);
	
	foreach ($a as $key=>$value) { 
		$vars[$key]=$value;
	} // NOTE TO SELF: SHORTCODE_ATTS DOESN'T LIKE UPPERCASE!!!!
	
	//error_log(print_r($vars,true),0);
	// construct the string being passed to the initial page
	$getVar=urlencode(base64_encode(json_encode($vars)));
	//error_log($getVar);
	
	ob_start(); 
		echo "<iframe src='".plugins_url( '/inc/dir/counselor_directory.php?vars='.$getVar, __FILE__ )."' style='width: 100%; height: 80vh; min-height: 50em;' frameborder='no' scrolling='no'></iframe>"; 
	return ob_get_clean();
}

// register shortcode
add_shortcode('sws_dir_search', 'sws_search_show');


// SHORTCODE FOR pics directories  
function ejj_photo_dir($atts) {
	
	$vars=array();
	
	$vars['themedir']=get_template_directory_uri();
	$vars['themedir2']=get_stylesheet_directory_uri();

	$a=shortcode_atts(array(
	  'group' => 'conf_asam',
	  'group2' => 'X',
	  'all_conf'=> 'Y',
	  'group_by_conf'=>'Y',
	  'min_title' => 'ASAM',
	  'show_prefixes' => "Y"
	), $atts);
	
	foreach ($a as $key=>$value) { 
		$vars[$key]=$value;
	} // NOTE TO SELF: SHORTCODE_ATTS DOESN'T LIKE UPPERCASE!!!!
	
	//error_log(print_r($vars,true),0);
	// construct the string being passed to the initial page
	$getVar=urlencode(base64_encode(json_encode($vars)));
	//error_log($getVar);
	
	ob_start(); 

/*echo 	"<style>
iframe {
	width: 1px;
	min-width: 100%;
}
</style>";*/
	if ($vars['group_by_conf']=="Y") { 
		echo "<iframe id='myIframe' src='".plugins_url( '/inc/dir/dir_unions_ejj.php?vars='.$getVar, __FILE__ )."' style='width: 100%; height:1000px; min-height: 50em;' frameborder='no' scrolling='no'></iframe>"; 
	} else {
		echo "<iframe id='myIframe' src='".plugins_url( '/inc/dir/dir_page_ejj.php?vars='.$getVar, __FILE__ )."' style='width: 100%; height:1000px; min-height: 50em;' frameborder='no' scrolling='no'></iframe>"; 
	}
	echo "<script>
  iFrameResize({ log: true }, '#myIframe')
</script>";
	return ob_get_clean();
}

// register shortcode
add_shortcode('sws_dir_photo', 'ejj_photo_dir');

?>

