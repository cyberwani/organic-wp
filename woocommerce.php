<?php

if ( ! class_exists( 'Timber' ) ) {
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';

    return;
}

$context            = Timber::context();
$context['sidebar'] = Timber::get_widgets( 'shop-sidebar' );


if ( is_singular( 'product' ) ) {
    $context['post']    = Timber::get_post();
    $product            = wc_get_product( $context['post']->ID );
    $context['product'] = $product;

    // Restore the context and loop back to the main query loop.
    wp_reset_postdata();

    Timber::render( 'views/woo/single-product.twig', $context );
} else {
    $posts = Timber::get_posts();
    $context['products'] = $posts;
    
    $context['grid_list'] = get_query_var('grid_list');

    if ( is_product_category() ) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $context['category'] = get_term( $term_id, 'product_cat' );
        $context['title'] = single_term_title( '', false );
    };
    
    if ( is_shop() ) {
      $context['title'] = 'Shop';
    };

    Timber::render( 'views/woo/archive.twig', $context );
}