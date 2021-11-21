<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

class AdvCAPTCHA_Helper {
    public static function getPreference($key) {
        return osc_get_preference($key, ADVCAPTCHA_PREF_KEY);
    }

    public static function setPreference($key, $value, $type = 'STRING') {
        return osc_set_preference($key, $value, ADVCAPTCHA_PREF_KEY, $type);
    }

    public static function assetUrl($file, $admin = false) {
        $folder = ($admin) ? 'admin' : 'web';
        return osc_base_url() . 'oc-content/plugins/' . ADVCAPTCHA_FOLDER . '/assets/' . $folder . '/' . $file;
    }

    public static function shuffleAssoc($array) {
        $keys = array_keys($array);
        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        return $new;
    }
}