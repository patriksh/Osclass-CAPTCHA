<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

class AdvancedCaptcha {
    function __construct() {
        osc_add_hook('before_html', array(&$this, 'setCaptcha'));
        osc_add_hook('ajax_advcaptcha_refresh', array(&$this, 'refreshCaptcha'));
        osc_add_hook('user_register_form', array(&$this, 'registerForm'));
    }

    function setCaptcha() {
        $page = Params::getParam('page');
        $action = (Params::getParam('action') != '') ? Params::getParam('action') : 'action';
        $pref = advcaptcha_pref('show_'.$page.'_'.$action);

        if($pref) {
            $captcha = ($pref == 'math') ? advcaptcha_generate_math() : advcaptcha_generate_text();
            $key = advcaptcha_session_key();
            Session::newInstance()->_setForm($key, $captcha);
            Session::newInstance()->_keepForm($key);

            return $captcha;
        }

        return false;
    }

    function refreshCaptcha() {
        $captcha = $this->setCaptcha();
        $answer = array_search('ans', $array);
        unset($captcha[$answer]);

        echo json_encode($captcha);
    }

    function registerForm() {
        echo '<pre>'.print_r(Session::newInstance()->_getForm(advcaptcha_session_key()), 1).'</pre>';
    }

}
$AdvancedCaptcha = new AdvancedCaptcha();

class AdvancedCaptchaAdmin {
    public function __construct() {
        osc_add_hook('init_admin', array(&$this, 'includes'));
        osc_add_hook('renderplugin_controller', array(&$this,'controller'));
        osc_add_hook('admin_menu_init', array(&$this, 'admin_menu'));

        $this->addRoutes();
    }

    function controller() {
        if (advcaptcha_is_admin()) {
            $controller = new AdvancedCaptchaController_Admin();
            $controller->doModel();
        }
    }

    function includes() {
        // Add backend JS and CSS.
        if(!advcaptcha_is_admin()) return;

        osc_add_hook('admin_header', array(&$this, 'adminHeader'));

        osc_register_script('uikit', advcaptcha_url('assets/admin/js/uikit.min.js'));
        osc_register_script('advcaptcha-admin', advcaptcha_url('assets/admin/js/main.min.js'));
        osc_enqueue_script('uikit');
        osc_enqueue_script('advcaptcha-admin');

        osc_enqueue_style('raleway', 'https://fonts.googleapis.com/css?family=Raleway&display=swap');
        osc_enqueue_style('uikit', advcaptcha_url('assets/admin/css/uikit.min.css'));
        osc_enqueue_style('advcaptcha-admin', advcaptcha_url('assets/admin/css/main.min.css'));
    }

    function addRoutes() {
        // Add backend routes.
        osc_add_route('advancedcaptcha', 'advancedcaptcha/', 'advancedcaptcha/', ADVCAPTCHA_FOLDER.'views/admin/settings.php');
        osc_add_route('advancedcaptcha-post', 'advancedcaptcha/save/', 'advancedcaptcha/save/', ADVCAPTCHA_FOLDER.'views/admin/settings.php');
    }

    function adminHeader() {
        // Add custom admin header.
        osc_remove_hook('admin_page_header', 'customPageHeader');
        osc_add_hook('admin_page_header', array(&$this,'pageHeader'), 9);
    }

    function admin_menu() {
        // Add admin submenu (under Plugins menu).
        osc_add_admin_submenu_divider('plugins', __('Advanced CAPTCHA', advcaptcha_plugin()), 'advcaptcha_divider', 'moderator');
        osc_add_admin_submenu_page('plugins', __('Settings', advcaptcha_plugin()), osc_route_admin_url('advancedcaptcha'), 'advancedcaptcha', 'moderator');
    }

    function pageHeader() {
        // Add custom admin header - include.
        include(ADVCAPTCHA_PATH.'views/admin/header.php');
    }
}
$AdvancedCaptchaAdmin = new AdvancedCaptchaAdmin();
