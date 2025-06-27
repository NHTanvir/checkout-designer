<?php
$selected_payment_method = WC()->session->get('chosen_payment_method');
$cyrpto_check = get_option("{$selected_payment_method}_crypto_check");

if ($cyrpto_check == 'yes') {
    $mics_style = 'display:block';
    $bit_style = 'display:none';
} else {
    $mics_style = 'display:none';
    $bit_style = 'display:block';
}
?>

<div class="blockonomics-payments-info" style="<?php echo esc_attr($mics_style); ?>">
    <h6 class="method"><?php esc_html_e('Total avgifter', 'checkout-designer'); ?></h6>
    <div class="fee-table">
        <div class="fee-title">
            <?php esc_html_e('Avgift beroende på börs:', 'checkout-designer'); ?>
        </div>
        <div class="fee-price">
            <?php esc_html_e('100-400 SEK', 'checkout-designer'); ?>
        </div>
    </div>
    <p class="blockonomics-payments-message">
        <img src="<?php echo esc_url('https://iptvutanbox.com/wp-content/uploads/2024/09/info.png'); ?>" alt="">
        <span>
            <?php 
            echo wp_kses_post('När du betalar med Krypto så skickar du valfri valuta från valfri plånbok eller från någon utav de kryptobörserna vi har guider för.<br><br><strong style="color: red;">OBS! Du ansvarar för avgifterna som plånboken/börsen du skickar ifrån tar. Skickar du ett för lågt belopp så går din beställning inte igenom!</strong>'); 
            ?>
        </span>
    </p>
    <a href="#coupon-section" class="mobile-arrow-bottom">
        <img src="<?php echo esc_url('https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-16.png'); ?>" alt="">
    </a>
</div>

<div class="blockonomics-payments-info" style="<?php echo esc_attr($bit_style); ?>">
    <h6 class="method"><?php esc_html_e('Total avgifter', 'checkout-designer'); ?></h6>
    <div class="fee-table">
        <div class="fee-title">
            <?php esc_html_e('Kortavgift', 'checkout-designer'); ?>
        </div>
        <div class="fee-price">
            10%
        </div>
    </div>
    <p class="blockonomics-payments-message">
        <img src="<?php echo esc_url('https://iptvutanbox.com/wp-content/uploads/2024/09/info-1.png'); ?>" alt="">
        <span>
            <?php 
            echo wp_kses_post('Med detta alternativ genomförs transaktionen i valutan $ (Dollar). Du köper USDC som sedan skickas till oss per automatik.<br/><br/><strong>Om detta betalningsalternativ inte fungerar för dig så kan du skapa en ny order och välja något av våra andra alternativ.</strong>'); 
            ?>
        </span>
    </p>
    <a href="#coupon-section" class="mobile-arrow-bottom">
        <img src="<?php echo esc_url('https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-16.png'); ?>" alt="">
    </a>
</div>
