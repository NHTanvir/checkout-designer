	<?php
    use Codexpert\CheckoutDesigner\Helper;
    
    $selected_payment_method = WC()->session->get('chosen_payment_method');

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
						
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product           = $cart_item['data'];
							$price              = $_product->get_price();
							$product_name       = $_product->get_name();
							$product_id         = $cart_item['product_id'];
							echo '<tr>';
							echo '<td>' . $product_name . '</td>';
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
						echo '<h6 class="method">'. __( 'Valfritt Extra konton', 'checkout-designer' ) .'</h6>';
						echo "<p>";
						echo "<img src='https://iptvutanbox.com/wp-content/uploads/2024/08/info-1.svg'>";
						echo 'Du kan lägga till hur många extra konton du vill.</p>';
					echo "</div>";
					
					echo "<div class='addons-body'>";
						
						$all_products = Helper::get_posts( ['post_type' => 'product', 'posts_per_page' => -1] );

						if ( ! empty( $all_products ) ) {
							echo '<select name="addon_option" class="addon-option-select">';
								echo '<option value="nytt-konto" selected>Nytt konto</option>';
								echo '<option value="förnyelse">Förnyelse</option>';
							echo '</select>';
						
							echo '<select name="addon_variation" class="addon-variation-select">';
						

								foreach ( $all_products as $product_id => $name ) {
									$product        = wc_get_product( $product_id );
									$name           = $product->get_name();

									echo '<option value="' . esc_attr( $product_id ) . '">' . esc_html( $name ) . '</option>';
								}
							
							
							echo '</select>';
							
							echo "<input type='text' name='addon_mac_address' class='addon-mac-address red' placeholder='Användarnamn eller MAC-adress' style='display: none;'>";
							echo '<button type="button" class="button add-addon-to-cart" data-product_id="0">Lägg till</button>';
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