<?php
// Custom Staff Post Type //
add_action('init', 'register_videos');

function register_videos()
{
	register_post_type('videos', array(
		'label' => 'Videos',
		'description' => '',
		'has_archive' => true,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'menu_icon' => 'dashicons-media-video',
		'menu_position' => '6',
		'hierarchical' => true,
		'rewrite' => array(
			'slug' => 'videos'
		),
		'taxonomies' => array( ),
		'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'can_export' => true,
		'query_var' => true,
		'exclude_from_search' => false,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'post'
		),
		
		'labels' => array(
			'name' => 'Videos',
			'singular_name' => 'Video',
			'menu_name' => 'Manage Videos',
			'add_new' => 'Add Video',
			'add_new_item' => 'Add New Video',
			'edit' => 'Edit',
			'edit_item' => 'Edit Video',
			'new_item' => 'New Video',
			'view' => 'View Videos',
			'view_item' => 'View Video',
			'search_items' => 'Search Videos',
			'not_found' => 'No Videos Found',
			'not_found_in_trash' => 'No Videos Found in Trash',
			'parent' => 'Parent Video',
		) ,
	));
}


function videos_taxonomy() {  
    register_taxonomy(  
        'video_ppl',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'videos',        //post type name
        array(  
            'hierarchical' => true,  
			'show_ui'=>true,
			'show_admin_column'=>true,
            'label' => 'People in Videos',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'videos', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    ); 
	
    register_taxonomy(  
        'video_topics',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'videos',        //post type name
        array(  
            'hierarchical' => true,  
			'show_ui'=>true,
			'show_admin_column'=>true,
            'label' => 'Video Topics',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'videos', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );  
 
}  
add_action( 'init', 'videos_taxonomy');


// EXCLUDE TAGS FROM LISTING
add_filter('get_the_terms', 'exclude_terms');
remove_filter('get_the_terms', 'exclude_terms');

function exclude_terms($terms) {
    $exclude_terms = array(36); //put term ids here to remove!
    if (!empty($terms) && is_array($terms)) {
        foreach ($terms as $key => $term) {
            if (in_array($term->term_id, $exclude_terms)) {
                unset($terms[$key]);
            }
        }
    }

    return $terms;
}

// set videos to MEDIA category on save
add_action( 'save_post', 'set_default_category' );
function set_default_category( $post_id ) {

    if (get_post_type($post_id) =='page') {
		return;
	}
	wp_set_post_categories($post_id, 43,true);
	return;
}

?>