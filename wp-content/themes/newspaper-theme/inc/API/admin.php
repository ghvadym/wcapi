<?php

add_action('admin_menu', 'parser_markets_options_page');
function parser_markets_options_page()
{
    add_submenu_page(
        'options-general.php',
        'WC API Settings',
        'WC API',
        'manage_options',
        'wc_api_options',
        'wc_api_callback'
    );
}

function wc_api_callback()
{
    echo 'hello';
}