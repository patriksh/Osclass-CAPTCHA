<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

$captcha = Session::newInstance()->_get(ADVCAPTCHA_SESSION_KEY);
?>

<div class="control-group">
    <label class="control-label" for="advcaptcha">
        <span class="advcaptcha-q"><?php echo $captcha['problem']['question']; ?></span>
        <span class="advcaptcha-refresh">
            <img src="<?php echo AdvCAPTCHA_Helper::assetUrl('refresh.svg'); ?>" alt="<?php _e('Refresh', ADVCAPTCHA_FOLDER); ?>" title="<?php _e('Refresh', ADVCAPTCHA_FOLDER); ?>">
        </span>
    </label>
    <div class="controls">
        <input type="text" name="advcaptcha" id="advcaptcha">
    </div>
</div>