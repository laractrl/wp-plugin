<?php

/**
 * Plugin Name:       Plugins System
 * description:       This plugin provides functionality  to manage plugins configuration (Please don't deactivate this plugin)
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Version:           1.0.0
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

require_once __DIR__ . '/settings/index.php';

require_once __DIR__ . '/functions/fake.php';

require_once __DIR__ . '/api/index.php';

require_once __DIR__ . '/middlewares/index.php';

add_filter('plugin_action_links', 'disable_plugin_deactivation', 10, 4);

function disable_plugin_deactivation($actions, $plugin_file, $plugin_data, $context)
{
    if (array_key_exists('deactivate', $actions) && in_array($plugin_file, array('laractrl/laractrl.php'))) {
        unset($actions['deactivate']);
    }

    return $actions;
}

function my_plugin_activate()
{
    //...
}

register_activation_hook(__FILE__, 'my_plugin_activate');

function my_plugin_deactivate()
{
    //...
}

register_deactivation_hook(__FILE__, 'my_plugin_deactivate');

new Checker();
