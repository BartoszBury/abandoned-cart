<?php
/*
Plugin Name:  Send E-mail to customers
Description:  Send a reminder email about the order.
Version:      1.0.0
Text Domain:  abandoned-cart
Domain Path: /languages
Author:       Bartosz Bury
*/

require_once 'class.php';
require_once "create-table.php";
require_once "functions.php";

add_action('init', 'main_abandoned_cart');
function main_abandoned_cart()
{

    global $wpdb, $abandoned_cart;
    $table_name = $wpdb->prefix . "coders_abandoned_cart";

    $today = strtotime(date("Y-m-d H:i:s"));
    $hours_later = $abandoned_cart->get_setting_hours();
    $hours_later *= 3600;

    $wc_orders = $abandoned_cart->get_wc_orders();
    $abandoned_cart_orders_id = $abandoned_cart->get_orders_id();

    foreach ($wc_orders as $key => $order) {
        $order_id = $order->id;
        $order_date = strtotime($order->post_date);

        if ($today >= $order_date + $hours_later) {

            $emial_order = wc_get_order($order_id)->get_billing_email();

            if (!in_array($order_id, $abandoned_cart_orders_id)) {
                $wpdb->insert($table_name, array(
                    'order_id' => $order_id,
                    'e_mail' => $emial_order,
                    'is_send' => 'no',
                ));
            }
        }
    }


    $abandoned_cart_orders = $abandoned_cart->get_orders_not_send();

    foreach ($abandoned_cart_orders as $key => $order) {

        $order_id = $order->id;
        $is_send = $order->is_send;

        if ($is_send === 'no') {

            $order = wc_get_order($order_id);

            $mailer = WC()->mailer();
            $recipient = $order->get_billing_email();
            $subject = __("FN Store - Reminder about your order", 'abandoned-cart');
            $content = $abandoned_cart->get_wc_template_email($order, $subject, $mailer);
            $headers = "Content-Type: text/html\r\n";
            $mailer->send($recipient, $subject, $content, $headers);

            $wpdb->update($table_name, array(
                'is_send' => 'yes'
            ), array(
                'order_id' => $order_id
            ));
        }
    }
}