<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

$pref = advcaptcha_get_preferences();
$positions = advcaptcha_positions();
?>

<div class="wm wm-settings uk-container uk-container-expand uk-padding">
    <h2 class="uk-heading-divider"><?php _e('Settings', advcaptcha_plugin()); ?></h2>
    <form action="<?php echo osc_route_admin_url('advancedcaptcha-post'); ?>" method="POST">
        <fieldset class="uk-fieldset">
            <legend class="uk-legend"><?php _e('reCAPTCHA site key', advcaptcha_plugin()); ?>&nbsp;<a class="uk-label uk-label-primary" href="https://www.google.com/recaptcha/admin/create"><?php _e('Generate here', advcaptcha_plugin()); ?></a></legend>
            <div class="uk-margin">
                <input name="recaptcha_site_key" class="uk-input" type="text" placeholder="<?php _e('Site key for reCAPTCHA v3.', advcaptcha_plugin()); ?>" value="<?php echo $pref['recaptcha_site_key']; ?>" required>
            </div>
        </fieldset>
        <fieldset class="uk-fieldset">
            <legend class="uk-legend"><?php _e('reCAPTCHA secret key', advcaptcha_plugin()); ?>&nbsp;<a class="uk-label uk-label-primary" href="https://www.google.com/recaptcha/admin/create"><?php _e('Generate here', advcaptcha_plugin()); ?></a></legend>
            <div class="uk-margin">
                <input name="recaptcha_secret_key" class="uk-input" type="text" placeholder="<?php _e('Secret key for reCAPTCHA v3.', advcaptcha_plugin()); ?>" value="<?php echo $pref['recaptcha_secret_key']; ?>" required>
            </div>
        </fieldset>
        <fieldset class="uk-fieldset qna-append">
            <legend class="uk-legend"><?php _e('Q&A CAPTCHA questions', advcaptcha_plugin()); ?>&nbsp;<a class="uk-label uk-label-primary" href="https://www.google.com/recaptcha/admin/create"><?php _e('Generate here', advcaptcha_plugin()); ?></a></legend>
            <?php if($pref['questions'] == '') { ?>
                <div class="uk-margin">
                    <div class="uk-inline">
                        <input name="rejection_notes[]" class="uk-input" type="text" placeholder="<?php echo $placeholder; ?>">
                        <a class="uk-form-icon uk-form-icon-flip" href="" uk-icon="icon: plus"></a>
                    </div>
                </div>
            <?php } else { ?>
                    <?php foreach($pref['questions'] as $key => $question) { ?>
                        <div class="uk-margin">
                            <div class="uk-inline">
                                <input name="rejection_notes[]" class="uk-input" type="text" placeholder="<?php echo $placeholder; ?>" value="<?php echo $questions; ?>">
                                <?php if(!$key) { ?>
                                    <span class="uk-form-icon uk-form-icon-flip qna-add" href="" uk-icon="icon: plus"></span>
                                <?php } else { ?>
                                    <span class="uk-form-icon uk-form-icon-flip qna-remove" href="" uk-icon="icon: trash"></span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </fieldset>
        <hr>
        <?php foreach($positions as $id => $pos) { ?>
            <fieldset class="uk-fieldset">
                <legend class="uk-legend"><?php printf(__('"%s" form', advcaptcha_plugin()), $pos['name']); ?><!-- &nbsp;<span class="uk-label uk-label-warning"><?php _e('eBay only', advcaptcha_plugin()); ?> --></span></legend>
                <div class="uk-margin">
                    <select class="uk-select" name="show_<?php echo $id; ?>">
                        <option value=""><?php _e('None', advcaptcha_plugin()); ?></option>
                        <option value="google" <?php if($pref['show_'.$id] == 'google') { echo 'selected'; } ?>><?php _e('reCAPTCHA v3', advcaptcha_plugin()); ?></option>
                        <option value="math" <?php if($pref['show_'.$id] == 'math') { echo 'selected'; } ?>><?php _e('Math', advcaptcha_plugin()); ?></option>
                        <option value="text" <?php if($pref['show_'.$id] == 'text') { echo 'selected'; } ?>><?php _e('Text', advcaptcha_plugin()); ?></option>
                        <option value="question" <?php if($pref['show_'.$id] == 'question') { echo 'selected'; } ?>><?php _e('Q&A', advcaptcha_plugin()); ?></option>
                    </select>
                </div>
            </fieldset>
        <?php } ?>

        <div class="uk-align-center uk-text-center">
            <button type="submit" class="uk-button uk-button-primary uk-button-large"><?php _e('Save', advcaptcha_plugin()); ?></button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.qna-add').click(function(e) {
            e.preventDefault();
            $('.qna-append').append('<div class="uk-margin"><div class="uk-inline"><input name="rejection_notes[]" class="uk-input" type="text" placeholder="<?php echo $placeholder; ?>"><a class="uk-form-icon uk-form-icon-flip" href="" uk-icon="icon: trash"></a></div></div>');
        });
        $('.qna-append').on('click', '.qna-remove', function(e) {
            e.preventDefault();
            $(this).parent().parent().remove();
        })
    });
</script>
