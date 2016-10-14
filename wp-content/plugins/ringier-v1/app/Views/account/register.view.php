<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/

if (!empty($_GET['email']) && !empty($_GET['code']) && !empty($_GET['id'])) {
    $is_refer = 0;
    $user_refer_id = $_GET['id'];

    $User = \RVN\Models\Users::init();
    $chekc_refer = $User->getUserRefer($user_refer_id,$_GET['email'],$_GET['code'],'pending');
    if(!empty($chekc_refer)){
        $is_refer =  1;
    }
}

?>
</div>

<style>
    .ctn-account .tml {
        max-width: none;
    }

    .main-content {
        display: none;
    }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4">
        <?php if(isset($is_refer)){ ?>
            <div class="message">
                <?php if($is_refer){ ?>
                    <p class="text-success bg-success">
                        You are invited. Please register to complete and receive many privileges.
                    </p>
                <?php } else { ?>
                    <p class="text-danger bg-danger">Link inviting inaccurate or have been used. Please check again.</p>
                <?php } ?>
            </div>
        <?php } ?>
        <p style="text-align: center;font-style: italic" class="font-playfair">Create your account to join the world of
            luxury cruises</p>
        <div class="text-center">
            Already have an account? <a href="<?php echo wp_login_url(); ?>" style="color: #999999;">Sign-in</a>
        </div>
        <div class="ctn-account">
            <div class="tml tml-register" id="theme-my-login<?php $template->the_instance(); ?>">
                <p style="margin: 20px 0;color: black;font-size: 17px;text-align: center;font-family: 'Playfair Display', serif;">
                    Sign In with</p>

                <div class="row">
                    <div class="col-xs-12 col-sm-6" style="text-align: left">
                        <a href="javascript:void(0)" onclick="login_fb()" title="Login with facebook">
                            <img src="<?php echo VIEW_URL . '/images/login-facebook.png' ?>" alt=""
                                 style="max-width: 100%">
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6" style="text-align: right">
                        <a href="javascript:void(0)" title="Login with google" class="google-plus-signin">
                            <img src="<?php echo VIEW_URL . '/images/glogin.png' ?>" alt="" style="max-width: 100%">
                        </a>
                    </div>
                </div>
                <p class="line-account"><span>or  create your account below</span></p>
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
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       placeholder="First Name"
                                       value="<?php $template->the_posted_value('first_name'); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       placeholder="Last Name"
                                       value="<?php $template->the_posted_value('last_name'); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="user_email" class="text"></label>-->
                                <input type="text" name="user_email" id="user_email" class="form-control"
                                       placeholder="E-mail"
                                       value="<?php $template->the_posted_value('user_email'); ?>"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="pass1" class="text">Password</label>-->
                                <input type="password" name="pass1" id="pass1" class="form-control"
                                       placeholder="Password"
                                       value="<?php $template->the_posted_value('pass1'); ?>"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <!--<label for="pass2" class="text"> </label>-->
                                <input type="password" name="pass2" id="pass2" class="form-control "
                                       placeholder="Confirm Password"
                                       value="<?php $template->the_posted_value('pass2'); ?>"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" value="1" name="is_agree">
                                    <a href="<?php echo WP_SITEURL . '/terms-of-use'; ?>"
                                       class="font-playfair is-agree-terms" target="_blank">
                                        I accept Terms of Use and Privacy Policy
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>


                    <?php do_action('register_form'); ?>

                    <!--<p class="tml-registration-confirmation" style="text-align: center;margin-top: 30px"
               id="reg_passmail<?php /*$template->the_instance(); */ ?>"><?php /*echo apply_filters('tml_register_passmail_template_message',
                    __('Registration confirmation will be e-mailed to you.', 'theme-my-login')); */ ?></p>-->

                    <p class="tml-submit-wrap text-center" style="margin-top: 0">
                        <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>"
                               value="Sign Up" class="bnt-primary"/>
                        <input type="hidden" name="redirect_to"
                               value="<?php $template->the_redirect_url('register'); ?>"/>
                        <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"/>
                        <input type="hidden" name="action" value="register"/>

                        <!--info refer-->
                        <?php if(!empty($is_refer)){ ?>
                            <input type="hidden" name="email_refer" value="<?php echo $_GET['email'] ?>"/>
                            <input type="hidden" name="code_refer" value="<?php echo $_GET['code'] ?>"/>
                            <input type="hidden" name="user_refer_id" value="<?php echo $_GET['id'] ?>"/>
                        <?php } ?>

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

<div>

    <script>
        var $ = jQuery.noConflict();
        $(document).ready(function () {
            $('.google-plus-signin').on('click', function (e) {
                auth2.grantOfflineAccess({'redirect_uri': 'postmessage'}).then(function (response) {

                    if (response) {
                        gapi.client.load('plus', 'v1', function () {
                            var request = gapi.client.plus.people.get({
                                'userId': 'me'
                            });
                            request.execute(function (resp) {
                                console.log('Retrieved profile for:' + resp.displayName);

                                $.ajax({
                                    url: MyAjax.ajax_url,
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        action: 'ajax_handler_account',
                                        method: 'GoogleLogin',
                                        user_data: resp
                                    },
                                    success: function (data) {
                                        if (data.status == 'success') {
                                            swal({
                                                    "title": "Success",
                                                    "text": "<p style='color: #008000;font-weight: bold'>" + data.message + "</p>",
                                                    "type": "success",
                                                    html: true
                                                },
                                                function(){
                                                    window.location.href = "";
                                                });

                                        }
                                        else {
                                            var result = data.message;
                                            var htmlErrors = "";
                                            if (result.length > 0) {
                                                htmlErrors += "<ul style='color: red'>";
                                                for (var i = 0; i < result.length; i++) {
                                                    htmlErrors += "<li style='list-style: none'>" + result[i] + "</li>";
                                                }
                                                htmlErrors += "</ul>";
                                            }
                                            swal({"title": "Error", "text": htmlErrors, "type": "error", html: true});
                                        }

                                    }
                                }); // end ajax

                            });
                        });

                    }

                });

            });
        });
    </script>
