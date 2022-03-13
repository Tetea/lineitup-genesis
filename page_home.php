<?php
/**
 * Template Name: Homepage
 */

// Add homepage body classes
add_filter('body_class', 'add_homepage_class');
function add_homepage_class($classes) {
	$classes[] = 'homepage-template';
	return $classes;
}

// Force layout to 'content-sidebar'
add_filter('genesis_pre_get_option_site_layout', 'themedy_home_layout');
function themedy_home_layout($layout) {
    $layout = 'full-width-content';
    return $layout;
}

// Load scripts for slider
add_action('get_header', 'lineitup_load_scripts');
function lineitup_load_scripts() {
	if (themedy_get_option('slider')) {
    	wp_enqueue_script('FlexSlider', CHILD_THEME_LIB_URL.'/js/jquery.flexslider-min.js', array('jquery'), '2.4.0', TRUE);
		wp_enqueue_style('FlexSlider', CHILD_THEME_LIB_URL.'/css/flexslider.css','','2.4.0');
	}
}

// Add slider options (if we should)
add_action('genesis_after', 'lineitup_slider_options');
function lineitup_slider_options() { 
	if (themedy_get_option('slider')) { ?>
		<script type="text/javascript">
			jQuery(window).load(function() {
				jQuery(".flexslider").flexslider({
						animation: "<?php themedy_option('slider_effect'); ?>",
						slideshowSpeed: <?php themedy_option('slider_pause'); ?>, 
						animationSpeed: <?php themedy_option('slider_speed'); ?>,
						smoothHeight: <?php if (themedy_get_option('slider_height') == 1) { echo 'true'; } else { echo 'false'; } ?>,
						directionNav: false,
						manualControls: ".featured-area-nav .tab",
						slideshow: <?php if (themedy_get_option('slider_pause') > 0) { echo 'true'; } else { echo 'false'; } ?>
				});
			});
		 </script>
	<?php }
}

// Add the Featured Area
add_action('genesis_after_header', 'themedy_featured_area', 1);
function themedy_featured_area() {
	?>
    <div class="page-area featured-area">
        <div class="tabs">
        	<?php 
			if (themedy_get_option('slider')) {
				?>
                <div class="featured-slider">
                	<div class="flexslider">
                        <ul class="slides">
                        <?php 
                        query_posts(array('posts_per_page' => 4, 'post_type' => 'slide')); 
                        if ( have_posts() ) : while ( have_posts() ) : the_post();
                            ?>
                            <li class="slide">
                                <div class="featured-content clearfix entry-content">
                                	<div class="wrap">
                                    <?php the_content(); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; else: endif; wp_reset_query(); ?>
                        </ul>
                    </div>
                </div>
                <?php
			} else {
				the_content();	
			}
			?>
        </div>
    </div>
   <?php
}

// Add the Secondary Header
add_action('genesis_after_header', 'themedy_featured_area_pagination', 99);
function themedy_featured_area_pagination() {
	if (themedy_get_option('slider') != '') {
	?>
    <div class="featured-area-nav">
    	<div class="wrap ">                
			<?php 
			$i = 1;
			query_posts(array('posts_per_page' => 4, 'post_type' => 'slide')); 
			if ( have_posts() ) : while ( have_posts() ) : the_post();
				if (genesis_get_image()) {
					$img = genesis_get_image( array( 'format' => 'html', 'size' => 'featured-thumb', 'attr' => array( 'class' => '', 'title' => get_the_title()  ) ) );
				} else {
					$img = '<img src="'.CHILD_URL.'/images/noimage.png" alt="" />';
				}
			printf( '<div class="one-fourth #tab'.$i.' tab'.(($i==1) ? ' first' : '').'"><a href="#" title="%s">%s</a></div>', the_title_attribute('echo=0'), $img );
			$i++;
			endwhile; else: endif; wp_reset_query();
			?>
    	</div>
    </div>
   <?php
	}
}

// Remove Genesis content
//remove_action( 'genesis_loop', 'genesis_do_loop' );

// Homepage Features (content sidebar)
if (themedy_get_option('features_area_enable') != '') {
	add_action('genesis_loop', 'themedy_do_features');
}
function themedy_do_features() {
	query_posts(array('posts_per_page' => themedy_get_option('features_area_amount'), 'post_type' => 'themedy_features'));
	if ( have_posts() ) :
	$i = 1;
	echo '<section class="features section">';
	while ( have_posts() ) : the_post();
		global $post;
		echo '<div class="feature feature-'.$i.' one-third'.(($i==1) ? ' first' : '').'">';
		if (has_post_thumbnail( $post->ID )) {
			echo '<div class="feature-image">';
			the_post_thumbnail( 'themedy-feature-standard' );
			echo '</div>';
			echo '<div class="feature-content">';
		}
		echo '<h4 class="widget-title widgettitle">'.get_the_title($post->ID).'</h4>';
		the_content();
		if (has_post_thumbnail( $post->ID )) {
			echo '</div>';
		}
		echo '</div>';
		$i++;
        if ($i >= 4) { $i = 1; }
	endwhile; 
	echo '</section>';
	else: endif; wp_reset_query();
}

// Homepage Subscribe Area
if (themedy_get_option('subscribe_area_enable') != '') {
	add_action('genesis_loop', 'themedy_subscribe_area');
}
function themedy_subscribe_area() {
		?>
		<section class="widget-section section">
        	<?php if (themedy_get_option('subscribe_title') or themedy_get_option('subscribe_text')) { ?>
			<div class="featured-content entry-content">
                <h2 class="lead-title"><?php echo strip_tags(themedy_get_option('subscribe_title'), '<strong><em>'); ?></h2>
                <div class="lead-content">
                    <?php echo do_shortcode(stripslashes(wpautop(themedy_get_option('subscribe_text')))); ?>
                </div>
            </div>
            <?php } ?>
            <div class="updates">
                <?php if (themedy_get_option('subscribe_form_custom')) {
                    echo '<div class="custom-form subscribe-form">'.stripslashes(do_shortcode(themedy_get_option('subscribe_form_custom'))).'</div>';
                } elseif (themedy_get_option('subscribe_form_action') and themedy_get_option('subscribe_form_email')) { ?>
                    <form class="subscribe-form" action="<?php echo esc_url(themedy_get_option('subscribe_form_action')); ?>" method="post">
                        <input type="email" required="required" value="" placeholder="<?php echo __('Your email address', 'themedy'); ?>" class="email" id="<?php echo themedy_get_option('subscribe_form_email'); ?>" name="<?php echo themedy_get_option('subscribe_form_email'); ?>" />
                        <input type="submit" class="subscribe button btn-primary" name="email" value="<?php echo strip_tags(themedy_get_option('subscribe_button_text')); ?>" />
                    </form>
                <?php } elseif (is_user_logged_in()) {
                    echo '<p>'.__('There was an error with your form settings. <br/><a href="'.admin_url( 'admin.php?page=themedy' ).'">Please check that you have filled in all fields correctly</a>.', 'themedy').'</p>';
                } ?>
            </div>
        </section>
		<?php
}

// Homepage Testimonials
if (themedy_get_option('testimonial_area_enable') != '') {
	add_action('genesis_loop', 'themedy_testimonials');
}
function themedy_testimonials() {
	?>    
    <section class="testimonials section">
		<?php 
		if (themedy_get_option('testimonial_title') != '') {
        	echo '<h3 class="widget-title widgettitle">'.themedy_get_option('testimonial_title').'</h3>';
		}
        query_posts(array('posts_per_page' => themedy_get_option('testimonial_area_amount'), 'post_type' => 'themedy_testimonials'));
        $i = 1;
        if ( have_posts() ) : while ( have_posts() ) : the_post();
            global $post;
            $terms = get_the_terms($post->ID, 'themedy_testimonials_comp');
            $term_name = '';
            if (!empty($terms)) { 
                foreach ($terms as $term) {
                    $term_name .= $term->name;	
                    if ($term !== end($terms)) {
                        $term_name.= ', ';
                    }
                }	
            }
            if ($i == 1) {
                    echo '<blockquote class="testimonial one-third first">';
            } else {
                    echo '<blockquote class="testimonial one-third">';	
            }
            echo '<div class="testimonial-content">';
            the_content();
            echo '</div>';
            if (has_post_thumbnail( $post->ID )) {
                echo '<div class="feature-image">';
                the_post_thumbnail( 'themedy-testimonial-standard' );
                echo '</div>';
            }
            echo '<div class="testimonial-cite">';
            echo '<cite><strong>'.get_the_title($post->ID).'</strong>';
            if ($term_name != '') { 
                echo '<br/><span class="org">'.$term_name.'</span>';
            }
            echo '</cite>';
            echo '</div>';
            echo '</blockquote>';
        $i++;
        if ($i >= 4) { $i = 1; }
        endwhile; else: echo '<p>No testimonials found.</p>'; endif; wp_reset_query();
        ?>
	</section>

	<?php
}

// Rest of Genesis
genesis();