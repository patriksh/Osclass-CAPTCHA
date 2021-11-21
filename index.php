<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

/*
Plugin Name: CAPTCHA
Plugin URI: https://defected.dev
Description: Any type of CAPTCHA, any form.
Version: 2.0.0
Author: defected.dev
Author URI: https://defected.dev
Plugin update URI: null
*/

define('ADVCAPTCHA_PATH', dirname(__FILE__) . '/');
define('ADVCAPTCHA_PLUGINPATH', osc_plugin_path(__FILE__));
define('ADVCAPTCHA_FOLDER', 'dftd_captcha');
define('ADVCAPTCHA_PREF_KEY', 'plugin_captcha');
define('ADVCAPTCHA_SESSION_KEY', 'advcaptcha');

require_once ADVCAPTCHA_PATH . 'classes/Helper.php';
require_once ADVCAPTCHA_PATH . 'classes/Positions.php';
require_once ADVCAPTCHA_PATH . 'classes/Problems.php';
require_once ADVCAPTCHA_PATH . 'classes/PluginAdmin.php';
require_once ADVCAPTCHA_PATH . 'classes/Plugin.php';

osc_register_plugin(ADVCAPTCHA_PLUGINPATH, function() {
    AdvCAPTCHA_Helper::setPreference('recaptcha_site_key', '');
    AdvCAPTCHA_Helper::setPreference('recaptcha_secret_key', '');
    AdvCAPTCHA_Helper::setPreference('recaptcha_threshold', '0.5');
    AdvCAPTCHA_Helper::setPreference('questions', json_encode(['Are you a robot?' => 'No', 'Are you human?' => 'Yes']));

    foreach(AdvCAPTCHA_Positions::all() as $id => $position) {
        AdvCAPTCHA_Helper::setPreference('show_' . $id, '');
    }
});

osc_add_hook(ADVCAPTCHA_PLUGINPATH . '_uninstall', function() {
    Preference::newInstance()->delete(['s_section' => ADVCAPTCHA_PREF_KEY]);
});

osc_add_hook(ADVCAPTCHA_PLUGINPATH . '_configure', function() {
    osc_redirect_to(osc_route_admin_url('advcaptcha-settings'));
});