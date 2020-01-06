<?php
global $product;
$id = $product->get_id();
$context['post']    = Timber::query_post( $id );

$context['grid_list'] = get_query_var('grid_list');



Timber::render(  'quick-view.twig' , $context );