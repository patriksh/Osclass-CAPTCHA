<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/
$key = advcaptcha_pref('recaptcha_site_key');
?>
<input type="hidden" name="recaptcha_response" id="recaptcha_response">
<input type="hidden" name="advcaptcha_session" id="<?php echo advcaptcha_session_key(); ?>">
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('<?php echo osc_esc_js($key); ?>', { action: '<?php echo osc_esc_js(advcaptcha_session_key()); ?>_form' }).then(function(token) {
            var recaptcha_response = document.getElementById('recaptcha_response');
            recaptcha_response.value = token;
        });
    });
</script>
