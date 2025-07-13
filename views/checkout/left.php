<?php
use Codexpert\CheckoutDesigner\Helper;

$selected_payment_method = WC()->session->get( 'chosen_payment_method' );

$checkout_heading    = Helper::get_option( 'checkout-designer_basic', 'checkout_heading', 'Varukorg' );
$addon_heading       = Helper::get_option( 'checkout-designer_basic', 'addon_heading', 'Valfritt Extra konton' );
$extra_accounts_text = Helper::get_option( 'checkout-designer_basic', 'extra_accounts_text', 'Du kan lägga till hur många extra konton du vill.' );
$add_button_text     = Helper::get_option( 'checkout-designer_basic', 'add_button_text', 'Lägg till' );
$payment_heading     = Helper::get_option( 'checkout-designer_basic', 'payment_heading', 'Betalning' );
$method_label        = Helper::get_option( 'checkout-designer_basic', 'method_label', 'Metod' );
?>

<div class="checkout-columns">
	<div class="checkout-left">
		<h3>
			<?php
			do_action( 'wpml_register_single_string', 'checkout-designer', 'checkout_heading', $checkout_heading );
			echo apply_filters( 'wpml_translate_single_string', $checkout_heading, 'checkout-designer', 'checkout_heading' );
			?>
		</h3>

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
									<span class="qty-display"><?php echo esc_html( str_pad( $quantity, 2, '0', STR_PAD_LEFT ) ); ?></span>
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
				<h6 class="method">
					<?php
					do_action( 'wpml_register_single_string', 'checkout-designer', 'addon_heading', $addon_heading );
					echo apply_filters( 'wpml_translate_single_string', $addon_heading, 'checkout-designer', 'addon_heading' );
					?>
				</h6>
				<p>
					<img src="<?php echo esc_url( 'https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning.png' ); ?>" alt="">
					<?php
					do_action( 'wpml_register_single_string', 'checkout-designer', 'extra_accounts_text', $extra_accounts_text );
					echo apply_filters( 'wpml_translate_single_string', $extra_accounts_text, 'checkout-designer', 'extra_accounts_text' );
					?>
				</p>
			</div>

			<div class="addons-body">
				<?php
				$all_products = Helper::get_posts(
					[
						'post_type'      => 'product',
						'posts_per_page' => -1,
					]
				);

				if ( ! empty( $all_products ) ) :
					?>
					<select name="addon_option" class="addon-option-select">
						<option value="nytt-konto" selected><?php esc_html_e( 'Nytt konto', 'checkout-designer' ); ?></option>
						<option value="förnyelse"><?php esc_html_e( 'Förnyelse', 'checkout-designer' ); ?></option>
					</select>

					<select name="addon_variation" class="addon-variation-select">
						<?php foreach ( $all_products as $product_id => $name ) :
							$product = wc_get_product( $product_id );
							if ( ! $product ) {
								continue;
							}
							?>
							<option value="<?php echo esc_attr( $product_id ); ?>"><?php echo esc_html( $product->get_name() ); ?></option>
						<?php endforeach; ?>
					</select>

					<input type="text" name="addon_mac_address" class="addon-mac-address red" placeholder="<?php esc_attr_e( 'Användarnamn eller MAC-adress', 'checkout-designer' ); ?>" style="display: none;">

					<?php
					do_action( 'wpml_register_single_string', 'checkout-designer', 'add_button_text', $add_button_text );
					?>
					<button type="button" class="button add-addon-to-cart" data-product_id="0">
						<?php echo esc_html( apply_filters( 'wpml_translate_single_string', $add_button_text, 'checkout-designer', 'add_button_text' ) ); ?>
						<!-- SVG icon left out for brevity -->
					</button>
				<?php endif; ?>
			</div>
		</div>

		<br><br>

		<?php if ( $selected_payment_method ) :
			$crypto_gateway = Helper::get_option( 'checkout-designer_basic', 'crypto_gateway' );
			if ( $crypto_gateway === $selected_payment_method ) :
				?>
				<div class="total-section">
					<h4><?php esc_html_e( 'a', 'checkout-designer' ); ?></h4>
					<table class="totals-table">
						<tbody>
							<tr><td><?php esc_html_e( 'Pris | SEK', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-sek]' ); ?></td></tr>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<div class="total-section">
					<h4><?php esc_html_e( 'Fakturauppgifter', 'checkout-designer' ); ?></h4>
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
				<h4><?php esc_html_e( 'Fakturauppgifter', 'checkout-designer' ); ?></h4>
				<table class="totals-table">
					<tbody>
						<tr><td><?php esc_html_e( 'Pris | SEK', 'checkout-designer' ); ?></td><td><?php echo do_shortcode( '[total-price-sek]' ); ?></td></tr>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php custom_coupon_form(); ?>
		
</div>
