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

    private static $instance;

    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;

        $this->_table_info = $wpdb->prefix . "user_info";
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
            if(!empty($data['is_refer'])  && !empty($data['email_refer']) && !empty($data['code_refer']) && !empty($data['user_refer_id'])){

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

                // xoa cac param da luu
                delete_user_meta($data['user_refer_id'],'email_refer',$data['email_refer']);
                delete_user_meta($data['user_refer_id'],'code_refer',$data['code_refer']);

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
}
