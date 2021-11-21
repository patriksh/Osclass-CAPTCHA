<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

class AdvCAPTCHA_Admin {
    private $routes;

    public function __construct() {
        $this->registerRoutes();

        osc_add_hook('renderplugin_controller', [$this, 'controller']);
        osc_add_hook('init_admin', [$this, 'includes']);
        osc_add_hook('admin_menu_init', [$this, 'menu']);
    }

    public function registerRoutes() {
        $routes = [
            'advcaptcha-settings' => ['url' => 'advcaptcha/settings/', 'file' => 'settings.php'],
            'advcaptcha-settings-post' => ['url' => 'advcaptcha/settings-post/', 'file' => 'settings.php'],
        ];

        $this->routes = array_keys($routes);

        foreach($routes as $name => $route) {
            osc_add_route($name, $route['url'], $route['url'], ADVCAPTCHA_FOLDER . '/views/admin/' . $route['file']);
        }
    }

    public function controller() {
        if(Params::getParam('route') == 'advcaptcha-settings-post') {
            AdvCAPTCHA_Helper::setPreference('recaptcha_site_key', Params::getParam('recaptcha_site_key'));
            AdvCAPTCHA_Helper::setPreference('recaptcha_secret_key', Params::getParam('recaptcha_secret_key'));
            AdvCAPTCHA_Helper::setPreference('recaptcha_threshold', Params::getParam('recaptcha_threshold'));
            AdvCAPTCHA_Helper::setPreference('hcaptcha_site_key', Params::getParam('hcaptcha_site_key'));
            AdvCAPTCHA_Helper::setPreference('hcaptcha_secret_key', Params::getParam('hcaptcha_secret_key'));

            $qna = [];
            foreach(Params::getParam('qna') as $value) {
                $qna[$value['question']] = $value['answer'];
            }
            AdvCAPTCHA_Helper::setPreference('questions', json_encode($qna));

            foreach(AdvCAPTCHA_Positions::all() as $id => $position) {
                AdvCAPTCHA_Helper::setPreference('show_' . $id, Params::getParam('show_' . $id));
            }

            osc_add_flash_ok_message(__('Settings updated successfully.', ADVCAPTCHA_FOLDER), 'admin');
            osc_redirect_to(osc_route_admin_url('advcaptcha-settings'));
        }
    }

    public function includes() {
        if(!in_array(Params::getParam('route'), $this->routes)) return;
        
        osc_register_script('uikit', AdvCAPTCHA_Helper::assetUrl('uikit.min.js', true), ['jquery']);
        osc_enqueue_script('uikit');
        osc_register_script('advcaptcha-admin', AdvCAPTCHA_Helper::assetUrl('main.js', true), ['uikit']);
        osc_enqueue_script('advcaptcha-admin');
        
        osc_enqueue_style('raleway', 'https://fonts.googleapis.com/css?family=Raleway&display=swap');
        osc_enqueue_style('uikit', AdvCAPTCHA_Helper::assetUrl('uikit.min.css', true));
        osc_enqueue_style('advcaptcha-admin', AdvCAPTCHA_Helper::assetUrl('main.css', true));

        osc_add_hook('admin_header', function() {
            osc_remove_hook('admin_page_header', 'customPageHeader');
            osc_add_hook('admin_page_header', function() {
                include ADVCAPTCHA_PATH . 'views/admin/header.php';
            }, 9);
        });
    }

    public function menu() {
        osc_add_admin_submenu_page('plugins', __('CAPTCHA', ADVCAPTCHA_FOLDER), osc_route_admin_url('advcaptcha-settings'), 'advcaptcha', 'admin');
    }
}

if(OC_ADMIN)
    new AdvCAPTCHA_Admin();