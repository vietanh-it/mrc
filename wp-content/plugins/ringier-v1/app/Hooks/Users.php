<?php
namespace RVN\Hooks;

class Users {
    private static $instance;

    public static function init()
    {
        if ( ! isset( self::$instance )) {
            self::$instance = new Users();
        }

        return self::$instance;
    }

    function __construct(){
        // hook login redirect
        //add_action( 'wp_login', array($this,'user_login'));

        // dang ky user
        add_filter( 'registration_errors', array($this,'registration_errors') );
        add_action( 'register_new_user', array($this,'register_new_user') );

        // update profile
        add_action( 'personal_options_update', array($this,'personal_options_update') );

        add_action( 'user_profile_update_errors', array($this,'tml_user_profile_update_errors'));


        /*add_action( 'validate_password_reset', function($errors, $user){
            $errors->remove( 'password_reset_mismatch' );
        });*/

        add_action('send_password_change_email', function(){
            return false;
        });

    }

    function registration_errors( $errors ) {
        if ( empty( $_POST['full_name'] ) ) {
            $errors->add( 'empty_full_name', 'Please enter your full name.' );
        }
        if ( empty( $_POST['location'] ) ) {
            $errors->add( 'empty_location', 'Please choice your nationality' );
        }
        if ( empty( $_POST['phone'] ) ) {
            $errors->add( 'empty_phone', 'Please enter your phone number' );
        }

        //EMAIL - USERNAME
        $username = $_POST['user_login'];
        if ( empty( $username ) ) {
            $errors->add( 'empty_email_address', 'Please enter your email' );
        } else if ( ! filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
            $errors->add( 'valid_email_address', 'Email not valid.' );
        }

        //PASSWORD & RE-PASSWORD
        if ( empty( $_POST['pass1'] ) ) {
            $errors->add( 'empty_pass', 'Please enter a password.' );
        }
        if ( empty( $_POST['pass2'] ) ) {
            $errors->add( 'empty_pass2', 'Please enter your password confirm.' );
        }

        $errors->remove( 'empty_email' );
        $errors->remove( 'empty_username' );

        return $errors;
    }

    function register_new_user( $user_id ) {
        var_dump($user_id);
        global $wpdb;
        if ( ! is_wp_error( $user_id ) ) {
            $objMember = '';

            var_dump($_POST);
            //$objMember->UserRegister($_POST);
        }
    }

    function personal_options_update( $user_id ) {

        $objMember = '';
        //$user_info = $objMember->getUserInfo($user_id);
        //$objMember->userUpdate($_POST);

    }

    function tml_user_profile_update_errors($errors, $update, $user){
        $errors->remove( 'nickname' );
    }

}