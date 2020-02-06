<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

class AdvancedCaptchaController_Admin extends AdminSecBaseModel {
    public $settings;

    public function __construct() {
        parent::__construct();

        $this->settings = $this->getSettings();
    }

    protected function getSettings() {
        $settings = array();
        $preferences = Preference::newInstance()->findBySection('plugin_advcaptcha');
        foreach($preferences as $pref) {
            $settings[$pref['s_name']] = $pref['s_value'];
        }

        return $settings;
    }

    public function doModel() {
        parent::doModel();
        switch(Params::getParam('route')) {
            // Settings page.
            case 'advancedcaptcha':
                View::newInstance()->_exportVariableToView('advcaptcha_preferences', $this->settings);
            break;
            // Save settings.
            case 'advancedcaptcha-post':
                osc_csrf_check();
                foreach($this->settings as $setting => $value) {
                    if($setting == 'questions') {
                        $qs = array_filter(Params::getParam('qna_q'), 'strlen');
                        $as = array_filter(Params::getParam('qna_a'), 'strlen');
                        $questions = serialize(array_map(null, $qs, $as));
                        osc_set_preference('questions', $questions, 'plugin_advcaptcha');
                    } else {
                        osc_set_preference($setting, Params::getParam($setting), 'plugin_advcaptcha');
                    }
                }

                osc_add_flash_ok_message(__('Settings updated.', advcaptcha_plugin()), 'admin');
                $this->redirectTo(osc_route_admin_url('advancedcaptcha'));
            break;
        }
    }

    function doView($file) {
        osc_run_hook('before_admin_html');
        osc_current_admin_theme_path($file);
        Session::newInstance()->_clearVariables();
        osc_run_hook('after_admin_html');
    }
}
?>
