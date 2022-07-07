<?php

function lc_confirme()
{
    return new WP_REST_Response('in confirmation', 200,  [
        'app_key' => get_option('laractrl_options', false) ? get_option('laractrl_options')['laractrl_field_app_key'] : '',
        'ip' => $_SERVER['SERVER_ADDR'],
        'domain' => str_replace(['https://', 'http://'], '', get_option('siteurl', $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? $_SERVER['REQUEST_URI']))
    ]);
}

add_action('rest_api_init', function () {
    register_rest_route('laractrl', 'confirme', [
        'methods' => 'GET',
        'callback' => 'lc_confirme'
    ]);
});
