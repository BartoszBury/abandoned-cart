<?php
/**
 * Created by IntelliJ IDEA.
 * User: oem
 * Date: 11/13/18
 * Time: 12:54 PM
 */

add_action('admin_menu', 'abandoned_submenu_page');

function abandoned_submenu_page()
{
    add_submenu_page('woocommerce', 'Abandoned Cart', 'Abandoned Cart', 'manage_options', 'woo-abandoned-cart', 'abandoned_callback');

    add_action('admin_init', 'abandoned_settings');
}

/* ================================== */
/* ===  Add settings to templates ====== */
/* ================================== */

function abandoned_settings()
{
    require_once 'settings/hours.php';
}

/* ================================== */
/* =========  Add templates   ======= */
/* ================================== */

function abandoned_callback()
{
    global $abandoned_cart;
    $abandoned_cart_orders = $abandoned_cart->get_abandoned_orders();
    echo '<h1> Abandoned Cart Settings </h1>';
    settings_errors(); ?>

    <form method="post" action="options.php">
        <?php settings_fields('woo-abandoned-cart-hours') ?>
        <?php do_settings_sections('woo-abandoned-cart-hours') ?>
        <?php submit_button() ?>
    </form>
    <table class="wc-abandoned-cart">
        <tr>
            <th>ID</th>
            <th>Order ID</th>
            <th>E-mail</th>
            <th>Is send</th>
        </tr>
        <?php
        foreach ($abandoned_cart_orders as $key => $value) {
            echo '<tr>';
            echo '<td>' . $value->id . '</td>';
            echo '<td>' . $value->order_id . '</td>';
            echo '<td>' . $value->e_mail . '</td>';
            echo '<td>' . $value->is_send . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
    <?php
}
