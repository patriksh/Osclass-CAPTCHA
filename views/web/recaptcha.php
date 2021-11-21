<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

$captcha = Session::newInstance()->_get(ADVCAPTCHA_SESSION_KEY);
?>

<input type="hidden" name="recaptcha_response" id="recaptcha_response">
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('<?php echo osc_esc_js(AdvCAPTCHA_Helper::getPreference('recaptcha_site_key')); ?>', { action: '<?php echo $captcha['name']; ?>_form' }).then(function(token) {
        document.getElementById('recaptcha_response').value = token;
    });
});
</script>