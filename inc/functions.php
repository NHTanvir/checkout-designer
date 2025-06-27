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
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_order_review', 30 );
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 30 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 


function add_custom_payment_message() {
    $selected_payment_method = WC()->session->get('chosen_payment_method');
    $cyrpto_check               = get_option( "{$selected_payment_method}_crypto_check" );
    if( $cyrpto_check == 'yes' ) {
        $mics_style = 'display:block';
        $bit_style = 'display:none';
    } else {
        $mics_style = 'display:none';
        $bit_style = 'display:block';
    }


    echo '<div class="blockonomics-payments-info" style="'.$mics_style.'">';
        echo '<h6 class="method">Total avgifter</h6>';
        echo '<div class="fee-table">';
            echo '<div class="fee-title">';
                echo "Avgift beroende på börs:";
            echo '</div>';
            echo '<div class="fee-price">';
                echo "100-400 SEK";
            echo '</div>'; 
        echo '</div>';
        echo '<p class="blockonomics-payments-message"><img src="https://iptvutanbox.com/wp-content/uploads/2024/09/info.png"><span>När du betalar med Krypto så skickar du valfri valuta från valfri plånbok eller från någon utav de kryptobörserna vi har guider för.<br><br><strong style="color: red;">OBS! Du ansvarar för avgifterna som plånboken/börsen du skickar ifrån tar. Skickar du ett för lågt belopp så går din beställning inte igenom!</strong></span></p>';
   
    echo '<a href="#coupon-section" class="mobile-arrow-bottom"><img src="https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-16.png"></a></div>';

    // Normal Payment Message
    echo '<div class="blockonomics-payments-info" style="'.$bit_style.'">'; // Changed class name for clarity
        echo '<h6 class="method">Total avgifter</h6>';
        echo '<div class="fee-table">';
            echo '<div class="fee-title">';
                echo "Kortavgift";
            echo '</div>';
            echo '<div class="fee-price">';
            echo '10%';
            echo '</div>';
        echo '</div>';
        echo '<p class="blockonomics-payments-message"><img src="https://iptvutanbox.com/wp-content/uploads/2024/09/info-1.png"><span>Med detta alternativ genomförs transaktionen i valutan $ (Dollar). Du köper USDC som sedan skickas till oss per automatik.<br/><br/><strong>Om detta betalningsalternativ inte fungerar för dig så kan du skapa en ny order och välja något av våra andra alternativ.</strong></span></p><a href="#coupon-section" class="mobile-arrow-bottom"><img src="https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-16.png"></a>';
    echo '</div>';
}

//Render coupon field
function custom_coupon_form() {
    ?>
    <div class="coupon" id="coupon-section">
        <p class="form-row">
            <input type="text" name="coupon_code" class="input-text" placeholder="Rabattkod" id="coupon_code" value="">
            <button type="submit" class="button" name="apply_coupon" value="Apply coupon">Använd</button>
        </p>
    </div>
    <?php
}
