<?php
/**s
 * Plugin Name:       SWS Video Library
 * Plugin URI:        https://ccharacter.com/custom-plugins/sws-video-lib/
 * Description:       Video lib
 * Version:           1.32
 * Requires at least: 5.2
 * Requires PHP:      5.5
 * Author:            Sharon Stromberg
 * Author URI:        https://ccharacter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sws-video-lib
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path(__FILE__).'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/ccharacter/sws-video-lib/main/plugin.json',
	__FILE__,
	'sws-video-lib'
);

require_once plugin_dir_path(__FILE__)."inc/custom_post_type.php";
//require_once plugin_dir_path(__FILE__)."inc/dir/assets/functions_sws.php";
//require_once plugin_dir_path(__FILE__)."inc/dir/assets/dir_functions.php";


// CREATE DEFAULT PAGE
function devotional_defaults() {
	if (!(sws_ck_post_exists("Daily Devotional","page"))) {
			
		$arr=array("post_type"=>"page","post_status"=>"publish","post_content"=>"[sws_daily_devotional]","post_title"=>"Daily Devotional",  'page_template'  => 'single-devotional.php');
		$id=wp_insert_post($arr);
		
		// INTRO
		update_post_meta($id,'intro',"An inspirational reading and Scripture passage to start your day off right");
		// THUMBNAIL	
		$image_url = plugins_url( 'img/1526049-SM.jpg', __FILE__); //echo $image_url;
		
		$upload_dir = wp_upload_dir();
		
		$image_data = file_get_contents( $image_url );
		
		$filename = basename( $image_url );
		
		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
		  $file = $upload_dir['path'] . '/' . $filename;
		}
		else {
		  $file = $upload_dir['basedir'] . '/' . $filename;
		}
		
		file_put_contents( $file, $image_data );
		
		$wp_filetype = wp_check_filetype( $filename, null );
		
		$attachment = array(
		  'post_mime_type' => $wp_filetype['type'],
		  'post_title' => sanitize_file_name( $filename ),
		  'post_content' => '',
		  'post_status' => 'inherit'
		);
		
		$attach_id = wp_insert_attachment( $attachment, $file, $id);
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		set_post_thumbnail( $id, $attach_id );

	}
	// add to admin	
   	global $submenu;
    $permalink = '/daily-devotional';
    $submenu['edit.php?post_type=dev_reading'][] = array( 'Today\'s Devotional', 'manage_options', $permalink );

    flush_rewrite_rules();
}
//add_action('admin_menu', 'devotional_defaults');

// SHORTCODE FOR directories  
/*function sws_dir_show($atts) {
	
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
</style>";
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
*/
?>

