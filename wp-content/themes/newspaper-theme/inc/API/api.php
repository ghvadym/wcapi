<?php

const API_NAME = 'share-products/v1';

add_action('rest_api_init', 'product_control_init');

function product_control_init()
{
    register_rest_route(API_NAME, 'user', [
        'methods'  => 'POST',
        'callback' => 'user_add_call',
        'permission_callback' => '__return_true',
        'args'     => [
            'email' => [
                'type'              => 'string',
                'format'            => 'email',
                'required'          => true,
                'validate_callback' => 'is_email'
            ],
            'name'  => [
                'type'     => 'string',
                'required' => true
            ]
        ],
    ]);

    register_rest_route(API_NAME, 'products', [
        'methods'  => 'POST',
        'callback' => 'get_products',
        'permission_callback' => '__return_true'
    ]);
}

function get_products(WP_REST_Request $request)
{
    $headers = $request->get_headers();
    $token = $headers['token'];

    if (checkToken($token)) {
        return 'valid token';
    }

    return 'not valid token';
}

function checkToken($token)
{
    global $wpdb;

    return $wpdb->get_var("
        SELECT token FROM ". $wpdb->prefix . TABLE_USERS ." 
        WHERE `token` = '". $token[0] ."'"
    );
}

function user_add_call(WP_REST_Request $request)
{
    $params = $request->get_body_params();

    return requestFromUser($params) ? 'request has been send' : 'something wrong';
}

function requestFromUser($params)
{
    global $wpdb;

    return $wpdb->insert($wpdb->prefix . TABLE_USERS, [
            'email'    => $params['email'],
            'username' => $params['name'],
        ]
    );
}