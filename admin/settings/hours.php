<?php
/**
 * Created by IntelliJ IDEA.
 * User: oem
 * Date: 11/13/18
 * Time: 1:04 PM
 */

register_setting( 'woo-abandoned-cart-hours', 'abandoned_cart_hours' );


add_settings_section( 'woo-abandoned-cart-hours', 'Setting Hours', '', 'woo-abandoned-cart-hours' );


add_settings_field( 'hours', 'Hours', 'abandoned_cart_hours', 'woo-abandoned-cart-hours', 'woo-abandoned-cart-hours' );

function abandoned_cart_hours(){
    $abandoned_cart_hours = esc_attr( get_option( 'abandoned_cart_hours' ) );
    echo '<input type="number" name="abandoned_cart_hours" value="' . $abandoned_cart_hours . '"/>';
}