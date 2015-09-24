<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configtext('filter_camo/host',
        get_string('host','filter_camo'),
        get_string('host_desc', 'filter_camo'),
        'https://camo.example.com'));

    $settings->add(new admin_setting_configtext('filter_camo/key',
        get_string('key','filter_camo'),
        get_string('key_desc', 'filter_camo'),
        'etooxeeNeuchopee8teecieghueghainohtheebaukahseegacheimunaefiquae'));
}
