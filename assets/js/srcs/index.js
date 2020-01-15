// import uikit
import UIkit from 'uikit';
import Icons from 'uikit/dist/js/uikit-icons';

// loads the Icon plugin
UIkit.use(Icons);

// The following line makes it finally work:
window.UIkit = UIkit;

// load infinite scroll
var InfiniteScroll = require('infinite-scroll');

// wrap all jQuery functions
jQuery(function($){
  
  var xhr;
  
  // add to cart button triggers modal when item is added to cart
  $(document).on( 'added_to_cart', function( e, fragments, cart_hash, this_button ) {
    var modal = UIkit.modal("#modal-cart");
    modal.show(); 
  });
  
  // fill cart data from product element for the added to cart modal
  $(document).on('click', '.add_to_cart_button', (function(){
    var $title = $(this).closest('.product').find('.uk-link-heading').text();
    var $image = $(this).closest('.product').find('.single-product-image-link').html();
    var $price = $(this).closest('.product').find('.price').html();
    $( ".tit" ).text( $title );
    $( ".im" ).html( $image );
    $( ".pri" ).html( $price );
  }));
  
  // single product add to cart button, making it ajax
  $( document ).on( 'click', '.single_add_to_cart_button', function(e) {
    e.preventDefault();
    
    var $thisbutton = $(this),
    $form = $thisbutton.closest('form.cart'),
    id = $thisbutton.val(),
    product_qty = $form.find('input[name=quantity]').val() || 1,
    product_id = $form.find('input[name=product_id]').val() || id,
    variation_id = $form.find('input[name=variation_id]').val() || 0;
    
    var data = {
        action: 'woocommerce_ajax_add_to_cart',
        product_id: product_id,
        product_sku: '',
        quantity: product_qty,
        variation_id: variation_id,
    };
    
    $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
    
    $.ajax({
        type: 'post',
        url: wc_add_to_cart_params.ajax_url,
        data: data,
        beforeSend: function (response) {
            $thisbutton.removeClass('added').addClass('loading');
        },
        complete: function (response) {
            $thisbutton.addClass('added').removeClass('loading');
        },
        success: function (response) {

            if (response.error & response.product_url) {
                window.location = response.product_url;
                return;
            } else {
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
            }
        },
    });
    return false;
  });
  
  // ajax loadmore, on posts currently
  $(document).on('click', '.organic_loadmore', (function(){
 
    var button = $(this),
        data = {
      'action': 'loadmore',
      'query': organic_loadmore_params.posts, // that's how we get params from wp_localize_script() function
      'page' : organic_loadmore_params.current_page
    };
 
    $.ajax({ // you can also use $.post here
      url : organic_loadmore_params.ajaxurl, // AJAX handler
      data : data,
      type : 'POST',
      beforeSend : function ( xhr ) {
        button.text('Loading...'); // change the button text, you can also add a preloader image
      },
      success : function( data ){
        if( data ) { 
          button.text( 'load more posts' );
          $( ".archive-posts" ).append(data);
          // $( ".archive-posts" ).before(data);
          organic_loadmore_params.current_page++;
 
          if ( organic_loadmore_params.current_page == organic_loadmore_params.max_page ) 
            button.remove(); // if last page, remove the button
 
          // you can also fire the "post-load" event here if you use a plugin that requires it
          // $( document.body ).trigger( 'post-load' );
        } else {
          button.remove(); // if no data, remove the button as well
        }
      }
    });
  }));
  
});