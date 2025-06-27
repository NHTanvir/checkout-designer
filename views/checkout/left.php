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
						<th><?php esc_html_e( 'Quantity', 'checkout-designer' ); ?></th>
						<th><?php esc_html_e( 'Pris i SEK', 'checkout-designer' ); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
						$_product     = $cart_item['data'];
						$price        = $_product->get_price();
						$product_name = $_product->get_name();
						$product_id   = $cart_item['product_id'];
					?>
						<tr>
							<td><?php echo esc_html( $product_name ); ?></td>
							<td>
								<div class="quantity">
									<input type="number" class="qty-input" name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="1">
								</div>
							</td>
							<td><?php echo wc_price( $price ); ?></td>
							<td>
								<button type="button" class="remove-cart" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
									<img src="<?php echo esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/08/Group-63.svg' ); ?>" alt="">
								</button>
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
					<img src="<?php echo esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/08/info-1.svg' ); ?>" alt="">
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
					<button type="button" class="button add-addon-to-cart" data-product_id="0"><?php esc_html_e( 'Lägg till', 'checkout-designer' ); ?></button>
				<?php endif; ?>
			</div>
		</div>

		<br/><br/>

		<?php if ( $selected_payment_method ) :
			$cyrpto_check = get_option( "{$selected_payment_method}_crypto_check" );
			if ( $cyrpto_check === 'yes' ) : ?>
				<div class="total-section">
					<table class="totals-table">
						<tbody>
							<tr><td><?php esc_html_e( 'Pris | SEK', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-sek]' ); ?></td></tr>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<div class="total-section">
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
				<table class="totals-table">
					<tbody>
						<tr><td><?php esc_html_e( 'Pris | SEK', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-sek]' ); ?></td></tr>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php // custom_coupon_form(); ?>
</div>
