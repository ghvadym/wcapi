<?php

const TABLE_USERS = 'api_users';
const TABLE_PRODUCTS_META = 'api_product_meta';

createTables();

function createTables()
{
    global $wpdb;
    $tableUsers = $wpdb->prefix . TABLE_USERS;
    $tableProductsMeta = $wpdb->prefix . TABLE_PRODUCTS_META;

    if (tableNotExists($tableUsers)) {
        userTable();
    }

    if (tableNotExists($tableProductsMeta)) {
        productMetaTable();
    }
}

function userTable()
{
    if (!defined('TABLE_USERS')) {
        return;
    }

    global $wpdb;
    $table = $wpdb->prefix . TABLE_USERS;

    $query =
    "CREATE TABLE IF NOT EXISTS " . $table . " (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `email` varchar(255) NOT NULL,
        `username` varchar(255),
        `token` varchar(255)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($query);
}

function productMetaTable()
{
    if (!defined('TABLE_PRODUCTS_META')) {
        return;
    }

    global $wpdb;
    $table = $wpdb->prefix . TABLE_PRODUCTS_META;

    $query =
        "CREATE TABLE IF NOT EXISTS " . $table . " (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `product_id` BIGINT(20) UNSIGNED NOT NULL,
        `user_id` BIGINT(20) UNSIGNED NOT NULL,
        FOREIGN KEY (user_id) REFERENCES wp_api_users(id) ON DELETE CASCADE
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($query);
}

function tableNotExists($table = '')
{
    if (empty($table)) {
        return false;
    }

    global $wpdb;
    return !$wpdb->get_var("SHOW TABLES LIKE '" . $table . "'");
}