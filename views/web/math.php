<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

$captcha = Session::newInstance()->_getForm(advcaptcha_session_key());
?>
<div class="mtx-form-group">
    <label for="advcaptcha"><?php _e('What\'s', advcaptcha_plugin()); ?> <span><?php echo $captcha['problem']['num1']; ?></span> + <span><?php echo $captcha['problem']['num2']; ?></span>?</label>
    <input type="text" name="advcaptcha" id="advcaptcha" placeholder="<?php _e('Fill me...', advcaptcha_plugin()); ?>">
    <span class="input-line bg-accent"></span>
</div>
