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
        <?php foreach($positions as $id => $pos) { ?>
            <fieldset class="uk-fieldset">
                <legend class="uk-legend"><?php printf(__('Show at "%s" form', advcaptcha_plugin()), $pos['name']); ?><!-- &nbsp;<span class="uk-label uk-label-warning"><?php _e('eBay only', advcaptcha_plugin()); ?> --></span></legend>
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
