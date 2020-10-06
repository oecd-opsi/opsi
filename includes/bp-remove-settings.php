<?php

class BP_Remove_Settings
{
    function __construct()
    {
        add_action('bp_actions', [$this, 'change_profile_settings_default_tab']);
        add_action('wp_before_admin_bar_render', [$this, 'remove_general_item_on_usermenu']);
        add_action('bp_actions', [$this, 'remove_profile_settings_general_tab']);
    }

    function change_profile_settings_default_tab()
    {

        if (bp_is_active('xprofile')) {

            $access = bp_core_can_edit_settings();
            $slug = bp_get_settings_slug();

            $args = array(
                'parent_slug' => $slug,
                'subnav_slug' => 'notifications',
                'screen_function' => 'bp_settings_screen_notification',
                'user_has_access' => $access
            );

            bp_core_new_nav_default($args);

        }
    }

    function remove_general_item_on_usermenu()
    {
        global $wp_admin_bar;

        if (bp_use_wp_admin_bar()) {
            $wp_admin_bar->remove_node('my-account-settings-general');
        }

    }

    function remove_profile_settings_general_tab()
    {

        if (bp_is_active('xprofile')) {
            bp_core_remove_subnav_item('settings', 'general');
        }
    }
}

new BP_Remove_Settings();
