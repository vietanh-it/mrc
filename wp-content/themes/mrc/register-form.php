<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="ctn-account">
                <h1 class="tile-main">Register
                    <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
                </h1>
                <div class="tml tml-register" id="theme-my-login<?php $template->the_instance(); ?>" \>
                    <?php $template->the_errors(); ?>
                    <form name="registerform" id="registerform<?php $template->the_instance(); ?>"
                          action="<?php $template->the_action_url('register', 'login_post'); ?>" method="post">
                        <?php if ('email' != $theme_my_login->get_option('login_type')) : ?>
                            <p class="tml-user-login-wrap">
                                <label for="user_login<?php $template->the_instance(); ?>"><?php _e('Username',
                                        'theme-my-login'); ?></label>
                                <input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>"
                                       class="input" value="<?php $template->the_posted_value('user_login'); ?>"
                                       size="20"/>
                            </p>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="full_name" class="text">Full name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                           value="<?php $template->the_posted_value('full_name'); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="location" class="text">Address</label>
                                    <select name="location" id="location" class="form-control select-2">
                                        <option value="">--- Chọn nơi ở ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="user_login" class="text">Email</label>
                                    <input type="text" name="user_login" id="user_login" class="form-control"
                                           value="<?php $template->the_posted_value('user_login'); ?>"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="phone" class="text">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                           value="<?php $template->the_posted_value('phone'); ?>"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="pass1" class="text">Password</label>
                                    <input type="password" name="pass1" id="pass1" class="form-control"
                                           value="<?php $template->the_posted_value('pass1'); ?>"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="pass2" class="text">Password confirm</label>
                                    <input type="password" name="pass2" id="pass2" class="form-control "
                                           value="<?php $template->the_posted_value('pass2'); ?>"/>
                                </div>
                            </div>


                        </div>


                        <?php do_action('register_form'); ?>

                        <p class="tml-registration-confirmation"
                           id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters('tml_register_passmail_template_message',
                                __('Registration confirmation will be e-mailed to you.', 'theme-my-login')); ?></p>

                        <p class="tml-submit-wrap">
                            <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>"
                                   value="<?php esc_attr_e('Register', 'theme-my-login'); ?>" class="bnt-primary"/>
                            <input type="hidden" name="redirect_to"
                                   value="<?php $template->the_redirect_url('register'); ?>"/>
                            <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"/>
                            <input type="hidden" name="action" value="register"/>
                        </p>
                    </form>
                    <?php $template->the_action_links(array('register' => false)); ?>
                </div>
            </div>
        </div>
    </div>
</div>
