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
        <span class="advcaptcha-q"><?php echo $captcha['problem']['question']; ?></span>
        <?php if($captcha['problem']['count'] > 1) { ?>
            <span class="advcaptcha-refresh" data-type="qna" data-session="<?php echo advcaptcha_session_key(); ?>"><img src="<?php echo advcaptcha_url('assets/web/img/refresh.svg'); ?>" alt="<?php _e('Refresh', advcaptcha_plugin()); ?>" title="<?php _e('Refresh', advcaptcha_plugin()); ?>"></span>
        <?php } ?>
    </label>
    <input type="text" name="advcaptcha" id="advcaptcha" placeholder="<?php _e('Fill me...', advcaptcha_plugin()); ?>">
    <span class="input-line bg-accent"></span>
</div>
