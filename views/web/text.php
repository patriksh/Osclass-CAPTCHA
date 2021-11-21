<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

$captcha = Session::newInstance()->_get(ADVCAPTCHA_SESSION_KEY);
?>

<div class="control-group">
</div>
<div class="control-group">
    <label class="control-label">
        <?php _e('Enter the text from the image', ADVCAPTCHA_FOLDER); ?>
        <span class="advcaptcha-refresh">
            <img src="<?php echo AdvCAPTCHA_Helper::assetUrl('refresh.svg'); ?>" alt="<?php _e('Refresh', ADVCAPTCHA_FOLDER); ?>" title="<?php _e('Refresh', ADVCAPTCHA_FOLDER); ?>">
        </span>
        <img src="<?php echo $captcha['problem']['image']; ?>" class="advcaptcha-img">
    </label>
    <div class="controls">
        <input type="text" name="advcaptcha" id="advcaptcha">
    </div>
</div>