<a href="#" class="dropdown-back" data-toggle="dropdown">
  <i class="fa fa-shopping-cart" aria-hidden="true"></i>
  <div class="basket-item-count" style="display: inline;">
    <span class="cart-items-count count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
  </div>
</a>
<ul class="dropdown-menu dropdown-menu-mini-cart">
  <li>
    <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
  </li>
</ul>