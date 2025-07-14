<?php
use Codexpert\CheckoutDesigner\Helper;
?>
<div class="checkout-right">
    <h3>
        <?php
        $checkout = WC()->checkout();

        woocommerce_form_field( 'cd_name', array(
            'type'        => 'text',
            'label'       => __( 'Name', 'checkout-designer' ),
            'required'    => true,
            'class'       => array( 'form-row-wide' ),
        ), $checkout->get_value( 'cd_name' ) );


        woocommerce_form_field( 'cd_phone', array(
            'type'        => 'tel',
            'label'       => __( 'Phone', 'checkout-designer' ),
            'required'    => false,
            'class'       => array( 'form-row-wide' ),
        ), $checkout->get_value( 'cd_phone' ) );


        woocommerce_form_field( 'cd_email', array(
            'type'        => 'email',
            'label'       => __( 'Email', 'checkout-designer' ),
            'required'    => true,
            'class'       => array( 'form-row-wide' ),
        ), $checkout->get_value( 'cd_email' ) );

        woocommerce_form_field( 'cd_mac', array(
            'type'        => 'text',
            'label'       => __( 'MAC‑address', 'checkout-designer' ),
            'required'    => false,
            'class'       => array( 'form-row-wide' ),
            'placeholder' => '00:1A:2B:3C:4D:5E',
            'description' => __( 'Only use this field if you have a Formuler, TVIP, MAG or Smart STP app. Only accepts MAC that start with 10:27, 00:1A or 00:1E.', 'checkout-designer' ),
        ), $checkout->get_value( 'cd_mac' ) );


        woocommerce_form_field( 'cd_adult', array(
            'type'        => 'select',
            'label'       => __( 'Adult content', 'checkout-designer' ),
            'required'    => true,
            'class'       => array( 'form-row-wide' ),
            'options'     => array(
                ''    => __( '– Please choose –', 'checkout-designer' ),
                'yes' => __( 'Yes',             'checkout-designer' ),
                'no'  => __( 'No',              'checkout-designer' ),
            ),
        ), $checkout->get_value( 'cd_adult' ) );
        
        $payment_heading = Helper::get_option( 'checkout-designer_basic', 'payment_heading', 'Betalning' );
        do_action( 'wpml_register_single_string', 'checkout-designer', 'payment_heading', $payment_heading );
        echo esc_html( apply_filters( 'wpml_translate_single_string', $payment_heading, 'checkout-designer', 'payment_heading' ) );
        ?>
    </h3>
    <div class="payment-methods-section">
        <h6 class="method">
            <?php
            $method_label = Helper::get_option( 'checkout-designer_basic', 'method_label', 'Metod' );
            do_action( 'wpml_register_single_string', 'checkout-designer', 'method_label', $method_label );
            echo esc_html( apply_filters( 'wpml_translate_single_string', $method_label, 'checkout-designer', 'method_label' ) );
            ?>
        </h6>

        <?php
        if ( function_exists( 'woocommerce_checkout_payment' ) ) {
            woocommerce_checkout_payment();
        }
        ?>
