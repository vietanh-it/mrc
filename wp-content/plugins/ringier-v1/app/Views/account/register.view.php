<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<p style="text-align: center">Create your account to join the world of luxury cruises</p>
<div class="ctn-account">
    <div class="tml tml-register" id="theme-my-login<?php $template->the_instance(); ?>" \>
        <div class="row" style="margin-bottom: 30px;border-bottom:1px solid #ccc ;padding-bottom: 30px">
            <div class="col-xs-12 col-sm-7">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <a href="javascript:void(0)" onclick="login_fb()" title="Login width facebook">
                            <img src="<?php echo VIEW_URL.'/images/login-facebook.png' ?>" alt="" style="width: 200px">
                        </a>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <a href="javascript:void(0)"  title="Login width google">
                            <img src="<?php echo VIEW_URL.'/images/glogin.png' ?>" alt="" style="width: 200px">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-5 text-right">
                <?php $template->the_action_links(array('register' => false)); ?>
            </div>
        </div>
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
                <div class="col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label for="first_name" class="text">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                               value="<?php $template->the_posted_value('first_name'); ?>">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label for="last_name" class="text">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                               value="<?php $template->the_posted_value('last_name'); ?>">
                    </div>
                </div>
                <!--<div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label for="nationality" class="text">Your country</label>
                        <select name="country" id="country" class="form-control select-2">
                            <option value="">--- Choose country ---</option>
                            <?php /*if(!empty($country_list)){
                                foreach ($country_list as $c){
                                    */?>
                                    <option value="<?php /*echo $c->alpha_2 */?>" <?php /*echo (!empty($user_info->country) && $user_info->country == $c->alpha_2) ? 'selected' : '' */?>><?php /*echo $c->name */?></option>
                                <?php /*}
                            } */?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label for="phone" class="text">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                               value="<?php /*$template->the_posted_value('phone'); */?>"/>
                    </div>
                </div>-->
                <div class="col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label for="user_email" class="text">Email</label>
                        <input type="text" name="user_email" id="user_email" class="form-control"
                               value="<?php $template->the_posted_value('user_email'); ?>"/>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label for="pass1" class="text">Password</label>
                        <input type="password" name="pass1" id="pass1" class="form-control"
                               value="<?php $template->the_posted_value('pass1'); ?>"/>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label for="pass2" class="text">Password confirm</label>
                        <input type="password" name="pass2" id="pass2" class="form-control "
                               value="<?php $template->the_posted_value('pass2'); ?>"/>
                    </div>
                </div>
            </div>


            <?php do_action('register_form'); ?>

            <p class="tml-registration-confirmation" style="text-align: center;margin-top: 30px"
               id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters('tml_register_passmail_template_message',
                    __('Registration confirmation will be e-mailed to you.', 'theme-my-login')); ?></p>

            <p class="tml-submit-wrap text-center">
                <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>"
                       value="<?php esc_attr_e('Register', 'theme-my-login'); ?>" class="bnt-primary"/>
                <input type="hidden" name="redirect_to"
                       value="<?php $template->the_redirect_url('register'); ?>"/>
                <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"/>
                <input type="hidden" name="action" value="register"/>
            </p>
        </form>
    </div>
</div>
