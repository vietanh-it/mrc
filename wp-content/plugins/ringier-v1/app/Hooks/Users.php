<?php
namespace RVN\Hooks;

class Users
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Users();
        }

        return self::$instance;
    }

    function __construct()
    {
        // hook login redirect
        //add_action( 'wp_login', array($this,'user_login'));

        // dang ky user
        add_filter('registration_errors', [$this, 'registration_errors']);
        add_action('register_new_user', [$this, 'register_new_user']);

        // update profile
        add_action('personal_options_update', [$this, 'personal_options_update']);

        add_action('user_profile_update_errors', [$this, 'tml_user_profile_update_errors']);


        /*add_action( 'validate_password_reset', function($errors, $user){
            $errors->remove( 'password_reset_mismatch' );
        });*/

        /*add_action('send_password_change_email', function(){
            return false;
        });*/

    }

    function registration_errors($errors)
    {
        if (empty($_POST['last_name'])) {
            $errors->add('empty_last_name', 'Please enter your last name.');
        }

        if (empty($_POST['first_name'])) {
            $errors->add('empty_first_name', 'Please enter your first name.');
        }
        /*if (empty($_POST['nationality'])) {
            $errors->add('empty_location', 'Please choice your nationality');
        }
        if (empty($_POST['phone'])) {
            $errors->add('empty_phone', 'Please enter your phone number');
        }*/
        //PASSWORD & RE-PASSWORD
        if (empty($_POST['pass1'])) {
            $errors->add('empty_pass', 'Please enter a password.');
        }
        if (empty($_POST['pass2'])) {
            $errors->add('empty_pass2', 'Please enter your password confirm.');
        }
        else {
            if ($_POST['pass1'] != $_POST['pass2']) {
                $errors->add('pass_not_match', 'Password confirm not right.');
            }
        }

        //EMAIL - USERNAME
        $username = $_POST['user_email'];
        if (empty($username)) {
            $errors->add('empty_email_address', 'Please enter your email');
        }
        else {
            if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $errors->add('valid_email_address', 'Email not valid.');
            }
        }

        $errors->remove('empty_email');
        $errors->remove('empty_username');
        $errors->remove('username_exists');

        //$errors->remove( 'recaptcha' );

        return $errors;
    }

    function register_new_user($user_id)
    {
        if (!is_wp_error($user_id)) {
            $objMember = \RVN\Models\Users::init();

            $data = $_POST;
            $data['user_id'] = $user_id;
            $data['password'] = $_POST['pass1'];

            $objMember->saveUser($data);
        }
    }

    function personal_options_update($user_id)
    {
        $objMember = \RVN\Models\Users::init();

        $data = $_POST;
        $data['user_id'] = $user_id;
        $objMember->saveUser($data);
    }

    function tml_user_profile_update_errors($errors, $update, $user)
    {
        $errors->remove('nickname');
    }

}