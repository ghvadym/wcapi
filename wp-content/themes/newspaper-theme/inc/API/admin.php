<?php

add_action('admin_menu', 'parser_markets_options_page');
function parser_markets_options_page()
{
    $hook = add_submenu_page(
        'options-general.php',
        'WC API Settings',
        'WC API',
        'manage_options',
        'wc_api_options',
        'example_table_page'
    );

    add_action( "load-$hook", 'example_table_page_load' );
}

//function wc_api_callback()
//{
//    echo 'hello';
//}

// WP 5.4.2. Cохранение опции экрана per_page. Нужно вызывать до события 'admin_menu'
add_filter( 'set_screen_option_'.'lisense_table_per_page', function( $status, $option, $value ){
    return (int) $value;
}, 10, 3 );

function example_table_page_load(){
    require_once('wp_list_table.php');
    // создаем экземпляр и сохраним его дальше выведем
    $GLOBALS['Example_List_Table'] = new Example_List_Table();
}

function example_table_page(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <?php
        // выводим таблицу на экран где нужно
        echo '<form action="" method="POST">';
        $GLOBALS['Example_List_Table']->display();
        echo '</form>';
        ?>

    </div>
    <?php
}