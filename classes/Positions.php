<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

class AdvCAPTCHA_Positions {
    public static function all() {
        return array(
            'login' => array(
                'name' => __('Login', ADVCAPTCHA_FOLDER),
                'hook_show' => 'advcaptcha_hook_login',
                'hook_post' => 'before_validating_login',
                'page' => 'login',
                'action' => null,
                'redirect' => osc_user_login_url(),
                'file' => 'user-login.php'
            ),
            'register' => array(
                'name' => __('Register', ADVCAPTCHA_FOLDER),
                'hook_show' => 'user_register_form',
                'hook_post' => 'before_user_register',
                'page' => 'register',
                'action' => 'register',
                'redirect' => osc_register_account_url()
            ),
            'recover' => array(
                'name' => __('Forgotten password', ADVCAPTCHA_FOLDER),
                'hook_show' => 'advcaptcha_hook_recover',
                'hook_post' => 'init_login',
                'page' => 'login',
                'action' => 'recover',
                'redirect' => osc_recover_user_password_url(),
                'file' => 'user-recover.php'
            ),
            'contact' => array(
                'name' => __('Contact', ADVCAPTCHA_FOLDER),
                'hook_show' => 'contact_form',
                'hook_post' => 'init_contact',
                'page' => 'contact',
                'action' => null,
                'redirect' => osc_contact_url()
            ),
            'item_add' => array(
                'name' => __('Add an item', ADVCAPTCHA_FOLDER),
                'hook_show' => 'advcaptcha_hook_item',
                'hook_post' => 'pre_item_post',
                'page' => 'item',
                'action' => 'item_add',
                'redirect' => osc_item_post_url(),
                'file' => 'item-post.php'
            ),
            'item_edit' => array(
                'name' => __('Edit an item', ADVCAPTCHA_FOLDER),
                'hook_show' => 'advcaptcha_hook_item',
                'hook_post' => 'pre_item_post',
                'page' => 'item',
                'action' => 'item_edit',
                'redirect' => null,
                'file' => 'item-post.php'
            ),
            'item_contact' => array(
                'name' => __('Item contact', ADVCAPTCHA_FOLDER),
                'hook_show' => 'item_contact_form',
                'hook_post' => 'pre_item_contact_post',
                'page' => 'item',
                'action' => '',
                'redirect' => null,
                'file' => 'item.php/item-sidebar.php'
            ),
            'comment' => array(
                'name' => __('Add a comment', ADVCAPTCHA_FOLDER),
                'hook_show' => 'advcaptcha_hook_comment',
                'hook_post' => 'pre_item_add_comment_post',
                'page' => 'item',
                'action' => null,
                'redirect' => null,
                'file' => 'item.php/item-sidebar.php'
            )
        );
    }

    public static function enabled() {
        $return = [];

        foreach(self::all() as $id => $position) {
            $type = AdvCAPTCHA_Helper::getPreference('show_' . $id);

            if($type != '') {
                $position['type'] = $type;
                $return[$id] = $position;
            }
        }
    
        return $return;
    }
}