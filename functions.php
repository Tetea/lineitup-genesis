<?php
// Start Genesis and Themedy options
include_once( get_template_directory() . '/lib/init.php' );
include_once(get_stylesheet_directory() . '/lib/init.php');

// Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

// Add Genesis structural wraps
add_theme_support( 'genesis-structural-wraps', array( 'header', 'inner', 'footer' ) );

// Add WP Background Image Support
add_theme_support( 'custom-background', array(
	'default-color' => 'EFEFEF',
	'default-image' => get_stylesheet_directory_uri() . '/images/bg-body.png',
	'default-repeat'         => 'tile',
) );

// Add WP header image support
add_theme_support( 'custom-header', array(
	'default-image'			=> get_stylesheet_directory_uri() . '/images/logo.png',
	'default-text-color'	=> 'fff',
	'flex-width'        	=> true,
	'width'					=> 100,
	'flex-height'       	=> true,
	'height'				=> 15,
	'wp-head-callback'		=> 'header_style',
	'admin-head-callback'	=> 'admin_header_style'
) );

function header_style() {
	$text_color = get_theme_mod( 'header_textcolor', get_theme_support( 'custom-header', 'default-text-color' ) );
	if ( get_header_textcolor() != 'blank' )
		echo "<style type= \"text/css\">.title-area .site-title a { color: #$text_color }</style>\n";
}

function admin_header_style() {
	echo '<style type="text/css"> #headimg { width: '.get_custom_header()->width.'px; height: '.get_custom_header()->height.'px; background-repeat: no-repeat; } #headimg h1 { margin: 0; } #headimg h1 a { text-decoration: none; display: block; padding: 0.5em 0; background: #fff; } #headimg #desc { background: #fff; height: '.get_custom_header()->height.'px; }</style>';
}

// Header image output control
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
if ( get_header_textcolor() == 'blank' ) {
	remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
	add_action( 'genesis_site_title', 'custom_site_title' );
	function custom_site_title() { 
		$header_image = esc_url(get_header_image());	
		if (!empty($header_image))
			echo 
			"<h1 class=\"site-title logo\">",
				"<a href=\"", esc_url(home_url()), "\">",
					"<img width=\"", get_custom_header()->width, "\" height=\"", get_custom_header()->height, "\" src=\"", $header_image, "\" alt=\"", get_bloginfo('title'), "\" />",
				"</a>",
			"</h1>";
	}
}

// Head CSS for action color (if needed)
add_action('wp_head', 'themedy_custom_action_color');
function themedy_custom_action_color() {
	$color = themedy_get_option('action_color');
	if ($color != '#425d80')
		echo "<style type= \"text/css\">.header-wrap { background-color: $color; }</style>\n";
}

// Enqueue external scripts and styles
add_action('wp_enqueue_scripts', 'themedy_enqueue', 15);
function themedy_enqueue() {
	// Custom CSS Legacy
	if (is_file(CHILD_DIR.'/custom/custom.css')) {
		wp_enqueue_style('themedy-child-theme-custom-style', CHILD_URL.'/custom/custom.css',CHILD_THEME_NAME,CHILD_THEME_VERSION);
	}
	// Mobile Menu
	if (themedy_get_option('mobile_menu')) {
		wp_enqueue_style('mmenu', CHILD_THEME_LIB_URL.'/css/jquery.mmenu.css','','4.7.5');
		wp_enqueue_script('mmenu', CHILD_THEME_LIB_URL.'/js/jquery.mmenu.min.js', array('jquery'), '4.7.5', TRUE);
		wp_enqueue_script('mmenu-args', CHILD_THEME_LIB_URL.'/js/jquery.mmenu-args.js', array('jquery'), '1', TRUE);
	}
}

// Mobile body class
add_filter('body_class', 'themedy_body_class');
function themedy_body_class($class) {
	if (themedy_get_option('mobile_menu')) {
		$class[] = 'mobile-enabled';
	}
	return $class;
}

// Enqueue Included Fonts
add_action( 'wp_enqueue_scripts', 'themedy_load_fonts' );
function themedy_load_fonts() {
	wp_enqueue_style( 'themedy-fonts', CHILD_THEME_LIB_URL.'/fonts/fonts.css', array(), CHILD_THEME_VERSION );
}

// New Image Sizes
add_image_size('featured-thumb', 234, 100, TRUE);
add_image_size('themedy-testimonial-standard', 48, 48, TRUE);
add_image_size('themedy-feature-standard', 48, 48, TRUE);
add_image_size('themedy-blog-thumbnail', 680, 250, TRUE);

// Add the Nav In Header
remove_action('genesis_after_header', 'genesis_do_nav');
add_action('genesis_header', 'genesis_do_nav');

// No Secondary Nav
remove_action('genesis_after_header', 'genesis_do_subnav');
add_theme_support( 'genesis-menus', array( 'primary' => 'Primary Navigation Menu' ) );

// Add Mobile Menu Toggle
add_action('genesis_header', 'themedy_nav_menu_toggle');
function themedy_nav_menu_toggle() {
	if (themedy_get_option('mobile_menu')) {
		echo '<a class="toggle-menu" href="#mobile-menu"><i class="icon-menu"></i><span class="screen-reader-text">'.__('Toggle Mobile Menu', 'themedy').'</span></a>';
	}
}

// Hide Headlines on Pages
if (themedy_get_option('secondary_area')) add_action('genesis_before_loop', 'themedy_remove_heading');
function themedy_remove_heading() {
	global $post;
	if (empty($post)) return;
	$template = get_post_meta($post->ID,'_wp_page_template',true);
	if ((is_page() || get_post_type() == 'portfolio' || get_post_type() == 'testimonial' || get_post_type() == 'feature' ) and $template != "page_blog.php" and !is_archive()) remove_action('genesis_entry_header', 'genesis_do_post_title');
}

// Add Featured Area Wrap
add_action('genesis_before_header', 'themedy_featured_wrap');
function themedy_featured_wrap() { ?>
	<div class="header-wrap">
<?php }


// Add the Secondary Header
add_action('genesis_after_header', 'themedy_sec_header');
function themedy_sec_header() {
	if (!is_page_template('page_home.php') && themedy_get_option('secondary_area')) {
	?>
        <div class="page-area">
            <div class="wrap">
                <?php
                global $post;
				if ($post) {
                	$themedy_title = get_post_meta($post->ID, 'themedy_title', true);
				} else {
					$themedy_title  = '';
				}
    
                if ($themedy_title != "") { echo "<h1 class=\"page-title\">". $themedy_title ."</h1>"; }
                elseif (is_page()) { echo "<h1 class=\"entry-title page-title\">"; the_title(); echo "</h1>"; }
                elseif ((is_home() || is_single()) && themedy_get_option('blog_title') && get_post_type() == 'post') { echo '<h2 class="page-title">'.esc_attr(themedy_get_option('blog_title')).'</h2>'; }
                elseif (is_home() || is_single() && get_post_type() == 'post') { echo "<h2 class=\"page-title\">".__('Blog','themedy')."</h2>"; }
				elseif (is_single() && get_post_type() == 'product') { echo "<h2 class=\"page-title\">".__('Shop','themedy')."</h2>"; }
				elseif (themedy_active_plugin() == 'woocommerce' and is_woocommerce()) { echo "<h2 class=\"page-title\">".__('Shop','themedy')."</h2>"; }
                elseif (is_search()) { echo "<h2 class=\"page-title\">".__('Search','themedy')."</h2>"; }
                elseif (is_archive()) { echo "<h2 class=\"page-title\">".__('Archives','themedy')."</h2>"; }
                elseif (is_404()) { echo "<h2 class=\"page-title\">".__('Page not Found','themedy')."</h2>"; }
				else { echo "<h1 class=\"entry-title page-title\">"; the_title(); echo "</h1>"; }
                ?>
                <?php
				do_action('themedy_tag');
				?>
            </div>
        </div>
	<?php } ?>
    </div>
    <?php 
}

// Standard themedy tagline
add_action('themedy_tag', 'themedy_do_tag');
function themedy_do_tag() { 
	global $post;
	$themedy_tag = get_post_meta($post->ID, 'themedy_tag', true);
	if ((is_home() || is_single()) && (themedy_get_option('blog_tag') && $themedy_tag == "")) { $themedy_tag = themedy_get_option('blog_tag'); }
	if ($themedy_tag != "") {
		echo '<p class="page-tag">'.strip_tags($themedy_tag).'</p>';
	} 
}

// WooCommerce Breadcrumb
add_filter( 'woocommerce_breadcrumb_defaults', 'jk_change_breadcrumb_home_text' );
function jk_change_breadcrumb_home_text( $defaults ) {
    // Add "You Are Here" to match Genesis breadcrumbs
	$defaults['wrap_before'] = $defaults['wrap_before'].'You are here: ';
	return $defaults;
}

// Remove Post Meta
add_action('genesis_before_entry','themedy_remove_archive_meta');
function themedy_remove_archive_meta() {
	if (!is_single())
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	if (is_single() and get_post_type() == 'portfolio') {
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 5 );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	}
}
add_filter( 'genesis_post_meta', 'themedy_post_meta_filter' );
function themedy_post_meta_filter($post_meta) {
	if ( !is_page() ) {
		$post_meta = '[post_tags sep="" before=""]';
		return $post_meta;
	}
}

// Customize Post Info
add_filter( 'genesis_post_info', 'themedy_post_info_filter' );
function themedy_post_info_filter($post_info) {
	$post_info = '<a href="'.get_permalink().'">[post_date]</a> / [post_author_link] / [post_categories before=""] / [post_comments hide_if_off="false"] [post_edit]';
	return $post_info;
}

// Code to Display Featured Image on top of the post
add_action( 'genesis_before_entry', 'featured_post_image', 1 );
function featured_post_image() {
	if (!themedy_get_option('post_images')) return;
	if ( is_singular( 'post' ) or (get_post_type() == 'portfolio' and is_single()) )
		the_post_thumbnail('themedy-post-image', array('class'	=> "post-image"));
}

// Add Read More Link to Excerpts
add_filter('excerpt_more', 'themedy_read_more_link');
add_filter( 'get_the_content_more_link', 'themedy_read_more_link' );
add_filter( 'the_content_more_link', 'themedy_read_more_link' );
function themedy_read_more_link() {
   return '... <span class="readmore"><a href="' . get_permalink() . '">'.__('Read More', 'themedy').' <span class="rarr">&rarr;</span></a></span>';
}

// Modify comments title text in comments
add_filter( 'genesis_title_comments', 'themedy_genesis_title_comments' );
function themedy_genesis_title_comments() {
	$title = '<h3>'.__('Discussion', 'themedy').'</h3>';
	return $title;
}

// Modify the author says text in comments
add_filter( 'comment_author_says_text', 'themedy_comment_author_says_text' );
function themedy_comment_author_says_text() {
	return '';
}

// Comment reply text
add_filter('comment_reply_link', 'themedy_comment_reply');
function themedy_comment_reply($content) {
	$content = str_replace('Reply', __('Reply to This Comment', 'themedy').' <span class="rarr">&rarr;</span>', $content);
	return $content;
}

// Change PREV and NEXT buttons
add_filter( 'genesis_prev_link_text', 'gt_review_prev_link_text' );
function gt_review_prev_link_text() {
        $prevlink = '<span class="larr">&larr;</span> '.__('Previous Page', 'themedy');
        return $prevlink;
}
add_filter( 'genesis_next_link_text', 'gt_review_next_link_text' );
function gt_review_next_link_text() {
        $nextlink = __('Next Page', 'themedy').' <span class="rarr">&rarr;</span>';
        return $nextlink;
}

// Add a "first" class
add_filter( 'post_class', 'themedy_class_on_first_post' );
function themedy_class_on_first_post( $classes ) {
  global $wp_query;
  if( 0 == $wp_query->current_post )
    $classes[] = 'top';
    
  return $classes;
}

// Customizes Footer Text (set in options)
if (themedy_get_option('footer')) {
	add_filter('genesis_footer_creds_text', 'custom_footer_creds_text');
	function custom_footer_creds_text($creds) {
    	$creds = do_shortcode(themedy_get_option('footer_text'));
    return $creds;
	}
}

// Remove uneeded widget areas
unregister_sidebar('header-right');

if (is_file(CHILD_DIR.'/custom/custom_functions.php')) { include(CHILD_DIR.'/custom/custom_functions.php'); } // Include Custom Functions