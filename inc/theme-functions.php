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
  add_image_size('organic-theme-cart-image', 80, 80, true);
  
  add_theme_support( 'woocommerce' );
  
  add_filter('woocommerce_enqueue_styles', '__return_false');
  
}
add_action('after_setup_theme', 'organic_theme_setup');

function organic_theme_enqueue_assets() {
  
  wp_enqueue_style('organic-theme-css', get_template_directory_uri() . '/assets/css/base.css');
  wp_enqueue_script('organic-theme-js', get_template_directory_uri() . '/assets/js/main/main.js', '', '', false);
  wp_enqueue_style('organic-theme-styles', get_stylesheet_uri());
  
}
add_action('wp_enqueue_scripts', 'organic_theme_enqueue_assets'); 


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
