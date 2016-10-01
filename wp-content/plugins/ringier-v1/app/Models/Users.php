<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

class Users
{
    protected $_wpdb;
    protected $_table_info;
    protected $_table_user_refer;

    private static $instance;

    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;

        $this->_table_info = $wpdb->prefix . "user_info";
        $this->_table_user_refer = $wpdb->prefix . "user_refer";
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Users();
        }

        return self::$instance;
    }

    /**
     * Tao moi user
     *
     * @param $data
     * @return bool | array
     */
    public function saveUser($data)
    {
        $result = false;

        if(!empty($data['user_id'])){
            $user_id = $data['user_id'];
        }else{
            $user_id = 0;
        }

        if($user_id){
            wp_update_user(array(
                "ID" => $user_id,
                "display_name" => $data['first_name'] . ' '.$data['last_name'],
                "first_name" => $data['first_name'],
                "last_name" => $data['last_name'],
            ));

            if($data['password']){
                wp_set_password( $data['password'], $user_id );
            }

            //sendy
            subscribeSendy(array(
                'display_name' => $data['first_name'],
                'user_email' => $data['user_email'],
            ));

            $query = 'SELECT * FROM '.$this->_table_info .' WHERE user_id = '.$user_id;
            $user_info = $this->_wpdb->get_row($query);

            $data_info = array(
                /*'phone' => $_POST['phone'],
                'country' => $_POST['country'],*/
                //'address' => $_POST['address'],
                'update_at' => current_time('mysql'),
            );
            if(!empty($data['email_refer']) && !empty($data['code_refer']) && !empty($data['user_refer_id'])){
                $chekc_refer = $this->getUserRefer($data['user_refer_id'],$data['email_refer'],$data['code_refer'],'pending');
                if(!empty($chekc_refer)){
                    // set is_refer cho nguoi dc moi
                    $data_info['is_refer'] = 1;

                    //cong them 1 cho is_refer cua user mời
                    $user_refer_info = $this->getUserInfo($data['user_refer_id']);
                    $is_refer = $user_refer_info->is_refer;
                    $new_refer  = 1;
                    if(!empty($is_refer)){
                        $new_refer = intval($is_refer + 1);
                    }
                    $this->_wpdb->update($this->_table_info,array('is_refer' => $new_refer),array('user_id' => $data['user_refer_id']));

                    // chuyen email da refer qua trang thai thanh cong
                    $this->updateUserRefer(array(
                        'id' => $chekc_refer->id,
                        'status' => 'publish',
                        'updated_at' => current_time('mysql'),
                    ));
                }

            }
            if(!$user_info){
                $data_info['user_id'] = $user_id;
                $this->_wpdb->insert($this->_table_info,$data_info);
            }else{
                $this->_wpdb->update($this->_table_info,$data_info,array('user_id' => $user_id));
            }
        }

        return $result;
    }


    /**
     * Lay thong tin user trong bang user info
     *
     * @param $user_id
     * @return array|bool|false|mixed|null|object|void
     */
    public function getUserInfo($user_id)
    {

        $user = get_user_by('id',$user_id);

        if($user){
            $query = 'SELECT * FROM '.$this->_table_info .' WHERE user_id = '.$user_id;

            $user_info = $this->_wpdb->get_row($query);

            if($user_info){
                $user =  (object) array_merge((array) $user->data, (array) $user_info);
            }
        }

        return $user;
    }

    public function saveUserInfo($data){
        $result = false;
        if(!empty($data['user_id'])){
            $query = 'SELECT * FROM '.$this->_table_info .' WHERE user_id = '.$data['user_id'];

            $user_info = $this->_wpdb->get_row($query);
            if(!empty($user_info)){
                $result = $this->_wpdb->update($this->_table_info,$data,array('user_id' => $data['user_id']));
            }else{
                $result = $this->_wpdb->insert($this->_table_info,$data);
            }
        }

        return $result;
    }

    public function updateUserEmail($user_id,$code){
        $result = false;

        $code_cr = get_user_meta($user_id,'code_change_email',true);
        if(!empty($code_cr) && $code_cr == $code){
            $new_email  = get_user_meta($user_id,'new_email',true);
            if(!empty($new_email)){

                $user = $this->getUserInfo($user_id);

                $ags["user_email"] = $new_email;
                $ags["user_login"] = $new_email;
                $ags["user_nicename"] = sanitize_title($new_email);

                $update =  $this->_wpdb->update($this->_wpdb->users,$ags,array('ID' => $user_id));
                if($update){
                    //sendy
                    subscribeSendy(array(
                        'display_name' => $user->display_name,
                        'user_email' => $new_email,
                    ));

                    delete_user_meta($user_id,'code_change_email');
                    delete_user_meta($user_id,'new_email');
                    $result = true;
                }
            }
        }

        return $result;
    }

    public function getListUserReferBY($user_id,$status = ''){
        if(empty($user_id)){
            return false;
        }else{
            $where = '';
            if(!empty($status)){
                $where .= ' AND status = "'.$status.'"';
            }
            $query = 'SELECT * FROM '.$this->_table_user_refer .' WHERE user_id = '.$user_id .$where;
            return $this->_wpdb->get_results($query);
        }
    }

    public function getUserRefer($user_id,$email_refer,$code='',$status = ''){
        if(empty($user_id) or empty($email_refer)){
            return false;
        }else{
            $where = '';
            if(!empty($status)){
                $where .= ' AND status = "'.$status.'"';
            }
            if(!empty($email_refer)){
                $where .= ' AND email_refer = "'.$email_refer.'"';
            }
            if(!empty($code)){
                $where .= ' AND code = "'.$code.'"';
            }
            $query = 'SELECT * FROM '.$this->_table_user_refer .' WHERE user_id = '.$user_id .$where;
            return $this->_wpdb->get_row($query);
        }
    }

    public function addUserRefer($args){
        if(empty($args['user_id'])){
            return false;
        }else{
            return $this->_wpdb->insert($this->_table_user_refer,$args);
        }
    }

    public function updateUserRefer($args){
        if(empty($args['id'])){
            return false;
        }else{
            $id = $args['id'];
            unset($args['id']);
            return $this->_wpdb->update($this->_table_user_refer,$args,array('id' => $id));
        }
    }
}
