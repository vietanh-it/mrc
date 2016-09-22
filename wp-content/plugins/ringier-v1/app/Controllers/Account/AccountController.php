<?php
namespace RVN\Controllers\Account;

use RVN\Controllers\_BaseController;
use RVN\Models\Location;
use RVN\Models\Posts;
use RVN\Models\Users;

class AccountController extends _BaseController
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_account", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_account", [$this, "ajaxHandler"]);
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new AccountController();
        }
        return self::$instance;
    }

    public function loginForm($template, $theme_my_login)
    {

        return view('account/login', compact('template', 'theme_my_login'));
    }


    public function register($template, $theme_my_login)
    {
        $Location = Location::init();
        $country_list = $Location->getCountryList();

        return view('account/register', compact('template', 'theme_my_login', 'country_list'));
    }

    public function resetpass($template)
    {

        return view('account/resetpass', compact('template'));
    }


    public function lostpassword($template, $theme_my_login)
    {

        return view('account/lost-password', compact('template', 'theme_my_login'));
    }

    public function profile($template, $profileuser)
    {

        return view('account/profile', compact('template', 'profileuser'));
    }

    public function ajaxConnectEmail($data)
    {

        if (!empty($data['c_email'])) {
            if (is_email($data['c_email'])) {

                $name = !empty($data['c_name']) ? $data['c_name'] : $data['c_email'];
                $args_sub = array(
                    'user_email' => $data['c_email'],
                    'display_name' => $name,
                );

                subscribeSendy($args_sub);

                // Add user to newsletter list
                global $wpdb;
                if (empty($wpdb->get_row("SELECT * FROM {$wpdb->prefix}newsletter WHERE email = '{$data['c_email']}'"))) {
                    $wpdb->insert($wpdb->prefix . 'newsletter', [
                        'name' => $name,
                        'email' => $data['c_email'],
                        'created_at' => current_time('mysql')
                    ]);
                }

                $hash_email = mrcEncrypt($data['c_email']);
                $args_mail = [
                    'first_name' => $name,
                    'url_web' => WP_SITEURL,
                    'url_unsubscribe' => WP_SITEURL . '/unsubscribe?p=' . $hash_email,
                ];
                sendEmailHTML($data['c_email'], 'Thank you for your newsletter sign-up
', 'normal_user/newsletter_sign_up.html', $args_mail);

                $result = array(
                    'status' => 'success',
                    'message' => 'Connect email success.',
                );
            } else {
                $result = array(
                    'status' => 'error',
                    'message' => array('Invalid email.'),
                );
            }
        } else {
            $result = array(
                'status' => 'error',
                'message' => array('Please enter your email.'),
            );
        }

        return $result;
    }

    public function ajaxReferFriend($data)
    {
        if (is_user_logged_in()) {
            if ($data['email_friend']) {
                if (is_email($data['email_friend'])) {
                    if (email_exists($data['email_friend'])) {
                        $result = array(
                            'status' => 'error',
                            'message' => array('This email is already a member.'),
                        );
                    } else {
                        $list_email_refer = get_user_meta(get_current_user_id(), 'email_refer');
                        if (in_array($data['email_friend'], $list_email_refer)) {
                            $result = array(
                                'status' => 'error',
                                'message' => array('You invited this email already..'),
                            );
                        } else {
                            $email = $data['email_friend'];
                            $code = md5(time()) . '_' . get_current_user_id();

                            $url = wp_registration_url() . '/?email=' . $email . '&code=' . $code . '&id=' . get_current_user_id();

                            $args_mail = array(
                                'first_name' => $data['email_friend'],
                                'url_register' => $url,
                            );
                            sendEmailHTML($data['email_friend'], 'Register invitation
', 'normal_user/refer_friend.html', $args_mail);

                            add_user_meta(get_current_user_id(), 'email_refer', $data['email_friend']);
                            add_user_meta(get_current_user_id(), 'code_refer', $code);

                            $result = array(
                                'status' => 'success',
                                'message' => 'Refer friend success.',
                                'url' => $url,
                            );
                        }
                    }

                } else {
                    $result = array(
                        'status' => 'error',
                        'message' => array('Email not email.'),
                    );
                }
            } else {
                $result = array(
                    'status' => 'error',
                    'message' => array('Please enter your email.'),
                );
            }
        } else {
            $result = array(
                'status' => 'error',
                'message' => array('Please login or register.'),
            );
        }


        return $result;

    }

    public function confirm_change_email(){
        $return = array(
            'status' => 'error',
            'message' => 'An error, please check your email and try again.',
        );
        if(!empty($_GET['id']) && !empty($_GET['code'])){
            $code = $_GET['code'];
            $user_id = $_GET['id'];

            $User = Users::init();
            $update = $User->updateUserEmail($user_id,$code);
            if($update){
                $return = array(
                    'status' => 'success',
                    'message' => 'Change email success, please login again.',
                );
            }
        }
        return view('account/confirm-change-email', compact('return'));
    }

    public function userInfo($user_id)
    {
        $objUser = Users::init();
        $return = array(
            'status' => ''
        );
        $data = array();

        if (!empty($_POST) && is_user_logged_in()) {
            $data['user_id'] = get_current_user_id();
            $data['update_at'] = current_time('mysql');
            if (isset($_POST['first_name']) && isset($_POST['last_name'])) {
                if (empty($_POST['first_name']) && empty($_POST['last_name'])) {
                    $return['status'] = 'error';
                    $return['message'][] = 'Please enter first name or last name.';
                } else {
                    $display_name = $_POST['first_name'] . ' ' . $_POST['last_name'];
                    wp_update_user(array(
                        "ID" => $data['user_id'],
                        "first_name" => $_POST['first_name'],
                        "last_name" => $_POST['last_name'],
                        "display_name" => $display_name,
                    )); 
                }
            }
            if (isset($_POST['birthday'])) {
                $data['birthday'] = date('Y-m-d', strtotime($_POST['birthday']));
            }
            if (isset($_POST['country'])) {
                $data['country'] = $_POST['country'];
            }
            if (isset($_POST['address'])) {
                $data['address'] = $_POST['address'];
            }
            if (isset($_POST['gender'])) {
                $data['gender'] = $_POST['gender'];
            }

            if (isset($_POST['passport_id'])) {
                $data['passport_id'] = $_POST['passport_id'];
            }
            if (isset($_POST['valid_until'])) {
                if (empty($_POST['valid_until'])) {
                    $return['status'] = 'error';
                    $return['message'][] = 'Please enter valid until.';
                } else {
                    $data['valid_until'] = date('Y-m-d', strtotime($_POST['valid_until']));;
                }
            }
            if (isset($_POST['date_of_issue'])) {
                $data['date_of_issue'] = date('Y-m-d', strtotime($_POST['date_of_issue']));;
            }
            if (isset($_POST['nationality'])) {
                $data['nationality'] = $_POST['nationality'];
            }

            $change_email = false;
            if (isset($_POST['a_email']) && isset($_POST['a_password'])) {
                $user_current = wp_get_current_user();

                $ags["ID"] = $data['user_id'];
                if (empty($_POST['a_email'])) {
                    $return['status'] = 'error';
                    $return['message'][] = 'Please enter your email.';
                } else {
                    if (!is_email($_POST['a_email'])) {
                        $return['status'] = 'error';
                        $return['message'][] = 'Email not match.';
                    } else {
                        if($_POST['a_email'] != $user_current->user_email){
                            if (email_exists($_POST['a_email'])) {
                                $return['status'] = 'error';
                                $return['message'][] = 'This email already exists.';
                            } else {
                                $code_change_email = md5(time().$_POST['a_email']);
                                $url_cf = WP_SITEURL.'/confirm-change-email/?id='.$user_current->ID.'&code='.$code_change_email;
                                if(!empty(get_user_meta($user_current->ID,'code_change_email',true))){
                                    update_user_meta($user_current->ID,'code_change_email',$code_change_email);
                                }else{
                                    add_user_meta($user_current->ID,'code_change_email',$code_change_email);
                                }

                                if(!empty(get_user_meta($user_current->ID,'new_email',true))){
                                    update_user_meta($user_current->ID,'new_email',$_POST['a_email']);
                                }else{
                                    add_user_meta($user_current->ID,'new_email',$_POST['a_email']);
                                }

                                $args_mail = array(
                                    'display_name' => $user_current->display_name,
                                    'confirm_url' => $url_cf,
                                );

                                $change_email = true;
                                sendEmailHTML($user_current->user_email,'Your email address was recently changed.','account/email_address_change.html', $args_mail);
                            }
                        }
                    }
                }

                if (empty($_POST['a_password'])) {
                    $return['status'] = 'error';
                    $return['message'][] = 'Please enter your password.';
                } else {
                    $check_pass = wp_check_password( $_POST['a_password'], $user_current->user_pass, $user_current->ID);
                    if(!$check_pass){
                        $ags['user_pass'] = $_POST['a_password'];
                        $args_mail = array(
                            'display_name' => $user_current->display_name,
                        );
                        sendEmailHTML($user_current->user_email,'Your password was recently changed.','account/password_change.html', $args_mail);
                    }
                }

                $update_ac = wp_update_user($ags);
                if (!$update_ac) {
                    $return['status'] = 'error';
                    $return['message'][] = 'An error, please try again.';
                }
            }

            if ($return['status'] != 'error') {
                $update = $objUser->saveUserInfo($data);
                if ($update) {
                    $return['status'] = 'success';
                    if($change_email){
                        $return['message'][] = 'Update profile success. Please check your email to complete the email change.';
                    }else{
                        $return['message'][] = 'Update profile success.';
                    }
                } else {
                    $return['status'] = 'error';
                    $return['message'][] = 'An error, please try again.';
                }
            }

        }

        $Location = Location::init();
        $country_list = $Location->getCountryList();
        $user_info = $objUser->getUserInfo($user_id);

        return view('account/profile', compact('user_info', 'country_list', 'return'));
    }

    public function ajaxSendContact($data)
    {
        $result = array(
            'message' => array('An error, please try again.'),
            'status' => 'error',
        );
        if (!empty($data)) {
            $html = '
                <p><b>Full name :</b> ' . $data["contact_full_name"] . '</p>
                <p><b>E-mail :</b> ' . $data["contact_email"] . '</p>
                <p><b>Phone number :</b> ' . $data["contact_phone"] . '</p>
                <p><b>Country :</b> ' . $data["contact_country"] . '</p>
                <p><b>Message subject :</b> ' . $data["contact_subject"] . '</p>
                <p><b>Message :</b> ' . $data["contact_message"] . '</p>
            ';

            $Post = Posts::init();
            $insert = wp_insert_post(array('post_title' => $data["contact_full_name"] . ' - ' . $data["contact_subject"],
                'post_author' => get_current_user_id() ? get_current_user_id() : 0,
                'post_status' => 'pending',
                'post_content' => $html,
                'post_type' => 'contact',
                'post_date' => current_time('mysql')));

            if ($insert) {
                $result = array(
                    'message' => 'Send contact success, thank you.',
                    'status' => 'success',
                );

                wp_mail('vietanhtran.it@gmail.com', '[Contact] ' . $data["contact_full_name"] . ' - ' . $data["contact_subject"], $html);
            }

        }

        return $result;
    }


    public function ajaxGoogleLogin($data)
    {
        $result= array(
            'status' => 'error',
            'message' => 'An error, please try again!'
        );
        if(!empty($data['user_data'])){
            $data = $data['user_data'];

            // TODO: save user
            if(!empty($data['emails'][0]['value'])){
                $email = $data['emails'][0]['value'];
                $user_id = username_exists($email);
                $first_name = !empty($data['name']['familyName']) ? $data['name']['familyName'] : '';
                $last_name = !empty($data['name']['givenName']) ? $data['name']['givenName'] : '';

                if( !$user_id ){
                    $user_id = email_exists( $email );
                    if( !$user_id ){
                        $password = $email . rand();
                        $user_id = wp_create_user( $email, $password, $email );

                        $args_mail = [
                            'first_name'      => !empty($first_name) ? $first_name : $data['displayName'],
                            'url_web'         => WP_SITEURL,
                        ];
                        sendEmailHTML($email,'Thank you for choosing us','account/welcome_email.html',$args_mail);

                        subscribeSendy(array(
                            'display_name' => $data['displayName'],
                            'user_email' => $email,
                        ));
                    }
                }


                if(!empty($user_id)){
                    wp_set_auth_cookie($user_id);

                    wp_update_user(array(
                        "ID" => $user_id,
                        "display_name" => $data['displayName'],
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                    ));

                    $User = Users::init();
                    $User->saveUserInfo(array(
                        'user_id' =>$user_id,
                        'avatar' => $data['image']['url'],
                        'gender' => $data['gender'],
                    ));

                    $result= array(
                        'status' => 'success',
                        'message' => 'Login success.'
                    );
                }
            }
        }

        return $result;
    }
}

?>