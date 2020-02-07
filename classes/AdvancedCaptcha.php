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
        $this->setPost();

        osc_add_hook('header', array(&$this, 'header'));
        osc_add_hook('before_html', array(&$this, 'prepareCaptcha'));
        osc_add_hook('ajax_advcaptcha_refresh', array(&$this, 'refreshCaptcha'));
    }

    function header() {
        $key = advcaptcha_pref('recaptcha_site_key');
        if($key != '') { ?>
            <script src="https://www.google.com/recaptcha/api.js?render=<?php echo osc_esc_html($key); ?>"></script>
        <?php }
    }

    function setForm() {
        foreach($this->positionsEnabled as $id => $pos) {
            osc_add_hook($pos['hook_show'], array(&$this, 'showForm'));
        }
    }

    function setPost() {
        foreach($this->positionsEnabled as $id => $pos) {
            osc_add_hook($pos['hook_post'], array(&$this, 'verifyCaptcha'));
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
            case 'qna':
                $problem = advcaptcha_generate_qna();
            break;
            default:
                $problem = null;
            break;
        }

        return $problem;
    }

    function verifyProblem($captcha) {
        $type = $captcha['type'];
        $problem = $captcha['problem'];
        $answer = osc_esc_html(Params::getParam('advcaptcha'));

        switch($type) {
            case 'google':
                $solved = advcaptcha_verify_google(osc_esc_html(Params::getParam('response')));
            break;
            case 'math':
                $solved = advcaptcha_verify_math($problem, $answer);
            break;
            case 'text':
                $solved = advcaptcha_verify_text($problem, $answer);
            break;
            case 'qna':
                $solved = advcaptcha_verify_qna($problem, $answer);
            break;
            default:
                $solved = false;
            break;
        }
    }

    function verifyCaptcha() {
        $key = osc_esc_html(Params::getParam('advcaptcha_session'));
        $captcha = Session::newInstance()->_getForm($key);
        if($captcha == '') {
            return;
        }

        $solved = $this->verifyProblem($captcha);

        if(!$solved) {
            $captcha_info = $this->positionsEnabled[$captcha['type']];
            $redirect = $captcha_info['redirect'];

            switch($captcha_info['hook_show']) {
                case 'before_user_register': // Register post.
                    Session::newInstance()->_setForm('user_s_name', trim(Params::getParam('s_name')));
                    Session::newInstance()->_setForm('user_s_email', Params::getParam('s_email'));
                    Session::newInstance()->_setForm('user_s_username', osc_sanitize_username(Params::getParam('s_username')));
                    $phone = (Params::getParam('s_phone_mobile')) ? trim(Params::getParam('s_phone_mobile')) : trim(Params::getParam('s_phone_land'));
                    Session::newInstance()->_setForm('user_s_phone', $phone);
                break;
                case 'init_login': // Recover password post.
                    if(Params::getParam('action') != 'recover_post') { return; }
                break;
                case 'init_contact': // Contact form post.
                    if(Params::getParam('action') != 'contact_post') { return; }
                    Session::newInstance()->_setForm('yourName', Params::getParam('yourName'));
                    Session::newInstance()->_setForm('yourEmail', Params::getParam('yourEmail'));
                    Session::newInstance()->_setForm('subject', Params::getParam('subject'));
                    Session::newInstance()->_setForm('message_body', Params::getParam('message'));
                break;
                case 'item_edit': // Item edit post.
                    $redirect = osc_item_edit_url(Params::getParam('secret'), Params::getParam('id'));
                break;
                case 'pre_item_add_comment_post': // Add comment post.
                    $redirect = osc_base_url(1).'?page=item&id='.Params::getParam('id');
                break;
            }

            osc_add_flash_error_message(__('CAPTCHA not solved correctly. Please try again.', advcaptcha_plugin()));
            osc_redirect_to($redirect);
        }
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

        osc_register_script('uikit', advcaptcha_url('assets/admin/js/uikit.min.js'), array('jquery'));
        osc_register_script('advcaptcha-admin', advcaptcha_url('assets/admin/js/main.min.js'), array('uikit'));
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
