<?php
/**
 * Created by IntelliJ IDEA.
 * User: oem
 * Date: 11/9/18
 * Time: 10:34 AM
 */

global $wpdb;

$charset_collate = $wpdb->get_charset_collate();
$table_name = $wpdb->prefix.'coders_abandoned_cart';

$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id mediumint(9) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  order_id INT(32) NOT NULL,
  e_mail VARCHAR(50) NOT NULL,
  is_send tinytext NOT NULL,
  UNIQUE (order_id)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);