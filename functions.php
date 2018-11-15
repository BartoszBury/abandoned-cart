<?php

function abandoned_cart_load_text_domain() {
	load_plugin_textdomain( 'abandoned-cart', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'abandoned_cart_load_text_domain' );



if (is_admin()) {
    require_once 'admin/admin-panel.php';
    function add_enqueue_scripts()
    {
        wp_enqueue_style('abandoned-cart', plugin_dir_url(__FILE__) . 'admin/style.css', 'Abandoned css');
    }

    add_action('admin_enqueue_scripts', 'add_enqueue_scripts');
}


add_filter('woocommerce_locate_template', 'abandoned_cart_woocommerce_locate_template', 10, 3);
function abandoned_cart_woocommerce_locate_template($template, $template_name, $template_path)
{
    global $woocommerce, $abandoned_cart;

    $_template = $template;

    if (!$template_path) $template_path = $woocommerce->template_url;

    $plugin_path = $abandoned_cart->plugin_dir_path() . '/woocommerce/';

    // Look within passed path within the theme - this is priority
    $template = locate_template(

        array(
            $template_path . $template_name,
            $template_name
        )
    );

    // Modification: Get the template from this plugin, if it exists
    if (!$template && file_exists($plugin_path . $template_name))
        $template = $plugin_path . $template_name;

    // Use default template
    if (!$template)
        $template = $_template;

    // Return what we found
    return $template;
}