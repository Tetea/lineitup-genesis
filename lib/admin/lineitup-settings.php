<?php

/**
 * This function registers the default values for Themedy theme settings
 */

function themedy_theme_settings_defaults() {
	$defaults = array( // define our defaults
		'action_color' => '#425d80',
		'slider' => 1,
		'slider_height' => 1,
		'slider_slideshow' => false,
		'slider_effect' => 'slide',
		'slider_speed' => '800',
		'slider_pause' => '0',
		'mobile_menu' => 1,
		'secondary_area' => 1,
		'woo_cart' => 1,
		'blog_title' => '',
		'blog_tag' => '',
		'subscribe_area_enable' => 1,
		'subscribe_title' => 'Never miss an update!',
		'subscribe_text' => 'You can edit this area (along with many more awesome options) by visiting the <strong><a href="'.admin_url( 'admin.php?page=themedy' ).'">Themedy Settings</a></strong> page in your WordPress admin!',
		'subscribe_form_action' => '',
		'subscribe_form_email' => '',
		'subscribe_button_text' => 'Subscribe',
		'subscribe_form_custom' => '',
		'features_area_enable' => 1,
		'features_area_amount' => 3,
		'testimonial_area_enable' => 1,
		'testimonial_area_amount' => 3,
		'testimonial_title' => 'Testimonials',
		'footer' => 1,
		'footer_text' => 'Copyright &copy;'.date('Y') . ' <a href="'. home_url() .'">' . get_bloginfo('name') . '</a> &mdash;',
	);

	return apply_filters('themedy_theme_settings_defaults', $defaults);
}

/**
 * Easy access to our available theme styles
 */
 
function themedy_styles() {
	return array('default' => __("Default", 'themedy'), 'green' => __("Green", 'themedy'), 'red' => __("Red", 'themedy'), 'pink' => __("Pink", 'themedy'), 'orange' => __("Orange", 'themedy'), 'dark' => __("Dark", 'themedy'));
}

/**
 * Extra colour picker stuff
 */

add_action('admin_menu', 'themedy_footer_script');
function themedy_footer_script() {
	global $_themedy_theme_settings_pagehook;
	add_action('load-'.$_themedy_theme_settings_pagehook, 'themedy_enqueue_color_picker');
	add_action( 'admin_footer-'.$_themedy_theme_settings_pagehook, 'themedy_color_picker_options' );
}
function themedy_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}
function themedy_color_picker_options() {
	echo '<script type="text/javascript">jQuery(document).ready(function($){$(".themedy-color").wpColorPicker();});</script>';
}

/**
 * Add our meta boxes
 */

function themedy_theme_settings_boxes() {
	global $_themedy_theme_settings_pagehook;

	if (function_exists('add_screen_option')) { add_screen_option('layout_columns', array('max' => 2, 'default' => 2) ); }

	add_meta_box('themedy-theme-settings-version', __('Information', 'themedy'), 'themedy_theme_settings_info_box', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-slider', __('Slider', 'themedy'), 'themedy_theme_settings_slider', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-frontpage', __('Front Page Options', 'themedy'), 'themedy_theme_settings_frontpage', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-appearance', __('Appearance', 'themedy'), 'themedy_theme_settings_appearance', $_themedy_theme_settings_pagehook, 'side');
	add_meta_box('themedy-theme-settings-general', __('General Options', 'themedy'), 'themedy_theme_settings_general', $_themedy_theme_settings_pagehook, 'side');
	add_meta_box('themedy_theme_settings_footer', __('Footer', 'themedy'), 'themedy_theme_settings_footer', $_themedy_theme_settings_pagehook, 'side');
}

/**
 * This next section defines functions that contain the content of the meta boxes
 */

function themedy_theme_settings_info_box() { ?>
	<p><strong><?php echo CHILD_THEME_NAME; ?></strong> by <a href="http://themedy.com">Themedy.com</a></p>
	<p><strong><?php _e('Version:', 'themedy'); ?></strong> <?php echo CHILD_THEME_VERSION; ?></p>
    <p><span class="description"><?php _e('For support, please visit <a href="http://themedy.com/forum/">http://themedy.com/forum/</a>', 'themedy'); ?></span></p>

<?php
}

function themedy_theme_settings_slider() { ?>
	<p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider_height]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider_height]" value="1" <?php checked(1, themedy_get_option('slider_height')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider_height]"><?php _e("Allow height of the slider to adjust on each slide?", 'themedy'); ?></label>
	</p>
    <hr class="div" />
    <p><?php _e("Transition Effect:", 'themedy'); ?>
	<select name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider_effect]">
		<option style="padding-right:10px;" value="slide" <?php selected('slide', themedy_get_option('slider_effect')); ?>><?php _e("Slide", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="fade" <?php selected('fade', themedy_get_option('slider_effect')); ?>><?php _e("Fade", 'themedy'); ?></option>
	</select></p>
    <p><?php _e("Slide Pause (ms):", 'themedy'); ?>
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider_pause]" value="<?php echo esc_attr( themedy_get_option('slider_pause') ); ?>" size="15" /><br/>
    <small>To enable autoplay, enter a positive slide pause delay above</small></p>
    <p><?php _e("Slide Transition Speed (ms):", 'themedy'); ?>
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider_speed]" value="<?php echo esc_attr( themedy_get_option('slider_speed') ); ?>" size="15" /></p>
<?php
}

function themedy_theme_settings_frontpage() { ?>
	<p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider]" value="1" <?php checked(1, themedy_get_option('slider')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[slider]"><?php _e("Enable the featured slider?", 'themedy'); ?></label>
	</p>
	<p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[features_area_enable]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[features_area_enable]" value="1" <?php checked(1, themedy_get_option('features_area_enable')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[features_area_enable]"><?php _e("Enable the features area?", 'themedy'); ?></label></p>
	<p><?php _e("Number of Featured Items to Display:", 'themedy'); ?>
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[features_area_amount]" value="<?php echo esc_attr( themedy_get_option('features_area_amount') ); ?>" size="5" /><br/>
    <p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_area_enable]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_area_enable]" value="1" <?php checked(1, themedy_get_option('subscribe_area_enable')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_area_enable]"><?php _e("Enable the subscribe area?", 'themedy'); ?></label></p>
    <p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[testimonial_area_enable]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[testimonial_area_enable]" value="1" <?php checked(1, themedy_get_option('testimonial_area_enable')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[testimonial_area_enable]"><?php _e("Enable the testimonial area?", 'themedy'); ?></label></p>
    <p><?php _e("Number of Testimonial Items to Display:", 'themedy'); ?>
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[testimonial_area_amount]" value="<?php echo esc_attr( themedy_get_option('testimonial_area_amount') ); ?>" size="5" /><br/>
    <hr class="div" />
    <p><?php _e("Testimonial Area Title", 'themedy'); ?>:<br />
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[testimonial_title]" value="<?php echo esc_attr( themedy_get_option('testimonial_title') ); ?>" size="47" /></p>
	<p><?php _e("Subscribe Area Title", 'themedy'); ?>:<br />
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_title]" value="<?php echo esc_attr( themedy_get_option('subscribe_title') ); ?>" size="47" /></p>
    <p><?php _e('Subscribe Area Text', 'themedy'); ?>:<br />
	<textarea name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_text]" rows="5" cols="40"><?php echo htmlspecialchars( themedy_get_option('subscribe_text') ); ?></textarea></p>
    <p><span class="description"><?php echo __("Fill out your form action URL and email input name/ID below <strong>OR</strong> input your full custom form code below (will overwrite and may need to be custom styled).", "themedy"); ?></span></p>
    <hr class="div" />
    <p><?php _e("Form Action URL", 'themedy'); ?>:<br />
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_form_action]" value="<?php echo esc_attr( themedy_get_option('subscribe_form_action') ); ?>" size="47" /></p>
    <p><?php _e("Email Input Name or ID", 'themedy'); ?>:<br />
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_form_email]" value="<?php echo esc_attr( themedy_get_option('subscribe_form_email') ); ?>" size="47" /></p>
    <p><?php _e("Submit Button Text", 'themedy'); ?>:<br />
    <input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_button_text]" value="<?php echo esc_attr( themedy_get_option('subscribe_button_text') ); ?>" size="47" /></p>
    <strong>OR</strong>
    <p><label><?php _e('Custom <code>&#60;form&#62;</code> HTML Code or contact form <code>[shortcode]</code>', 'themedy'); ?>:</label><br />
	<textarea name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[subscribe_form_custom]" rows="5" cols="40"><?php echo htmlspecialchars( themedy_get_option('subscribe_form_custom') ); ?></textarea></p>
<?php
}

function themedy_theme_settings_appearance() { ?>
	<p><?php _e("Primary Color:", 'themedy'); ?><br />
	<input type="text" value="<?php echo themedy_get_option('action_color'); ?>" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[action_color]" data-default-color="#425d80" class="themedy-color" /></p>
	<hr class="div" />
    <p><?php _e('You can change the header image <a href="themes.php?page=custom-header/">by clicking here</a>.', 'themedy'); ?></p>
<?php
}

function themedy_theme_settings_general() { ?>
	<p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[mobile_menu]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[mobile_menu]" value="1" <?php checked(1, themedy_get_option('mobile_menu')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[mobile_menu]"><?php _e("Use the <strong>jQuery Mobile Menu</strong>?", 'themedy'); ?></label></p>
    <p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[secondary_area]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[secondary_area]" value="1" <?php checked(1, themedy_get_option('secondary_area')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[secondary_area]"><?php _e("Show <strong>Title and Tag</strong> area across site?", 'themedy'); ?></label></p>
    <?php if (themedy_active_plugin() == 'woocommerce') { ?>
    <p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[woo_cart]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[woo_cart]" value="1" <?php checked(1, themedy_get_option('woo_cart')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[woo_cart]"><?php _e("Show <strong>WooCommerce</strong> shopping cart across site?", 'themedy'); ?></label></p>
    <?php } ?>
    <hr class="div" />
    <p><?php _e("Custom Blog Title:", 'themedy'); ?>
	<input type="text" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[blog_title]" value="<?php echo esc_attr( themedy_get_option('blog_title') ); ?>" size="30" /></p>
    <p><?php _e('Custom Blog Tag:', 'themedy'); ?><br />
	<textarea name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[blog_tag]" rows="3" cols="42"><?php echo esc_attr( themedy_get_option('blog_tag') ); ?></textarea></p>
<?php
}

function themedy_theme_settings_footer() { ?>
	<p><input type="checkbox" name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[footer]" id="<?php echo LINEITUP_SETTINGS_FIELD; ?>[footer]" value="1" <?php checked(1, themedy_get_option('footer')); ?> /> <label for="<?php echo LINEITUP_SETTINGS_FIELD; ?>[footer]"><?php _e("Use custom footer text?", 'themedy'); ?></label>
	</p>
	<p><?php _e('Footer text', 'themedy'); ?>:<br />
	<textarea name="<?php echo LINEITUP_SETTINGS_FIELD; ?>[footer_text]" rows="5" cols="42"><?php echo htmlspecialchars( themedy_get_option('footer_text') ); ?></textarea></p>

<?php
}