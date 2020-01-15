<?php
/**
 * Theme functions & bits
 *
 * @package Organic_Theme
 */

function organic_theme_setup()
{
  // theme support for title tag
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('menus');
  add_theme_support('post-formats', array(
      'gallery',
      'quote',
      'video',
      'aside',
      'image',
      'link'
  ));
  add_theme_support('align-wide');
  add_theme_support('responsive-embeds');
  add_theme_support('woocommerce');

  // Switch default core markup for search form, comment form, and comments to output valid HTML5.
  add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption'
  ));

  // Add support for core custom logo.
  add_theme_support('custom-logo', array(
      'height' => 30,
      'width' => 261,
      'flex-width' => true,
      'flex-height' => true
  ));

  // add custom thumbs sizes.
  add_image_size('organic-theme-featured-image-archive', 800, 300, true);
  add_image_size('organic-theme-woocommerce', 600, 600, true);
  add_image_size('organic-theme-quickview', 400, 400, true);
  add_image_size('organic-theme-woo-archive-grid', 260, 260, true);
  add_image_size('organic-theme-cart-image', 80, 80, true);
  
  add_theme_support( 'woocommerce' );
  add_theme_support( 'wc-product-gallery-zoom' );
  add_theme_support( 'wc-product-gallery-lightbox' );
  add_theme_support( 'wc-product-gallery-slider' );
  
}
add_action('after_setup_theme', 'organic_theme_setup');

// theme assets
function organic_theme_enqueue_assets() {
  
  wp_enqueue_style('organic-theme-css', get_template_directory_uri() . '/assets/css/base.css');
  wp_enqueue_script('organic-theme-js', get_template_directory_uri() . '/assets/js/main/main.js', '', '', false);
  wp_enqueue_style('organic-theme-styles', get_stylesheet_uri());
  wp_localize_script( 'organic-theme-js', 'quickview', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
  wp_localize_script( 'organic-theme-js', 'organic_loadmore_params', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
    'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
    'max_page' => $wp_query->max_num_pages
  ) );
  
}
add_action('wp_enqueue_scripts', 'organic_theme_enqueue_assets'); 

function organic_loadmore_ajax_handler() {

    $context = Timber::get_context();
    $postargs = json_decode( stripslashes( $_POST['query'] ), true );
  	$postargs['paged'] = $_POST['page'] + 1; // we need next page to be loaded
  	$postargs['post_status'] = 'publish';
    $context['posts'] = Timber::get_posts( $postargs );

    $template = 'load.twig';

    Timber::render( $template, $context );

    die();

}
add_action('wp_ajax_loadmore', 'organic_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'organic_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}


// add custom url paramater key
function custom_query_vars_filter($vars) {
  $vars[] .= 'grid_list';
  return $vars;
}
add_filter( 'query_vars', 'custom_query_vars_filter' );



// add another custom url paramater key
function custom_query_vars_results_filter($vars) {
  $vars[] .= 'results';
  return $vars;
}
add_filter( 'query_vars', 'custom_query_vars_results_filter' );

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
function new_loop_shop_per_page( $cols ) {
  
  $cols = get_query_var('results');
  return $cols;
  
  var_dump($cols);
  
}

// regisers custom widget
function organic_custom_uikit_widgets_init() {
  
  register_widget("Organic_Theme_Custom_UIKIT_Widget_Class");
  
}
add_action("widgets_init", "organic_custom_uikit_widgets_init");


/**
 * Limit max menu depth in admin panel to 1 on certain menus on edit menu screen using js
 */
function main_menu_limit_depth( $hook ) {

  if ( $hook != 'nav-menus.php' ) return;

  wp_add_inline_script( 'nav-menu', 'jQuery(document).ready(function(){ var selected_menu_id = jQuery("#select-menu-to-edit option:selected").prop("value"); if ("2" === selected_menu_id ) { wpNavMenu.options.globalMaxDepth = 0; } });', 'after' );

}
add_action( 'admin_enqueue_scripts', 'main_menu_limit_depth' );




// stuff to say we need timber activated!! see TGM Plugin activation library for php
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
add_action('tgmpa_register', 'organic_theme_register_required_plugins');

function organic_theme_register_required_plugins()
{
    $plugins = array(
        array(
            'name' => 'Timber',
            'slug' => 'timber-library',
            'required' => true
        )
    );
    $config  = array(
        'id' => 'tgmpa', // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '', // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'themes.php', // Parent menu slug.
        'capability' => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true, // Show admin notices or not.
        'dismissable' => true, // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false, // Automatically activate plugins after installation or not.
        'message' => '' // Message to output right before the plugins table.
    );
    tgmpa($plugins, $config);
}
























function organic_quickview_ajax_handler() {

  global $product;
  $query = $_POST['query'];
  $context['post']    = Timber::query_post( $query );
  
wp_reset_postdata();

  Timber::render(  'quickview.twig' , $context );
  
die();
  
}
add_action('wp_ajax_quickview', 'organic_quickview_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_quickview', 'organic_quickview_ajax_handler'); // wp_ajax_nopriv_{action}


















add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
        }