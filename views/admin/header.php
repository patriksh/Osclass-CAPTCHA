<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');
?>
<header class="wm wm-topbar wm-secondary-darker-bg">
    <p>Open-source plugin by <a href="//wmods.zagorski-oglasnik.com" target="_blank">WEBmods</a> | Published on <a href="//loveosclass.com" target="_blank">LoveOsclass</a> | <a href="//osclasscommunity.com" target="_blank">Osclass Community</a></p>
</header>
<nav class="wm wm-navbar wm-background">
    <p class="wm-navbar-logo">Advanced CAPTCHA</p>
    <ul class="wm-navbar-items">
        <li class="<?php if(advcaptcha_is_route('advancedcaptcha')) { echo 'active'; } ?>"><a href="<?php echo osc_route_admin_url('advancedcaptcha'); ?>"><?php _e('Settings', advcaptcha_plugin()); ?></a></li>
        <li><a href="//osclasscommunity.com" target="_blank"><?php _e('Osclass Community', advcaptcha_plugin()); ?></a></li>
        <li><a href="//wmods.zagorski-oglasnik.com" target="_blank"><?php _e('WEBmods', advcaptcha_plugin()); ?></a></li>
    </ul>
</nav>
