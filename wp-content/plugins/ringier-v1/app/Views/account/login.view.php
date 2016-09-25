<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
</div><!--close .main-content-->

<style>
    .main-content {
        display: none;
    }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4">
        <p style="text-align: center;font-style: italic" class="font-playfair">Welcome to the world of luxury
            cruises</p>
        <div class="text-center">
            Don't have an account? <a href="<?php echo wp_registration_url(); ?>" style="color: #999999;">Sign-up</a>
        </div>

        <div class="ctn-account">
            <div class="tml tml-login" id="theme-my-login<?php $template->the_instance(); ?>">
                <p style="margin: 25px 0 17px;color: black;font-size: 17px;text-align: center;font-family: 'Playfair Display', serif;">
                    Sign In with</p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <a href="javascript:void(0)" onclick="login_fb()" title="Login with facebook">
                            <img src="<?php echo VIEW_URL . '/images/login-facebook.png' ?>" alt="">
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6" style="text-align: right">
                        <a href="javascript:void(0)" title="Login with google" class="google-plus-signin">
                            <img src="<?php echo VIEW_URL . '/images/glogin.png' ?>" alt="">
                        </a>
                    </div>
                </div>

                <p class="line-account"><span>Or sign-in with your created account</span></p>
                <?php $template->the_errors(); ?>
                <form name="loginform" id="loginform<?php $template->the_instance(); ?>"
                      action="<?php $template->the_action_url('login', 'login_post'); ?>" method="post">
                    <p class="tml-user-login-wrap">
                        <!--<label for="user_login<?php /*$template->the_instance(); */ ?>"><?php
                        /*                    if ('username' == $theme_my_login->get_option('login_type')) {
                                                _e('Username', 'theme-my-login');
                                            } elseif ('email' == $theme_my_login->get_option('login_type')) {
                                                _e('E-mail', 'theme-my-login');
                                            } else {
                                                _e('Username or E-mail', 'theme-my-login');
                                            }
                                            */ ?></label>-->
                        <input type="text" name="log" id="user_login<?php $template->the_instance(); ?>"
                               class="input form-control" value="<?php $template->the_posted_value('log'); ?>"
                               size="20" placeholder="Your email address"/>
                    </p>

                    <p class="tml-user-pass-wrap">
                        <!--<label for="user_pass<?php /*$template->the_instance(); */ ?>"><?php /*_e('Password',
                        'theme-my-login'); */ ?></label>-->
                        <input type="password" name="pwd" id="user_pass<?php $template->the_instance(); ?>"
                               class="input form-control" value="" size="20" autocomplete="off"
                               placeholder="Your password"/>
                    </p>

                    <?php do_action('login_form'); ?>

                    <div class="tml-rememberme-submit-wrap">
                        <p class="tml-rememberme-wrap">
                            <input name="rememberme" type="checkbox"
                                   id="rememberme<?php $template->the_instance(); ?>" value="forever"/>
                            <label
                                for="rememberme<?php $template->the_instance(); ?>"><?php esc_attr_e('Remember Me',
                                    'theme-my-login'); ?></label>
                        </p>
                        <p class="tml-submit-wrap">
                            <a href="<?php echo WP_SITEURL . '/account/lostpassword/' ?>" class="font-playfair">Forgot
                                password</a>
                        </p>
                    </div>
                    <p class="tml-submit-wrap" style="width: 100%; text-align: center;float: left;margin: 0 0 60px;">
                        <input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>"
                               value="Sign in" class="bnt-primary"/>
                        <input type="hidden" name="redirect_to"
                               value="<?php $template->the_redirect_url('login'); ?>"/>
                        <input type="hidden" name="instance" value="<?php $template->the_instance(); ?>"/>
                        <input type="hidden" name="action" value="login"/>
                    </p>
                </form>
                <?php // $template->the_action_links(array('login' => false)); ?>
            </div>
        </div>

    </div>
</div>

<div><!--open close div of .main-content-->

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