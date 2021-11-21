<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

$captcha = Session::newInstance()->_get(ADVCAPTCHA_SESSION_KEY);
?>

<div class="control-group">
    <label class="control-label" for="advcaptcha">
        <?php _e('What\'s', ADVCAPTCHA_FOLDER); ?> <span class="advcaptcha-num1"><?php echo $captcha['problem']['num1']; ?></span> + <span class="advcaptcha-num2"><?php echo $captcha['problem']['num2']; ?></span>?
        <span class="advcaptcha-refresh">
            <img src="<?php echo AdvCAPTCHA_Helper::assetUrl('refresh.svg'); ?>" alt="<?php _e('Refresh', ADVCAPTCHA_FOLDER); ?>" title="<?php _e('Refresh', ADVCAPTCHA_FOLDER); ?>">
        </span>
    </label>
    <div class="controls">
        <input type="text" name="advcaptcha" id="advcaptcha">
    </div>
</div>