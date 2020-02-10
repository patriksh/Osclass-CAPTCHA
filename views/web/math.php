<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

$captcha = Session::newInstance()->_getForm(advcaptcha_session_key());
?>
<input type="hidden" name="advcaptcha_session" value="<?php echo advcaptcha_session_key(); ?>">
<div class="mtx-form-group">
    <label for="advcaptcha">
        <?php _e('What\'s', advcaptcha_plugin()); ?> <span class="advcaptcha-num1"><?php echo $captcha['problem']['num1']; ?></span> + <span class="advcaptcha-num2"><?php echo $captcha['problem']['num2']; ?></span>?
        <span class="advcaptcha-refresh" data-type="math" data-session="<?php echo advcaptcha_session_key(); ?>"><img src="<?php echo advcaptcha_url('assets/web/img/refresh.svg'); ?>" alt="<?php _e('Refresh', advcaptcha_plugin()); ?>" title="<?php _e('Refresh', advcaptcha_plugin()); ?>"></span>
    </label>
    <input type="text" name="advcaptcha" id="advcaptcha" placeholder="<?php _e('Fill me...', advcaptcha_plugin()); ?>">
    <span class="input-line bg-accent"></span>
</div>
