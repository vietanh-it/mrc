<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quoc Vu
 * Date: 9/17/13
 * Time: 11:52 AM
 * To change this template use File | Settings | File Templates.
 */

function ringier_enqueue_script() {
    $version = "20150419";
    wp_enqueue_script( 'ringier-facebook', '/wp-content/plugins/ringier-social/facebook/js/facebook.js', array('jquery'), $version, true );
    wp_localize_script( 'ringier-facebook', 'ringier_social', array(
            'app_fb_id' => FACEBOOK_APP_ID,
            'channelUrl_fb' => WP_SITEURL,
            'admin_ajax_url' => admin_url("admin-ajax.php")
        )
    );
}
add_action( 'wp_enqueue_scripts', 'ringier_enqueue_script' );


add_action( 'wp_ajax_ringier_facebook_login', 'ringier_facebook_login' );
add_action( 'wp_ajax_nopriv_ringier_facebook_login', 'ringier_facebook_login' );
function ringier_facebook_login(){
    $error = 1;
    if( isset($_POST) ){
        /* @var wpdb $wpdb*/
        global $wpdb;
        $fb_id = $_POST["id"];
        if( $fb_id ){
            $query = "SELECT * FROM $wpdb->usermeta WHERE meta_key='facebook_id' AND meta_value='" . $fb_id . "'";

            $result = $wpdb->get_row($query);
            if( $result ){
                $user_id = $result->user_id;
                wp_set_auth_cookie($user_id);
                $error = 0;
            }
            else{ // chua co account
                //$username = $email = $_POST["email"];
                $username = $email = $_POST["email"];
                $facebook_url = $_POST["link"];
                $full_name = $_POST["name"];

                if( !empty($username) ){

                    /* TODO check && create user by account facebook */
                    $user_id = ringier_create_user($_POST);
                    if($user_id){
                        $_POST['user_id'] = $user_id;
                        ringier_update_user_info($_POST);
                        wp_set_auth_cookie($user_id);
                    }
                    $error = 0;

                    $args_mail = [
                        'first_name'      => $full_name,
                        'url_web'         => WP_SITEURL,
                    ];
                    sendEmailHTML($username,'Thank you for choosing us','account/welcome_email.html',$args_mail);

                    subscribeSendy(array(
                        'display_name' => $full_name,
                        'user_email' => $username,
                    ));
                }
            }
        }
    }

    $dataBack = array( "error" => 1 );
    if( $error == 0 ){
        $dataBack = array("error" => 0);
        $first_time = get_user_meta($user_id, 'facebook_first_time', true);
        if( $first_time == 1 ){
            $key = create_code_fb_firsttime();
            $dataBack["link"] = WP_SITEURL;
        }
    }
    echo json_encode( $dataBack ); exit();
}

function ringier_render_fb_login(){
    $html_login = "<a onclick=\"login_fb();\" href=\"javascript:void(0);\" title=\"Đăng nhập với Facebook\" class='login-fb radius-4' id='sign_in_fb'>Đăng nhập với Facebook</a>";
    return $html_login;
}
add_shortcode( 'ringier_button_fb_login', 'ringier_render_fb_login' );

function create_code_fb_firsttime(){
    $key = md5(wp_rand());
//    $_SESSION["facebook_firsttime"] = $key;
    return $key;
}

function ringier_check_exist_user_info($user_id){
    /* @var wpdb $wpdb*/
    global $wpdb;

    $query = 'SELECT count(*) as total FROM '.$wpdb->prefix.'user_info WHERE user_id=' . $user_id;
    $result = $wpdb->get_row($query);
    if( $result->total > 0 ){
        return true;
    }
    return false;
}

function ringier_create_user($data){
    $username = $email = $data["email"];
    $password = $email . rand();

    $user_id = username_exists($username);
    if( !$user_id ){
        $user_id = email_exists( $email );
        if( !$user_id ){
            $user_id = wp_create_user( $username, $password, $email );
            $creds = array(
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => FALSE
            );

            // TODO: Send email welcome
            // sendEmailHTML($username, 'Activate your account', 'account/welcome_email.html');
           // $user = wp_signon($creds, FALSE);
        }
    }
    return $user_id;
}

function ringier_update_user_info($data){
    /* @var wpdb $wpdb*/
    global $wpdb;

    $user_id = $data['user_id'];
    $fb_id = $data["id"];
    $facebook_url = $data["link"];
    $facebook_avatar = "https://graph.facebook.com/$fb_id/picture";

    $full_name = $data["name"];
    $gender = strtolower($data["gender"]);
    $birthday = (!empty($data['birthday']))? date("Y", strtotime($data["birthday"])) :"";
    $access_token = $data["access_token"];

    $archive_fb = array(
        'facebook_id' =>  $fb_id,
        'facebook_url' => $facebook_url,
        'facebook_avatar' => $facebook_avatar,
        'facebook_access_token' => $access_token
    );

    $data_user = array(
        'ID' => $user_id,
        'display_name' => $full_name,
        'user_url' => $facebook_url
    );
    wp_update_user( $data_user);
    $flag_exist = ringier_check_exist_user_info($user_id);
    if( $flag_exist ){
        $wpdb->update(
            $wpdb->prefix. 'user_info',
            array(
//                'user_id'       => $user_id,
                'gender'        => $gender,
                'birth_year'      => $birthday,
                'avatar'        => $facebook_avatar,
                'nationality'        => "noset",
                'update_at'    => current_time('mysql')
            ),
            array('user_id' => $user_id)
        );
    }else{
        $wpdb->insert(
            $wpdb->prefix. 'user_info',
            array(
                'user_id'       => $user_id,
                'gender'        => $gender,
                'birth_year'      => $birthday,
                'avatar'        => $facebook_avatar,
                'nationality'        => "noset",
                'update_at'    => current_time('mysql')
            )
        );
    }
    /**/

    add_user_meta($user_id, 'facebook_first_time', 1, true);
    add_user_meta($user_id, 'facebook_id', $fb_id, true);

    return true;
}