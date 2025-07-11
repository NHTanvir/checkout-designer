<?php
use Codexpert\CheckoutDesigner\Helper;
$selected_payment_method = WC()->session->get('chosen_payment_method');
?>

<div class="checkout-columns">
	<div class="checkout-left">
		<h3><?php esc_html_e( 'Varukorg', 'checkout-designer' ); ?></h3>
		<div class="table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Produkt', 'checkout-designer' ); ?></th>
                        <th><?php esc_html_e( 'Pris i SEK', 'checkout-designer' ); ?></th>
                        <th><?php esc_html_e( 'Antal', 'checkout-designer' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                        $_product     = $cart_item['data'];
                        $quantity     = $cart_item['quantity'];
                        $price        = $_product->get_price() * $quantity;
                        $product_name = $_product->get_name();
       
                    ?>
                        <tr>
                            <td><div class="product-title"><?php echo esc_html( $product_name ); ?></div></td>
                            <td><?php echo wc_price( $price ); ?></td>
                            <td>
                                <div class="quantity-control" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                    <button type="button" class="qty-decrease">-</button>
									<span class="qty-display">
										<?php echo esc_html( str_pad( $quantity, 2, '0', STR_PAD_LEFT ) ); ?>
									</span>
                                    <button type="button" class="qty-increase">+</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

		</div>

		<div class="addons-section">
			<div class="addons-head">
				<h6 class="method"><?php esc_html_e( 'Valfritt Extra konton', 'checkout-designer' ); ?></h6>
				<p>
					<img src="<?php echo esc_url( 'https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning.png' ); ?>" alt="">
					<?php esc_html_e( 'Du kan lägga till hur många extra konton du vill.', 'checkout-designer' ); ?>
				</p>
			</div>

			<div class="addons-body">
				<?php
				$all_products = Helper::get_posts( [ 'post_type' => 'product', 'posts_per_page' => -1 ] );
				if ( ! empty( $all_products ) ) :
				?>
					<select name="addon_option" class="addon-option-select">
						<option value="nytt-konto" selected><?php esc_html_e( 'Nytt konto', 'checkout-designer' ); ?></option>
						<option value="förnyelse"><?php esc_html_e( 'Förnyelse', 'checkout-designer' ); ?></option>
					</select>

					<select name="addon_variation" class="addon-variation-select">
						<?php foreach ( $all_products as $product_id => $name ) :
							$product = wc_get_product( $product_id );
							if ( ! $product ) continue;
						?>
							<option value="<?php echo esc_attr( $product_id ); ?>"><?php echo esc_html( $product->get_name() ); ?></option>
						<?php endforeach; ?>
					</select>

					<input type="text" name="addon_mac_address" class="addon-mac-address red" placeholder="<?php esc_attr_e( 'Användarnamn eller MAC-adress', 'checkout-designer' ); ?>" style="display: none;">
					<button type="button" class="button add-addon-to-cart" data-product_id="0"><?php esc_html_e( 'Lägg till', 'checkout-designer' ); ?><svg id="Capa_1" enable-background="new 0 0 611.802 611.802" height="14" viewBox="0 0 611.802 611.802" width="14" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev/svgjs"><g width="100%" height="100%" transform="matrix(1,0,0,1,0,0)"><g><g id="图层_x0020_1_31_"><path clip-rule="evenodd" d="m305.901 611.802c-17.973 0-32.676-14.703-32.676-32.676v-69.742-170.807h-170.807-69.742c-17.974 0-32.676-14.703-32.676-32.676 0-17.973 14.702-32.676 32.676-32.676h69.742 170.807v-170.807-69.742c0-17.974 14.702-32.676 32.676-32.676 17.973 0 32.676 14.702 32.676 32.676v69.742 170.807h170.807 69.742c17.973 0 32.676 14.702 32.676 32.676 0 17.973-14.703 32.676-32.676 32.676h-69.742-170.807v170.807 69.742c0 17.973-14.703 32.676-32.676 32.676z" fill-rule="evenodd" fill="#ffffff" fill-opacity="1" data-original-color="#000000ff" stroke="none" stroke-opacity="1"/></g></g></g></svg></button>
				<?php endif; ?>
			</div>
		</div>

		<br/><br/>

		<?php if ( $selected_payment_method ) :
		    $cyrpto_gateway       = Helper::get_option( "checkout-designer_basic", 'crypto_gateway' );
			if ( $cyrpto_gateway == $selected_payment_method ) : ?>
				<div class="total-section">
				    <h4><?php _e( 'Fakturauppgifter', 'checkout-designer') ?></h4>
					<table class="totals-table">
						<tbody>
							<tr><td><?php esc_html_e( 'Pris | SEK', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-sek]' ); ?></td></tr>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<div class="total-section">
					<h4><?php _e( 'Fakturauppgifter', 'checkout-designer') ?></h4>
					<table class="totals-table">
						<tbody>
							<tr><td><?php esc_html_e( 'Pris | SEK', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-sek]' ); ?></td></tr>
							<tr><td><?php esc_html_e( 'Kortavgift - 10%', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-fee-sek]' ); ?></td></tr>
							<tr><td><?php esc_html_e( 'Totalt', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-eur]' ); ?></td></tr>
						</tbody>
					</table>
				</div>
			<?php endif;
		else : ?>
			<div class="total-section">
				<h4><?php _e( 'Fakturauppgifter', 'checkout-designer') ?></h4>
				<table class="totals-table">
					<tbody>
						<tr><td><?php esc_html_e( 'Pris | SEK', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-sek]' ); ?></td></tr>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
  
		<?php custom_coupon_form(); ?>

</div>
