<?php

// Add Page Tag Meta Box
add_action('add_meta_boxes', 'themedy_add_pagetag_meta_box');
function themedy_add_pagetag_meta_box() {
	add_meta_box( 'themedy_pagetag_meta_box', __('Themedy Options', 'themedy'), 'themedy_pagetag_meta_box', 'page', 'side' );
}

function themedy_pagetag_meta_box( $post ) { 
	$themedy_title = get_post_meta($post->ID, 'themedy_title', true);
	$themedy_tag = get_post_meta($post->ID, 'themedy_tag', true);
	echo '<p><strong>'.__("Custom Title", "themedy").'</strong></p>';
	echo '<input type="text" rows="1" class="large-text" id="themedy_title" name="themedy_title" value="' . $themedy_title . '" />';
	echo '<p><strong>'.__("Custom Tagline", "themedy").'</strong></p>';
	echo '<textarea rows="3" cols="3" class="widefat" id="themedy_tag" name="themedy_tag">' . $themedy_tag . '</textarea>';
}

add_action('save_post', 'themedy_save_pagetag_postdata');
function themedy_save_pagetag_postdata( $post_id ) {
	global $post;
	
	if (isset( $_POST['themedy_title'] ) ) {
    	update_post_meta( $post_id, 'themedy_title', strip_tags( $_POST['themedy_title'] ) );
	}
	if (isset( $_POST['themedy_tag'] ) ) {
		update_post_meta( $post_id, 'themedy_tag', strip_tags( $_POST['themedy_tag'] ) );
	}
}