<?php

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function laractrl_settings_init()
{
    // Register a new setting for "laractrl" page.
    register_setting('laractrl', 'laractrl_options');

    // Register a new section in the "laractrl" page.
    add_settings_section(
        'laractrl_section_developers',
        __('The Matrix has you.', 'laractrl'),
        'laractrl_section_developers_callback',
        'laractrl'
    );

    // Register a new field in the "laractrl_section_developers" section, inside the "laractrl" page.
    add_settings_field(
        'laractrl_field_app_key', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __('App Key', 'laractrl'),
        'laractrl_field_app_key_cb',
        'laractrl',
        'laractrl_section_developers',
        array(
            'label_for'         => 'laractrl_field_app_key',
            'class'             => 'laractrl_row',
            'laractrl_custom_data' => 'custom',
        )
    );
}

/**
 * Register our laractrl_settings_init to the admin_init action hook.
 */

if (get_option('laractrl_options', false)) {
    return false;
}else {
    add_action('admin_init', 'laractrl_settings_init');
}


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function laractrl_section_developers_callback($args)
{
?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'laractrl'); ?></p>
<?php
}

/**
 * App Key field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function laractrl_field_app_key_cb($args)
{
?>
    <input id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['laractrl_custom_data']); ?>" name="laractrl_options[<?php echo esc_attr($args['label_for']); ?>]"/>
            <p class="description">
        <?php esc_html_e('You take the blue app_key and the story ends. You wake in your bed and you believe whatever you want to believe.', 'laractrl'); ?>
    </p>
    <p class="description">
        <?php esc_html_e('You take the red app_key and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'laractrl'); ?>
    </p>
<?php
}

/**
 * Add the top level menu page.
 */
function laractrl_options_page()
{
    add_menu_page(
        'LaraCtrl Configuration',
        'LaraCtrl Configuration',
        'manage_options',
        'laractrl',
        'laractrl_options_page_html'
    );
}


/**
 * Register our laractrl_options_page to the admin_menu action hook.
 */
add_action('admin_menu', 'laractrl_options_page');


/**
 * Top level menu callback function
 */
function laractrl_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('laractrl_messages', 'laractrl_message', __('Settings Saved', 'laractrl'), 'updated');
    }

    // show error/update messages
    settings_errors('laractrl_messages');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "laractrl"
            settings_fields('laractrl');
            // output setting sections and their fields
            // (sections are registered for "laractrl", each field is registered to a specific section)
            do_settings_sections('laractrl');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}
