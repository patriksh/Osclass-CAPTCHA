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

$placeholder_q = __('Question', advcaptcha_plugin());
$placeholder_a = __('Answer', advcaptcha_plugin());
$qna_key = 0;
?>
<div class="wm wm-settings uk-container uk-container-expand uk-padding">
    <h2 class="uk-heading-divider"><?php _e('Settings', advcaptcha_plugin()); ?></h2>
    <form action="<?php echo osc_route_admin_url('advancedcaptcha-post'); ?>" method="POST">
        <fieldset class="uk-fieldset">
            <legend class="uk-legend"><?php _e('reCAPTCHA V3 site key', advcaptcha_plugin()); ?>&nbsp;<a class="uk-label uk-label-primary" href="https://www.google.com/recaptcha/admin/create" target="_blank"><?php _e('Generate here', advcaptcha_plugin()); ?></a></legend>
            <div class="uk-margin">
                <input name="recaptcha_site_key" class="uk-input" type="text" placeholder="<?php _e('Site key for reCAPTCHA v3.', advcaptcha_plugin()); ?>" value="<?php echo $pref['recaptcha_site_key']; ?>">
            </div>
        </fieldset>
        <fieldset class="uk-fieldset">
            <legend class="uk-legend"><?php _e('reCAPTCHA V3 secret key', advcaptcha_plugin()); ?>&nbsp;<a class="uk-label uk-label-primary" href="https://www.google.com/recaptcha/admin/create" target="_blank"><?php _e('Generate here', advcaptcha_plugin()); ?></a></legend>
            <div class="uk-margin">
                <input name="recaptcha_secret_key" class="uk-input" type="text" placeholder="<?php _e('Secret key for reCAPTCHA v3.', advcaptcha_plugin()); ?>" value="<?php echo $pref['recaptcha_secret_key']; ?>">
            </div>
        </fieldset>
        <fieldset class="uk-fieldset">
            <legend class="uk-legend"><?php _e('reCAPTCHA V3 score threshold', advcaptcha_plugin()); ?>&nbsp;<a class="uk-label uk-label-primary" uk-toggle="target: #recaptcha-threshold" ><?php _e('More info', advcaptcha_plugin()); ?></a></legend>
            <div class="uk-margin">
                <input name="recaptcha_threshold" class="uk-input" type="number" placeholder="<?php _e('Score threshold for reCAPTCHA.', advcaptcha_plugin()); ?>" value="<?php echo $pref['recaptcha_threshold']; ?>" step="0.1" min="0.1" max="1">
            </div>
        </fieldset>
        <fieldset class="uk-fieldset qna-append">
            <legend class="uk-legend"><?php _e('Q&A CAPTCHA questions', advcaptcha_plugin()); ?></legend>
            <?php if($pref['questions'] == '') { ?>
                <div class="uk-margin uk-grid-small" uk-grid>
                    <div class="uk-width-2-5@s">
                        <input name="qna_q[0]" class="uk-input qna-q" type="text" placeholder="<?php echo $placeholder_q; ?>" required>
                    </div>
                    <div class="uk-width-2-5@s">
                        <input name="qna_a[0]" class="uk-input qna-a" type="text" placeholder="<?php echo $placeholder_a; ?>" required>
                    </div>
                    <div class="uk-width-1-5@s">
                        <button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-add"><?php _e('Add more', advcaptcha_plugin()); ?></button>
                    </div>
                </div>
            <?php } else { ?>
                <?php foreach(unserialize($pref['questions']) as $key => $question) { ?>
                    <?php $qna_key = $key;?>
                    <div class="uk-margin uk-grid-small" uk-grid>
                        <div class="uk-width-2-5@s">
                            <input name="qna_q[<?php echo $key; ?>]" class="uk-input qna-q" type="text" placeholder="<?php echo $placeholder_q; ?>" value="<?php echo $question[0]; ?>">
                        </div>
                        <div class="uk-width-2-5@s">
                            <input name="qna_a[<?php echo $key; ?>]" class="uk-input qna-a" type="text" placeholder="<?php echo $placeholder_a; ?>" value="<?php echo $question[1]; ?>">
                        </div>
                        <div class="uk-width-1-5@s">
                            <?php if(!$key) { ?>
                                <button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-add"><?php _e('Add more', advcaptcha_plugin()); ?></button>
                            <?php } else { ?>
                                <button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-remove"><?php _e('Remove', advcaptcha_plugin()); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <input type="hidden" id="qna-key" value="<?php echo $qna_key; ?>">
        </fieldset>
        <hr>
        <?php foreach($positions as $id => $pos) { ?>
            <?php
            $theme_mod = '';
            if(array_key_exists('file', $pos)) {
                $theme_mod = '&nbsp;<a class="uk-label uk-label-warning theme-mod-toggle" uk-toggle="target: #theme-mod" data-hook="'.$pos['hook_show'].'" data-file="'.$pos['file'].'">'.__('Theme mod required', advcaptcha_plugin()).'</a>';
            }
            ?>
            <fieldset class="uk-fieldset">
                <legend class="uk-legend"><?php printf(__('"%s" form', advcaptcha_plugin()), $pos['name']); ?><?php echo $theme_mod; ?></legend>
                <div class="uk-margin">
                    <select class="uk-select" name="show_<?php echo $id; ?>">
                        <option value=""><?php _e('None', advcaptcha_plugin()); ?></option>
                        <option value="google" <?php if($pref['show_'.$id] == 'google') { echo 'selected'; } ?>><?php _e('reCAPTCHA v3', advcaptcha_plugin()); ?></option>
                        <option value="math" <?php if($pref['show_'.$id] == 'math') { echo 'selected'; } ?>><?php _e('Math', advcaptcha_plugin()); ?></option>
                        <option value="text" <?php if($pref['show_'.$id] == 'text') { echo 'selected'; } ?>><?php _e('Text', advcaptcha_plugin()); ?></option>
                        <option value="qna" <?php if($pref['show_'.$id] == 'qna') { echo 'selected'; } ?>><?php _e('Q&A', advcaptcha_plugin()); ?></option>
                    </select>
                </div>
            </fieldset>
        <?php } ?>
        <hr>
        <fieldset class="uk-fieldset">
            <legend class="uk-legend"><?php _e('Adapting to your theme, adding CAPTCHA to an existing plugin') ?></legend>
            <div class="uk-margin">
                <p><?php printf(__('Ask on our <a href="%s">support forums</a>.'), 'https://forums.osclasscommunity.com'); ?></p>
            </div>
            <div class="uk-margin">
                <p><?php printf(__('Drop me a mail at <a href="mailto:%s">%s</a> for premium integration to your site.'), 'patrikkos04@gmail.com', 'patrikkos04(at)gmail.com'); ?></p>
            </div>
        </fieldset>

        <div class="uk-align-center uk-text-center">
            <button type="submit" class="uk-button uk-button-primary uk-button-large"><?php _e('Save', advcaptcha_plugin()); ?></button>
        </div>
    </form>
</div>

<div id="recaptcha-threshold" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title"><?php _e('reCAPTCHA V3 score threshold', advcaptcha_plugin()); ?></h2>
        <p><?php _e('reCAPTCHA v3 returns a score (1.0 is very likely a good interaction, 0.0 is very likely a bot). Based on the score, you can take variable action in the context of your site.', advcaptcha_plugin()); ?></p>
        <p><?php _e('reCAPTCHA learns by seeing real traffic on your site. For this reason, scores in a staging environment or soon after implementing may differ from production. As reCAPTCHA v3 doesn\'t ever interrupt the user flow, you can first run reCAPTCHA without taking action and then decide on thresholds by looking at your traffic in the admin console. By default, you can use a threshold of 0.5.', advcaptcha_plugin()); ?></p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button"><?php _e('Close', advcaptcha_plugin()); ?></button>
        </p>
    </div>
</div>

<div id="theme-mod" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title"><?php _e('Theme mod', advcaptcha_plugin()); ?></h2>
        <p><?php _e('Open', advcaptcha_plugin()); ?> <strong class="theme-mod-file"></strong> <?php _e('in <i>oc-content/themes/your_theme</i> and add', advcaptcha_plugin()); ?> <strong class="theme-mod-hook uk-display-block"></strong> <?php _e('where you want the CAPTCHA to be shown. It\'s usually somewhere above the <i>&lt;/form&gt;</i> tag.', advcaptcha_plugin()); ?></p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button"><?php _e('Close', advcaptcha_plugin()); ?></button>
        </p>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.theme-mod-toggle').click(function() {
            var file = $(this).attr('data-file');
            var hook = "&lt;?php osc_run_hook('"+$(this).attr('data-hook')+"'); ?&gt;";
            $('#theme-mod .theme-mod-file').html(file);
            $('#theme-mod .theme-mod-hook').html(hook);
        });

        $('.qna-add').click(function(e) {
            e.preventDefault();
            var key = $('#qna-key').val();
            key++;
            $('#qna-key').val(key);
            $('.qna-append').append('<div class="uk-margin uk-grid-small" uk-grid><div class="uk-width-2-5@s"><input name="qna_q['+key+']" class="uk-input qna-q" type="text" placeholder="<?php echo $placeholder_q; ?>" required></div><div class="uk-width-2-5@s"><input name="qna_a['+key+']" class="uk-input qna-a" type="text" placeholder="<?php echo $placeholder_a; ?>" required></div><div class="uk-width-1-5@s"><button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-remove"><?php _e('Remove', advcaptcha_plugin()); ?></button></div></div>');
        });

        $('.qna-append').on('click', '.qna-remove', function(e) {
            e.preventDefault();
            var key = $('#qna-key').val();
            key--;
            $('#qna-key').val(key);
            $(this).parent().parent().remove();
        });
    });
</script>
