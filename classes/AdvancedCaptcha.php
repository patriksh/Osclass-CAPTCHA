<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

class AdvancedCaptcha {
    protected $positionsEnabled;

    function __construct() {
        $this->positionsEnabled = advcaptcha_positions_enabled();
        $this->setForm();

        osc_add_hook('header', array(&$this, 'header'));
        osc_add_hook('before_html', array(&$this, 'prepareCaptcha'));
        osc_add_hook('ajax_advcaptcha_refresh', array(&$this, 'refreshCaptcha'));
    }

    function header() {
        $key = advcaptcha_pref('recaptcha_site_key');
        ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo osc_esc_html($key); ?>"></script>
        <?php
    }

    function setForm() {
        foreach($this->positionsEnabled as $id => $pos) {
            osc_add_hook($pos['hook_show'], array(&$this, 'showForm'));
        }
    }

    function showForm() {
        $captcha = Session::newInstance()->_getForm(advcaptcha_session_key());
        if(in_array($captcha['type'], advcaptcha_types())) {
            include ADVCAPTCHA_PATH.'views/web/'.$captcha['type'].'.php';
        }
    }

    function prepareCaptcha() {
        $page = (Params::getParam('page') != '') ? Params::getParam('page') : null;
        $action = (Params::getParam('action') != '') ? Params::getParam('action') : null;
        $position = array();

        foreach($this->positionsEnabled as $id => $pos) {
            if($pos['page'] == $page && $pos['action'] == $action) {
                $pos['id'] = $id;
                $position = $pos;
                break;
            }
        }

        if(count($position) == 0) {
            return;
        }

        $key = advcaptcha_session_key();
        Session::newInstance()->_setForm($key, array('type' => $position['type'], 'problem' => $this->prepareProblem($position['type'])));
        Session::newInstance()->_keepForm($key);
    }

    function prepareProblem($type) {
        switch($type) {
            case 'google':
                $problem = null;
            break;
            case 'math':
                $problem = advcaptcha_generate_math();
            break;
            case 'text':
                $problem = advcaptcha_generate_text();
            break;
            case 'question':
                $problem = null;
            break;
            default:
                $problem = null;
            break;
        }

        return $problem;
    }

    function refreshCaptcha() {
        $type = osc_esc_html(Params::getParam('type'));
        unset($captcha[array_search('ans', $captcha)]);

        echo json_encode($captcha);
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

if(OC_ADMIN) {
    $AdvancedCaptchaAdmin = new AdvancedCaptchaAdmin();
}
