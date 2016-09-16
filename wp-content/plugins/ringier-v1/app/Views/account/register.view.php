<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
$is_refer = 0;
$email_refer = 0;
$code_refer = 0;
$user_refer_id= 0;

if(!empty($_GET['email']) && !empty($_GET['code']) && !empty($_GET['id'])){
    $user_refer_id = $_GET['id'];

    $list_email_refer = get_user_meta($user_refer_id,'email_refer');
    $list_code_refer = get_user_meta($user_refer_id,'code_refer');
    if(!empty($list_email_refer)){
        foreach ($list_email_refer as $k => $email){
            if($_GET['email'] == $email){
                $code = $list_code_refer[$k];
                if($code == $_GET['code']){
                    $email_refer = $email;
                    $code_refer = $code;
                    $is_refer = 1;
                }
            }
        }
    }
}

?>
<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4">
        <p style="text-align: center;font-style: italic" class="font-playfair">Create your account to join the world of luxury cruises</p>
        <div class="ctn-account">
            <div class="tml tml-register" id="theme-my-login<?php $template->the_instance(); ?>" \>
                <p style="margin: 20px 0;color: black;font-size: 17px;text-align: center;font-family: 'Playfair Display', serif;">Sign In with</p>

                <div class="row">
                    <div class="col-xs-12 col-sm-6" style="text-align: left">
                        <a href="javascript:void(0)" onclick="login_fb()" title="Login width facebook">
                            <img src="<?php echo VIEW_URL.'/images/login-facebook.png' ?>" alt="" style="max-width: 100%" >
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6" style="text-align: right">
                        <a href="javascript:void(0)"  title="Login width google">
                            <img src="<?php echo VIEW_URL.'/images/glogin.png' ?>" alt="" style="max-width: 100%" >
                        </a>
                    </div>
                </div>
                <p class="line-account"> <span>or  create your account</span> </p>
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
                                <!--<label for="first_name" class="text"></label>-->
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name"
                                       value="<?php $template->the_posted_value('first_name'); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name"
                                       value="<?php $template->the_posted_value('last_name'); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="user_email" class="text"></label>-->
                                <input type="text" name="user_email" id="user_email" class="form-control" placeholder="E-mail"
                                       value="<?php $template->the_posted_value('user_email'); ?>"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="pass1" class="text">Password</label>-->
                                <input type="password" name="pass1" id="pass1" class="form-control" placeholder="Password"
                                       value="<?php $template->the_posted_value('pass1'); ?>"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="pass2" class="text"> </label>-->
                                <input type="password" name="pass2" id="pass2" class="form-control " placeholder="Confirm Password"
                                       value="<?php $template->the_posted_value('pass2'); ?>"/>
                            </div>
                        </div>
                    </div>


                    <?php do_action('register_form'); ?>

                    <!--<p class="tml-registration-confirmation" style="text-align: center;margin-top: 30px"
               id="reg_passmail<?php /*$template->the_instance(); */?>"><?php /*echo apply_filters('tml_register_passmail_template_message',
                    __('Registration confirmation will be e-mailed to you.', 'theme-my-login')); */?></p>-->

                    <p class="tml-submit-wrap text-center" style="margin-top: 30px">
                        <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>"
                               value="<?php esc_attr_e('Create Account', 'theme-my-login'); ?>" class="bnt-primary"/>
                        <input type="hidden" name="redirect_to"
                               value="<?php $template->the_redirect_url('register'); ?>"/>
                        <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"/>
                        <input type="hidden" name="action" value="register"/>

                        <!--info refer-->
                        <input type="hidden" name="is_refer" value="<?php echo $is_refer ?>"/>
                        <input type="hidden" name="email_refer" value="<?php echo $email_refer ?>"/>
                        <input type="hidden" name="code_refer" value="<?php echo $code_refer ?>"/>
                        <input type="hidden" name="user_refer_id" value="<?php echo $user_refer_id ?>"/>
                    </p>
                </form>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 text-center">
                        <?php //$template->the_action_links(array('register' => false)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
