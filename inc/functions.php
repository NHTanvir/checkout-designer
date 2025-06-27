<?php
use Codexpert\CheckoutDesigner\Helper;
if( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Gets the site's base URL
 * 
 * @uses get_bloginfo()
 * 
 * @return string $url the site URL
 */
if( ! function_exists( 'cd_site_url' ) ) :
function cd_site_url() {
	$url = get_bloginfo( 'url' );

	return $url;
}
endif;
add_action('woocommerce_checkout_before_order_review', 'custom_checkout_columns_start');
add_action('woocommerce_checkout_after_order_review', 'custom_checkout_columns_end');
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10); 
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10); 


//Rearrange column move our tbale to first position left side data
function custom_checkout_columns_start() {
return;
    $selected_payment_method = WC()->session->get('chosen_payment_method');
    $addon_product_id   = Helper::get_option( 'checkout-designer_basic',  'addon_product');
    $addon_product      = wc_get_product( $addon_product_id );

	if( ! $addon_product ) return;

    echo '<div class="checkout-columns">';
    echo '<div class="checkout-left">';
    echo '<h3>Varukorg</h3>';
    echo "<div class='table-wrapper'>";
        echo '<table class="product-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Produkt</th>';
            echo '<th>Quantity</th>';
            echo '<th>Pris i SEK</th>';
           
            echo "<th></th>";
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product           = $cart_item['data'];
                $variation_id       = $cart_item['variation_id']; 
                $variation_product  = wc_get_product($variation_id); 
				if( ! $variation_product ) continue;
                $price              = $variation_product->get_price();
                $product_name       = $_product->get_name();
                $product_id         = $cart_item['product_id'];
                $variation_data     = $_product->get_variation_attributes();
                $variation_name     = reset($variation_data);
                if( $addon_product_id == $product_id ) {
                    if (preg_match('/(\d+)\s*(dagar|månader)/', $variation_name, $matches)) {
                        $duration   = (int) $matches[1]; 
                        $unit       = $matches[2]; 
                        
                        if ($unit == 'dagar') {
                            $months = ceil($duration / 30);
                            $variation_name = preg_replace('/(\d+)\s*dagar/', $months . 'm +ansl', $variation_name);
                        } elseif ($unit == 'månader') {
                            $variation_name = preg_replace('/(\d+)\s*månader/', $duration . 'm +ansl', $variation_name);
                        }
                    }
                }

                echo '<tr>';
                echo '<td>' . $variation_name . '</td>';
                echo '<td>';
                    echo '<div class="quantity">';
                        echo '<input type="number" class="qty-input" name="cart[' . $cart_item_key . '][qty]" value="' . $cart_item['quantity'] . '" min="1">';
                    echo '</div>';
                echo '</td>';
                echo '<td>' . wc_price( $price ) . '</td>';
                echo '<td>';
                    echo "<button type='button' class='remove-cart' data-cart-item-key='{$cart_item_key}'>";
                        echo '<img src="https://iptvutanbox.com/wp-content/uploads/2024/08/Group-63.svg">';
                    echo '</button>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
        echo '</table>';
    echo '</div>';
    echo '<div class="addons-section">';
    echo "<div class='addons-head'>";
        echo '<h6 class="method">'. $addon_product->get_title() .'</h6>';
        echo "<p>";
        echo "<img src='https://iptvutanbox.com/wp-content/uploads/2024/08/info-1.svg'>";
        echo 'Du kan lägga till hur många extra konton du vill.</p>';
    echo "</div>";
    
    echo "<div class='addons-body'>";
    $product_in_cart            = false;
    $matching_variation_name    = '';
    $main_product               = get_option('main_product');
    
    // Check if the main product is in the cart
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $main_product) {
            $product_in_cart = true;
            $variation_obj = wc_get_product($cart_item['variation_id']);
            $attributes = $variation_obj->get_attributes();
            $matching_variation_name = implode(', ', array_values($attributes)); 
            break; 
        }
    }
    
    $available_variations = $addon_product->get_available_variations();
    
    if (!empty($available_variations)) {
        echo '<select name="addon_option" class="addon-option-select">';
            echo '<option value="nytt-konto" selected>Nytt konto</option>';
            echo '<option value="förnyelse">Förnyelse</option>';
        echo '</select>';
    
        echo '<select name="addon_variation" class="addon-variation-select">';
	    
        if ($product_in_cart) {

            foreach ($available_variations as $variation) {
                $variation_obj = wc_get_product($variation['variation_id']);
                $attributes = $variation_obj->get_attributes();
                $variation_name = implode(', ', array_values($attributes));
                echo '<option value="' . esc_attr($variation['variation_id']) . '">' . esc_html($variation_name) . '</option>';
            }
        } 
        
        echo '</select>';
        
        echo "<input type='text' name='addon_mac_address' class='addon-mac-address red' placeholder='Användarnamn eller MAC-adress' style='display: none;'>";
        echo '<button type="button" class="button add-addon-to-cart" data-product_id="' . esc_attr($addon_product->get_id()) . '">Lägg till</button>';
    }
    
    
    echo "</div>";
    echo '</div>';
    
    echo '<br/><br/>';
    if( $selected_payment_method ) {
        $cyrpto_check = get_option( "{$selected_payment_method}_crypto_check" );
        if ($cyrpto_check === 'yes') {
            echo '<div class="total-section">';
                echo '<table class="totals-table">';
                echo '<tbody>';
                echo '<tr><td>Pris | SEK</td><td>' . do_shortcode('[total-price-sek]') . '</td></tr>';
                echo '</tbody>';
                echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="total-section">';
                echo '<table class="totals-table">';
                echo '<tbody>';
                echo '<tr><td>Pris | SEK</td><td>' . do_shortcode('[total-price-sek]') . '</td></tr>';
                echo '<tr><td>Kortavgift - 10%</td><td>' . do_shortcode('[total-fee-sek]') . '</td></tr>'; 
                echo '<tr><td>Totalt</td><td>' . do_shortcode('[total-price-eur]') . '</td></tr>';
                echo '</tbody>';
                echo '</table>';
            echo '</div>';
        }
    }
    else{
        echo '<div class="total-section">';
        echo '<table class="totals-table">';
        echo '<tbody>';
        echo '<tr><td>Pris | SEK</td><td>' . do_shortcode('[total-price-sek]') . '</td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
    // custom_coupon_form();   

    echo '</div>'; 
}

//Rearrange column move paymentr method to the right
function custom_checkout_columns_end() {
    ?>
    <div class="checkout-right">

        <h3>Betalning</h3>
        <div class="payment-methods-section">
            <h6 class="method">Metod</h6>
    <?php

    if (function_exists('woocommerce_checkout_payment')) {
        // woocommerce_checkout_payment();
    }

    echo '</div>';
    echo '</div>';

}