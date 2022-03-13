<?php
/*----------------------------------------------------------

Description: 	Testimonial and Features functionality for Themedy themes

----------------------------------------------------------*/

// Custom Themedy Testimonials
add_action( 'init', 'themedy_add_testimonials', 0 );
function themedy_add_testimonials() {

	$labels = array(
		'name'                => _x( 'Testimonials', 'Post Type General Name', 'themedy' ),
		'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'themedy' ),
		'menu_name'           => __( 'Testimonials', 'themedy' ),
		'parent_item_colon'   => __( 'Parent Item:', 'themedy' ),
		'all_items'           => __( 'All Testimonials', 'themedy' ),
		'view_item'           => __( 'View Testimonial', 'themedy' ),
		'add_new_item'        => __( 'Add New Testimonial', 'themedy' ),
		'add_new'             => __( 'Add New', 'themedy' ),
		'edit_item'           => __( 'Edit Testimonial', 'themedy' ),
		'update_item'         => __( 'Update Testimonial', 'themedy' ),
		'search_items'        => __( 'Search Testimonials', 'themedy' ),
		'not_found'           => __( 'No items found.', 'themedy' ),
		'not_found_in_trash'  => __( 'No items found in trash.', 'themedy' ),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'revisions', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
		'menu_icon' 		  => 'dashicons-editor-quote',
		'rewrite' 			  => array('slug'=>'themedy_testimonials'),
		'taxonomies'          => array( 'themedy_testimonials_comp' ),
	);
	register_post_type( 'themedy_testimonials', $args );
	
	$labels = array(
		'name'                       => _x( 'Organizations', 'Taxonomy General Name', 'themedy' ),
		'singular_name'              => _x( 'Organization', 'Taxonomy Singular Name', 'themedy' ),
		'menu_name'                  => __( 'Organizations', 'themedy' ),
		'all_items'                  => __( 'All Organizations', 'themedy' ),
		'parent_item'                => __( 'Parent Organization', 'themedy' ),
		'parent_item_colon'          => __( 'Parent Organization:', 'themedy' ),
		'new_item_name'              => __( 'New Organization Name', 'themedy' ),
		'add_new_item'               => __( 'Add New Organization', 'themedy' ),
		'edit_item'                  => __( 'Edit Organization', 'themedy' ),
		'update_item'                => __( 'Update Organization', 'themedy' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'themedy' ),
		'search_items'               => __( 'Search Organizations', 'themedy' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'themedy' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'themedy' ),
	);
	register_taxonomy(
        'themedy_testimonials_comp',        
        'themedy_testimonials',
        array(
            'hierarchical' 			 => TRUE,
            'label' 				 => 'Organizations',
			'labels'        		 => $labels,
        )		
    ); 
}

// Custom Themedy Features
add_action( 'init', 'themedy_add_features', 0 );
function themedy_add_features() {

	$labels = array(
		'name'                => _x( 'Features', 'Post Type General Name', 'themedy' ),
		'singular_name'       => _x( 'Feature', 'Post Type Singular Name', 'themedy' ),
		'menu_name'           => __( 'Features', 'themedy' ),
		'parent_item_colon'   => __( 'Parent Feature:', 'themedy' ),
		'all_items'           => __( 'All Features', 'themedy' ),
		'view_item'           => __( 'View Feature', 'themedy' ),
		'add_new_item'        => __( 'Add New Feature', 'themedy' ),
		'add_new'             => __( 'Add New', 'themedy' ),
		'edit_item'           => __( 'Edit Feature', 'themedy' ),
		'update_item'         => __( 'Update Feature', 'themedy' ),
		'search_items'        => __( 'Search Features', 'themedy' ),
		'not_found'           => __( 'No items found.', 'themedy' ),
		'not_found_in_trash'  => __( 'No items found in trash.', 'themedy' ),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'revisions', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
		'menu_icon' 		  => 'dashicons-star-filled',
		'rewrite' 			  => array('slug'=>'themedy_features')
	);
	register_post_type( 'themedy_features', $args );
}

// Add custom columns for the "manage" screen of post type.
function themedy_register_custom_columns ( $column_name, $id ) {
	global $wpdb, $post;

	$meta = get_post_custom( $id );

	switch ( $column_name ) {

		case 'image':
			$value = '';

			$value = themedy_get_image( $id, 40 );

			echo $value;
		break;

		default:
		break;

	}
}

// Add custom column headings for the "manage" screen of post type.
function themedy_register_custom_column_headings ( $defaults ) {
	$new_columns = array( 'image' => __( 'Image', 'themedy' ) );

	$last_item = '';

	//if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }

	if ( count( $defaults ) > 2 ) {
		$last_item = array_slice( $defaults, -1 );

		array_pop( $defaults );
	}
	$defaults = array_merge( $defaults, $new_columns );

	if ( $last_item != '' ) {
		foreach ( $last_item as $k => $v ) {
			$defaults[$k] = $v;
			break;
		}
	}

	return $defaults;
}

// Get image function for custom post type columns
function themedy_get_image ( $id, $size = 'themedy-feature-thumbnail' ) {
	$response = '';

	if ( has_post_thumbnail( $id ) ) {
		// If not a string or an array, and not an integer, default to 150x9999.
		if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
			$size = array( intval( $size ), intval( $size ) );
		} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
			$size = array( 150, 9999 );
		}
		$response = get_the_post_thumbnail( intval( $id ), $size );
	}

	return $response;
}

// Set up custom post type columns
if ( is_admin() ) {
	global $pagenow;
	if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == 'themedy_testimonials' ) {
		add_filter( 'manage_edit-' . 'themedy_testimonials' . '_columns', 'themedy_register_custom_column_headings' , 10, 1 );
		add_action( 'manage_posts_custom_column', 'themedy_register_custom_columns', 10, 2 );
	}
	if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == 'themedy_features' ) {
		add_filter( 'manage_edit-' . 'themedy_features' . '_columns', 'themedy_register_custom_column_headings' , 10, 1 );
		add_action( 'manage_posts_custom_column', 'themedy_register_custom_columns', 10, 2 );
	}
}

// Edit custom post type labels 
add_filter( 'enter_title_here', 'themedy_change_default_title' );
function themedy_change_default_title( $title ){
     $screen = get_current_screen();
 
     if  ( $screen->post_type == 'themedy_testimonials' ) {
          return 'Enter the testimonial name here';
     }
	 if  ( $screen->post_type == 'themedy_features' ) {
          return 'Enter the feature title here';
     }
	 
	 return $title;
}