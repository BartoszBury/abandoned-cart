<?php
/**
 * Created by IntelliJ IDEA.
 * User: oem
 * Date: 11/13/18
 * Time: 12:31 PM
 */

class AbandonedCart
{
    private function get_table_name(){
        global $wpdb;
        return $wpdb->prefix.'coders_abandoned_cart';
    }

    private function get_prefix(){
        global $wpdb;
        return $wpdb->prefix;
    }

    public function get_wc_orders()
    {
        global $wpdb;

        $orders = $wpdb->get_results("
          SELECT p.id, p.post_date
          FROM {$this->get_prefix()}posts AS p
          WHERE `post_status` LIKE 'wc-pending'
          AND `post_type` LIKE 'shop_order'"
        );

        return $orders;
    }

    public function get_abandoned_orders(){
        global $wpdb;

        $orders = $wpdb->get_results("
          SELECT *
          FROM {$this->get_table_name()}
          ");

        return $orders;
    }

    public function get_orders_id()
    {
        global $wpdb;

        $abandoned_cart_orders = $wpdb->get_results("
          SELECT a.order_id
          FROM {$this->get_table_name()} AS a
        ");

        $cart_orders = array();
        foreach ($abandoned_cart_orders as $key => $order) {
            $cart_orders[] .= $order->order_id;
        }

        return $cart_orders;
    }

    public function get_orders_not_send()
    {
        global $wpdb;

        $abandoned_cart = $wpdb->get_results("
          SELECT p.id, a.order_id, a.is_send
          FROM {$this->get_prefix()}posts AS p, {$this->get_table_name()} AS a
          WHERE p.id = a.order_id
          AND `post_type` LIKE 'shop_order'
          AND `post_status` LIKE 'wc-pending'
          AND `is_send` LIKE 'no'
        ");

        return $abandoned_cart;
    }

    public function get_wc_template_email($order, $heading = false, $mailer)
    {
        $template = 'emails/pending.php';
        return wc_get_template_html($template, array(
            'order' => $order,
            'email_heading' => $heading,
            'sent_to_admin' => false,
            'plain_text' => false,
            'email' => $mailer
        ));
    }

    public function get_setting_hours(){
        return esc_attr( get_option( 'abandoned_cart_hours' ) );
    }

    public function plugin_dir_path() {

        // gets the absolute path to this plugin directory
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
}

$abandoned_cart = new AbandonedCart();

