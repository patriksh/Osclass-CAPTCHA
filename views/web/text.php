<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

$captcha = Session::newInstance()->_getForm(advcaptcha_session_key());
?>
<input type="hidden" name="advcaptcha_session" id="<?php echo advcaptcha_session_key(); ?>">
<div class="mtx-form-group">
    <img src="<?php echo $captcha['problem']['img']; ?>">
</div>
<div class="mtx-form-group">
    <label for="advcaptcha"><?php _e('Enter the text from image above', advcaptcha_plugin()); ?></label>
    <input type="text" name="advcaptcha" id="advcaptcha" placeholder="<?php _e('Fill me...', advcaptcha_plugin()); ?>">
    <span class="input-line bg-accent"></span>
</div>
