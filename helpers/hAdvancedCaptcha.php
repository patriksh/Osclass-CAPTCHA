<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/* Get plugin folder. */
function advcaptcha_plugin() {
    return 'zo_advancedcaptcha';
}

/* Get plugin file URL. */
function advcaptcha_url($file = '') {
    return osc_base_url().'oc-content/plugins/'.advcaptcha_plugin().'/'.$file;
}

function advcaptcha_admin_routes() {
    return array('advancedcaptcha', 'advancedcaptcha-post');
}

function advcaptcha_is_admin() {
    if(!Params::existParam('route')) {
        return false;
    }

    return in_array(Params::getParam('route'), advcaptcha_admin_routes());
}

function advcaptcha_is_route($name) {
    return (Params::getParam('route') == $name);
}

/* Get list of positions (for preferences). */
function advcaptcha_positions() {
    return array(
        'login' => array('name' => __('Login', advcaptcha_plugin()), 'hook_show' => '', 'hook_post' => ''),
        'register' => array('name' => __('Register', advcaptcha_plugin()), 'hook_show' => '', 'hook_post' => ''),
        'recover' => array('name' => __('Forgotten password', advcaptcha_plugin()), 'hook_show' => '', 'hook_post' => ''),
        'contact' => array('name' => __('Contact', advcaptcha_plugin()), 'hook_show' => '', 'hook_post' => ''),
        'item_add' => array('name' => __('Add an item', advcaptcha_plugin()), 'hook_show' => '', 'hook_post' => ''),
        'item_edit' => array('name' => __('Edit an item', advcaptcha_plugin()), 'hook_show' => '', 'hook_post' => ''),
        'comment' => array('name' => __('Add a comment', advcaptcha_plugin()), 'hook_show' => '', 'hook_post' => '')
    );
}

/* Get preferences from View. */
function advcaptcha_get_preferences() {
    return View::newInstance()->_get('advcaptcha_preferences');
}

/* Get Web routes. */
function advcaptcha_web_routes() {
    return array('advancedcaptcha-refresh');
}

/* Get session key. */
function advcaptcha_session_key() {
    $page = Params::getParam('page');
    $action = (Params::getParam('action') != '') ? Params::getParam('action') : 'action';
    $key = 'advcaptcha_'.$page.'_'.$action;

    return $key;
}

/* Generate math captcha. */
function advcaptcha_generate_math($max = 10) {
    $num1 = rand(1, $max);
    $num2 = rand(1, $max);
    $ans = $num1 + $num2;

    return array('num1' => $num1, 'num2' => $num2, 'ans' => $ans, 'type' => 'math');
}

/* Generate text captcha. */
function advcaptcha_generate_text($length = 5) {
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $max = strlen($chars) - 1;
    $random = '';
    for ($i = 0; $i <= $length; $i++) {
        $random .= substr($char, rand(0, $max), 1);
    }

    $image = advcaptcha_generate_text_img($random);

    return array('ans' => $random, 'img' => $image, 'type' => 'text');
}

/* Generate text captcha image. */
function advcaptcha_generate_text_img($string, $width = 250, $height = 80, $fontsize = 24) {
    $captcha = imagecreatetruecolor($width, $height);
    $background = ADVCAPTCHA_PATH.'assets/web/pattern.jpg';
    list($bx, $by) = getimagesize($background);
    $bx = ($bx - $width < 0) ? 0 : rand(0, $bx - $width);
    $by = ($by - $height < 0) ? 0 : rand(0, $by - $height);
    $background = imagecreatefromjpeg($background);
    imagecopy($captcha, $background, 0, 0, $bx, $by, $width, $height);

    $text_size = imagettfbbox($fontsize, 0, $font, $string);
    $text_width = max([$text_size[2], $text_size[4]]) - min([$text_size[0], $text_size[6]]);
    $text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);

    $centerX = ceil(($width - $text_width) / 2);
    $centerX = $centerX < 0 ? 0 : $centerX;
    $centerX = ceil(($height - $text_height) / 2);
    $centerY = $centerX < 0 ? 0 : $centerX;

    if(rand(0, 1)) {
        $centerX -= rand(0,55);
    } else {
        $centerX += rand(0,55);
    }
    $colornow = imagecolorallocate($captcha, rand(0, 100), rand(0, 100), rand(0, 100));
    imagettftext($captcha, $fontsize, rand(-10, 10), $centerX, $centerY, $colornow, $font, $string);

    ob_start();
    imagejpeg($captcha);
    imagedestroy($captcha);
    $contents = ob_get_contents();
    ob_end_clean();

    $image = 'data:image/jpeg;base64,'.base64_encode($contents);
    return $image;
}
