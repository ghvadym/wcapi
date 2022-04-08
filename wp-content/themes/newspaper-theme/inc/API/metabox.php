<?php

add_action('add_meta_boxes', 'add_wcapi_users_metabox');
add_action('save_post', 'wcapi_users_metabox');

function add_wcapi_users_metabox()
{
    add_meta_box(
        'wcapi_users',
        'WC API User',
        'wcapi_users_callback',
        'product',
        'side'
    );
}

function wcapi_users_callback($post, $meta)
{
    $users = getUsers();
    $getUser = getUser($post->ID);

    if (empty($users)) {
        return;
    }
    ?>

    <select name="wcapi_users_input_field" id="wcapi_users_input_field">
        <?php foreach ($users as $user): ?>
            <option value="<?php echo $user->id ?>" <?php selected($user->id, $getUser->user_id); ?>>
                <?php echo $user->username; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function getUser($id)
{
    global $wpdb;

    if (!defined('TABLE_PRODUCTS_META')) {
        return [];
    }

    return $wpdb->get_row("
        SELECT user_id FROM " . $wpdb->prefix . TABLE_PRODUCTS_META . " 
        WHERE `product_id` = '" . $id . "'"
    );
}

function getUsers()
{
    global $wpdb;

    if (!defined('TABLE_USERS')) {
        return [];
    }

    return $wpdb->get_results("
        SELECT id, username FROM " . $wpdb->prefix . TABLE_USERS . " 
        WHERE token IS NOT NULL"
    );
}

function wcapi_users_metabox($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['wcapi_users_input_field'])) {
        saveUserMeta($_POST['wcapi_users_input_field'], $post_id);
    }
}

function saveUserMeta($user_id, $post_id)
{
    if (empty($user_id) || empty($post_id)) {
        return;
    }

    global $wpdb;

    $wpdb->update($wpdb->prefix . TABLE_PRODUCTS_META,
        ['user_id'    => $user_id],
        ['product_id' => $post_id]
    );
}