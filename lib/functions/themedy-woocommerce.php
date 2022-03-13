<?php
/*----------------------------------------------------------

Description: 	WooCommerce functionality for Themedy themes
				Get the WooCommerce plugin for free at http://wordpress.org/plugins/woocommerce/

----------------------------------------------------------*/

add_theme_support( 'woocommerce' );

// Check For Shop Plugins
function themedy_active_plugin(){

	$active_plugins = get_option('active_plugins');
	$plugin_name = '';
	if ( class_exists( 'woocommerce' )) { $plugin_name = 'woocommerce'; }

	return ( $plugin_name <> '' ) ? $plugin_name : false;
}
global $themedy_active_plugin_name;
$themedy_active_plugin_name = themedy_active_plugin();

// Add Woo image sizes
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'themedy_woocommerce_image_dimensions', 1 ); #when theme is enabled
register_activation_hook( WP_PLUGIN_DIR.'/woocommerce/woocommerce.php', 'themedy_woocommerce_image_dimensions' ); #when woocommerce is activated
function themedy_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '310',	// px
		'height'	=> '310',	// px
		'crop'		=> 1 		// true
	);
 
	$single = array(
		'width' 	=> '640',	// px
		'height'	=> '640',	// px
		'crop'		=> 1 		// true
	);
 
	$thumbnail = array(
		'width' 	=> '200',	// px
		'height'	=> '200',	// px
		'crop'		=> 1 		// true
	);
 
	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}

// Product Sidebar 1
function themedy_do_product_sidebar() {
	if ( !dynamic_sidebar('primary-product-sidebar') ) {
		echo '<div class="widget widget_text"><div class="widget-wrap">';
			echo '<h4 class="widgettitle">';
				_e('Primary Product Sidebar', 'themedy');
			echo '</h4>';
			echo '<div class="textwidget"><p>';
				printf(__('This is the Primary Product Sidebar. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'themedy'), admin_url('widgets.php'));
			echo '</p></div>';
		echo '</div></div>';

	}
}

// Product Sidebar 2
function themedy_do_product_sidebar_alt() {
	if ( !dynamic_sidebar('secondary-product-sidebar') ) {
		echo '<div class="widget widget_text"><div class="widget-wrap">';
			echo '<h4 class="widgettitle">';
				_e('Secondary Product Sidebar', 'themedy');
			echo '</h4>';
			echo '<div class="textwidget"><p>';
				printf(__('This is the Secondary Product Sidebar. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'themedy'), admin_url('widgets.php'));
			echo '</p></div>';
		echo '</div></div>';

	}
}

// Register Product Sidebars
if (themedy_active_plugin() == 'woocommerce') { 
	genesis_register_sidebar(array(
		'name' => 'Primary Product Sidebar',
		'id' => 'primary-product-sidebar',
		'description' => 'This is the primary product sidebar shown on product pages.',
	));
	genesis_register_sidebar(array(
		'name' => 'Secondary Product Sidebar',
		'id' => 'secondary-product-sidebar',
		'description' => 'This is the secondary product sidebar shown on product pages.',
	));
}

// Add sidebars for woocommerce templates
add_action('wp_head', 'woocommerce_sidebars');
function woocommerce_sidebars() {
	if (themedy_active_plugin() == 'woocommerce' and is_woocommerce()) {
		add_action( 'genesis_sidebar', 'themedy_do_product_sidebar' );
		add_action( 'genesis_sidebar_alt', 'themedy_do_product_sidebar_alt' );
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
	}
}

// Cart total and viewer in header
add_action( 'init' , 'themedy_swap_cart' , 15 );
function themedy_swap_cart() {
	if (themedy_active_plugin() == 'woocommerce' and themedy_get_option('woo_cart')) {
		add_action('themedy_tag', 'themedy_cart_button');
		remove_action('themedy_tag', 'themedy_do_tag');
	}
}
function themedy_cart_button() {
	global $woocommerce;
	?>
	<div class="shop-menu">  
	<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'themedy'); ?>"><i class="icon-basket"></i><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'themedy'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
	</div>
	<?php
}

// Ajax cart button in header
if (themedy_active_plugin() == 'woocommerce' and themedy_get_option('woo_cart')) {
	add_filter('add_to_cart_fragments', 'themedy_header_add_to_cart_fragment');
}
function themedy_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;	
	ob_start();	
	?>
	<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'themedy'); ?>"><i class="icon-cart"></i></i><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'themedy'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();	
	return $fragments;	
}

// Change number of products per row in loop
add_filter('loop_shop_columns', 'themedy_woo_loop_columns');
if ( ! function_exists( 'themedy_woo_loop_columns' ) ) { 
	function themedy_woo_loop_columns() {
		return 3;
	}
}

// Add body class for woo styles to work
if ( ! class_exists( 'WC_pac' ) and themedy_active_plugin() == 'woocommerce') { /* WooCommerce Archive Customizer workaround */
	add_action( 'wp', 'woocommerce_pac_columns', 1 );
	function woocommerce_pac_columns() {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			add_filter( 'body_class', 'themedy_wc_products_class' );
			function themedy_wc_products_class($classes) {
				$classes[] 	= 'columns-3';
				return $classes;
			}
		}
	}
}

// Change amount of products shown on shop page
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 9;' ), 20 );