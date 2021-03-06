<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
</div>
<style>
    .main-content {
        display: none;
    }
    .tml {
        max-width: none;
    }
</style>

    <div class="col-xs-12 col-sm-4 col-sm-offset-4">
        <div class="ctn-account" style="text-align: center">
            <div class="tml tml-lostpassword" id="theme-my-login<?php $template->the_instance(); ?>">
                <?php echo '<p class="font-playfair" style="font-style: italic">We will send a link to your email to help you reset your password</p>' ?>
                <?php $template->the_errors(); ?>
                <form name="lostpasswordform" id="lostpasswordform<?php $template->the_instance(); ?>"
                      style="margin-top: 30px"
                      action="<?php $template->the_action_url('lostpassword', 'login_post'); ?>" method="post">
                    <p class="tml-user-login-wrap">
                        <!--<label for="user_login<?php /*$template->the_instance(); */ ?>"><?php
                        /*                    if ('email' == $theme_my_login->get_option('login_type')) {
                                                _e('E-mail:', 'theme-my-login');
                                            } else {
                                                _e('Username or E-mail:', 'theme-my-login');
                                            } */ ?></label>-->
                        <input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>"
                               class="input form-control" placeholder="Your email address"
                               value="<?php $template->the_posted_value('user_login'); ?>" size="20"/>
                    </p>

                    <?php do_action('lostpassword_form'); ?>

                    <p class="tml-submit-wrap">
                        <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>"
                               value="Send"
                               class="bnt-primary"/>
                        <input type="hidden" name="redirect_to"
                               value="<?php $template->the_redirect_url('lostpassword'); ?>"/>
                        <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"/>
                        <input type="hidden" name="action" value="lostpassword"/>
                    </p>
                </form>
                <?php //$template->the_action_links(array('lostpassword' => false)); ?>
            </div>
        </div>
    </div>

<div>