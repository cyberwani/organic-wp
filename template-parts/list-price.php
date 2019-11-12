<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price uk-margin-small-bottom" style="float: right"><?php echo $price_html; ?></span>
<?php endif; ?>
