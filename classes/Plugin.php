<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

class AdvCAPTCHA {
    public $enabledPositions;

    function __construct() {
        osc_add_hook('init', [$this, 'includes']);
        osc_add_hook('before_html', [$this, 'setup']);
        osc_add_hook('ajax_advcaptcha', [$this, 'refresh']);

        $this->enabledPositions = AdvCAPTCHA_Positions::enabled();
        foreach($this->enabledPositions as $position) {
            osc_add_hook($position['hook_show'], [$this, 'form']);
            osc_add_hook($position['hook_post'], [$this, 'post']);
        }
    }

    function includes() {
        $position = $this->_position();
        if(!$position) return;

        osc_enqueue_style('advcaptcha', AdvCAPTCHA_Helper::assetUrl('main.css'));
        osc_register_script('advcaptcha', AdvCAPTCHA_Helper::assetUrl('main.js'), ['jquery']);
        osc_enqueue_script('advcaptcha');

        if($position['type'] == 'recaptcha') {
            osc_register_script('recaptchav3', 'https://www.google.com/recaptcha/api.js?render=' . AdvCAPTCHA_Helper::getPreference('recaptcha_site_key'));
            osc_enqueue_script('recaptchav3');
        } else if($position['type'] == 'hcaptcha') {
            osc_register_script('hcaptcha', 'https://js.hcaptcha.com/1/api.js');
            osc_enqueue_script('hcaptcha');
        }

        osc_add_hook('header', function() { ?>
            <script>var advcaptcha_refresh_url = '<?php echo osc_ajax_hook_url('advcaptcha'); ?>';</script>
        <?php });
    }

    function setup() {
        Session::newInstance()->_drop(ADVCAPTCHA_SESSION_KEY);

        $position = $this->_position();
        if(!$position) return;

        $captcha = [
            'name' => $position['id'],
            'type' => $position['type'],
            'problem' => AdvCAPTCHA_Problems::generate($position['type']),
        ];

        if($captcha['type'] != 'recaptcha' && $captcha['type'] != 'hcaptcha' && !$captcha['problem']) return;

        Session::newInstance()->_set(ADVCAPTCHA_SESSION_KEY, $captcha);
    }

    function refresh() {
        $captcha = Session::newInstance()->_get(ADVCAPTCHA_SESSION_KEY);
        if(!$captcha) return;

        $captcha['problem'] = AdvCAPTCHA_Problems::generate($captcha['type']);

        Session::newInstance()->_set(ADVCAPTCHA_SESSION_KEY, $captcha);

        unset($captcha['problem']['answer']);
        echo json_encode($captcha);
    }

    function form() {
        $captcha = Session::newInstance()->_get(ADVCAPTCHA_SESSION_KEY);

        if($captcha) {
            $themeFile = 'plugins/' . ADVCAPTCHA_FOLDER . '/' . $captcha['type'] . '.php';
            if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . $themeFile)) {
                osc_current_web_theme_path($themeFile);
            } else {
                include ADVCAPTCHA_PATH . 'views/web/' . $captcha['type'] . '.php';
            }
        }
    }

    function post() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $sessionCaptcha = Session::newInstance()->_get(ADVCAPTCHA_SESSION_KEY);
        if(!$sessionCaptcha) return;

        Session::newInstance()->_drop(ADVCAPTCHA_SESSION_KEY);

        if(!AdvCAPTCHA_Problems::verify($sessionCaptcha)) {
            $captcha = $this->enabledPositions[$sessionCaptcha['name']];
            $redirect = $captcha['redirect'];

            switch($captcha['hook_post']) {
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
                case 'pre_item_contact_post': // Contact post.
                    $redirect = osc_base_url(1) . '?page=item&id=' . Params::getParam('id');
                break;
            }

            osc_add_flash_error_message(__('CAPTCHA not solved correctly. Please try again.', ADVCAPTCHA_FOLDER));
            osc_redirect_to($redirect);
        }
    }

    public function _position() {
        $page = Params::getParam('page') ?: null;
        $action = Params::getParam('action') ?: null;

        foreach($this->enabledPositions as $id => $position) {
            if($position['page'] == $page && $position['action'] == $action) {
                $position['id'] = $id;
                return $position;
            }
        }

        return false;
    }
}
new AdvCAPTCHA();