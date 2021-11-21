<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/
?>

<div class="dftd dftd-settings uk-container uk-container-expand uk-padding">
    <h2 class="uk-heading-divider"><?php _e('Settings', ADVCAPTCHA_FOLDER); ?></h2>
    <form action="<?php echo osc_route_admin_url('advcaptcha-settings-post'); ?>" method="POST">
        <fieldset class="uk-fieldset">
            <legend class="uk-legend">
                <?php _e('reCAPTCHA V3', ADVCAPTCHA_FOLDER); ?>
                &nbsp;<a class="uk-label" href="https://www.google.com/recaptcha/admin/create" target="_blank"><?php _e('Generate here', ADVCAPTCHA_FOLDER); ?></a>
                &nbsp;<a class="uk-label" uk-toggle="target: #recaptcha-threshold"><?php _e('What is score threshold?', ADVCAPTCHA_FOLDER); ?></a>
            </legend>
            <div class="uk-margin uk-grid-small" uk-grid>
                <div class="uk-width-1-3@s">
                    <input name="recaptcha_site_key" class="uk-input" type="text" placeholder="<?php _e('Site key', ADVCAPTCHA_FOLDER); ?>" value="<?php echo AdvCAPTCHA_Helper::getPreference('recaptcha_site_key'); ?>">
                </div>
                <div class="uk-width-1-3@s">
                    <input name="recaptcha_secret_key" class="uk-input" type="text" placeholder="<?php _e('Secret key', ADVCAPTCHA_FOLDER); ?>" value="<?php echo AdvCAPTCHA_Helper::getPreference('recaptcha_secret_key'); ?>">
                </div>
                <div class="uk-width-1-3@s">
                    <input name="recaptcha_threshold" class="uk-input" type="number" placeholder="<?php _e('Score threshold', ADVCAPTCHA_FOLDER); ?>" value="<?php echo AdvCAPTCHA_Helper::getPreference('recaptcha_threshold'); ?>" step="0.1" min="0.1" max="1">
                </div>
            </div>
        </fieldset>

        <fieldset class="uk-fieldset">
            <legend class="uk-legend">
                <?php _e('hCaptcha', ADVCAPTCHA_FOLDER); ?>
                &nbsp;<a class="uk-label" href="https://dashboard.hcaptcha.com/signup" target="_blank"><?php _e('Generate here', ADVCAPTCHA_FOLDER); ?></a>
            </legend>
            <div class="uk-margin uk-grid-small" uk-grid>
                <div class="uk-width-1-2@s">
                    <input name="hcaptcha_site_key" class="uk-input" type="text" placeholder="<?php _e('Site key', ADVCAPTCHA_FOLDER); ?>" value="<?php echo AdvCAPTCHA_Helper::getPreference('hcaptcha_site_key'); ?>">
                </div>
                <div class="uk-width-1-2@s">
                    <input name="hcaptcha_secret_key" class="uk-input" type="text" placeholder="<?php _e('Secret key', ADVCAPTCHA_FOLDER); ?>" value="<?php echo AdvCAPTCHA_Helper::getPreference('hcaptcha_secret_key'); ?>">
                </div>
            </div>
        </fieldset>

        <?php
        $questions = json_decode(AdvCAPTCHA_Helper::getPreference('questions'));
        $qnaKey = 0;
        ?>
        <fieldset class="uk-fieldset qna-append">
            <legend class="uk-legend"><?php _e('Q&A questions', ADVCAPTCHA_FOLDER); ?></legend>
            <?php if(!$questions) { ?>
                <div class="uk-margin uk-grid-small" uk-grid>
                    <div class="uk-width-2-5@s">
                        <input name="qna[0][question]" class="uk-input" type="text" placeholder="<?php _e('Question', ADVCAPTCHA_FOLDER); ?>">
                    </div>
                    <div class="uk-width-2-5@s">
                        <input name="qna[0][answer]" class="uk-input" type="text" placeholder="<?php _e('Answer', ADVCAPTCHA_FOLDER); ?>">
                    </div>
                    <div class="uk-width-1-5@s">
                        <button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-add"><?php _e('Add more', ADVCAPTCHA_FOLDER); ?></button>
                    </div>
                </div>
            <?php } else { ?>
                <?php $count = 0; ?>
                <?php foreach($questions as $question => $answer) { ?>
                    <?php $qnaKey = $count; $count++; ?>
                    <div class="uk-margin uk-grid-small" uk-grid>
                        <div class="uk-width-2-5@s">
                            <input name="qna[<?php echo $qnaKey; ?>][question]" class="uk-input" type="text" placeholder="<?php _e('Question', ADVCAPTCHA_FOLDER); ?>" value="<?php echo $question; ?>">
                        </div>
                        <div class="uk-width-2-5@s">
                            <input name="qna[<?php echo $qnaKey; ?>][answer]" class="uk-input" type="text" placeholder="<?php _e('Answer', ADVCAPTCHA_FOLDER); ?>" value="<?php echo $answer; ?>">
                        </div>
                        <div class="uk-width-1-5@s">
                            <?php if(!$qnaKey) { ?>
                                <button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-add"><?php _e('Add more', ADVCAPTCHA_FOLDER); ?></button>
                            <?php } else { ?>
                                <button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-remove"><?php _e('Remove', ADVCAPTCHA_FOLDER); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <input type="hidden" id="qna-key" value="<?php echo $qnaKey; ?>">
        </fieldset>
        
        <hr>

        <?php foreach(AdvCAPTCHA_Positions::all() as $id => $position) { ?>
            <?php
            $value = AdvCAPTCHA_Helper::getPreference('show_' . $id);
            $theme_mod = '';
            if(array_key_exists('file', $position)) {
                $theme_mod = '&nbsp;<a class="uk-label uk-label-warning theme-mod-toggle" uk-toggle="target: #theme-mod" data-hook="'.$position['hook_show'].'" data-file="'.$position['file'].'">'.__('Theme mod required', ADVCAPTCHA_FOLDER).'</a>';
            }
            ?>
            <fieldset class="uk-fieldset">
                <legend class="uk-legend"><?php printf(__('"%s" form', ADVCAPTCHA_FOLDER), $position['name']); ?><?php echo $theme_mod; ?></legend>
                <div class="uk-margin">
                    <select class="uk-select" name="show_<?php echo $id; ?>">
                        <option value=""><?php _e('None', ADVCAPTCHA_FOLDER); ?></option>
                        <option value="recaptcha" <?php if($value == 'recaptcha') echo 'selected'; ?>><?php _e('reCAPTCHA v3', ADVCAPTCHA_FOLDER); ?></option>
                        <option value="hcaptcha" <?php if($value == 'hcaptcha') echo 'selected'; ?>><?php _e('hCaptcha', ADVCAPTCHA_FOLDER); ?></option>
                        <option value="math" <?php if($value == 'math') echo 'selected'; ?>><?php _e('Math', ADVCAPTCHA_FOLDER); ?></option>
                        <option value="text" <?php if($value == 'text') echo 'selected'; ?>><?php _e('Text', ADVCAPTCHA_FOLDER); ?></option>
                        <option value="qna" <?php if($value == 'qna') echo 'selected'; ?>><?php _e('Q&A', ADVCAPTCHA_FOLDER); ?></option>
                    </select>
                </div>
            </fieldset>
        <?php } ?>

        <div class="uk-align-center uk-text-center">
            <button type="submit" class="uk-button uk-button-primary uk-button-large"><?php _e('Save', ADVCAPTCHA_FOLDER); ?></button>
        </div>
    </form>
</div>

<div id="recaptcha-threshold" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title"><?php _e('reCAPTCHA V3 score threshold', ADVCAPTCHA_FOLDER); ?></h2>
        <p><?php _e('reCAPTCHA v3 returns a score (1.0 is very likely a good interaction, 0.0 is very likely a bot). Based on the score, you can take variable action in the context of your site.', ADVCAPTCHA_FOLDER); ?></p>
        <p><?php _e('reCAPTCHA learns by seeing real traffic on your site. For this reason, scores in a staging environment or soon after implementing may differ from production. As reCAPTCHA v3 doesn\'t ever interrupt the user flow, you can first run reCAPTCHA without taking action and then decide on thresholds by looking at your traffic in the admin console. By default, you can use a threshold of 0.5.', ADVCAPTCHA_FOLDER); ?></p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button"><?php _e('Close', ADVCAPTCHA_FOLDER); ?></button>
        </p>
    </div>
</div>

<div id="theme-mod" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title"><?php _e('Theme mod', ADVCAPTCHA_FOLDER); ?></h2>
        <p><?php _e('Open', ADVCAPTCHA_FOLDER); ?> <strong class="theme-mod-file"></strong> <?php _e('in <i>oc-content/themes/your_theme</i> and add', ADVCAPTCHA_FOLDER); ?> <strong class="theme-mod-hook uk-display-block"></strong> <?php _e('where you want the CAPTCHA to be shown. It\'s usually somewhere above the <i>&lt;/form&gt;</i> tag.', ADVCAPTCHA_FOLDER); ?></p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button"><?php _e('Close', ADVCAPTCHA_FOLDER); ?></button>
        </p>
    </div>
</div>

<script>
$(function() {
    $('.theme-mod-toggle').click(function() {
        var file = $(this).attr('data-file');
        var hook = "&lt;?php osc_run_hook('" + $(this).attr('data-hook') + "'); ?&gt;";
        $('#theme-mod .theme-mod-file').html(file);
        $('#theme-mod .theme-mod-hook').html(hook);
    });

    // This is some deep jQuery magic shit.

    $('.qna-add').click(function(e) {
        e.preventDefault();
        var key = $('#qna-key').val();
        key++;
        $('#qna-key').val(key);
        $('.qna-append').append('<div class="uk-margin uk-grid-small" uk-grid><div class="uk-width-2-5@s"><input name="qna['+key+'][question]" class="uk-input" type="text" placeholder="<?php _e('Question', ADVCAPTCHA_FOLDER); ?>" required></div><div class="uk-width-2-5@s"><input name="qna['+key+'][answer]" class="uk-input" type="text" placeholder="<?php _e('Answer', ADVCAPTCHA_FOLDER); ?>" required></div><div class="uk-width-1-5@s"><button type="button" class="uk-button uk-button-secondary uk-width-1-1 qna-remove"><?php _e('Remove', ADVCAPTCHA_FOLDER); ?></button></div></div>');
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