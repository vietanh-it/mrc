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
     * @return array
     */
    public function saveUser($data)
    {
        $result = false;

        if($data['user_id']){
            $user_id = $data['user_id'];
        }else{
            $user_id = 0;
        }

        if($user_id){
            wp_update_user(array(
                "ID" => $user_id,
                "display_name" => $data['full_name'],
            ));

            if($data['password']){
                wp_set_password( $data['password'], $user_id );

            }

            $query = 'SELECT * FROM '.$this->_table_info .' WHERE user_id = '.$user_id;
            $user_info = $this->_wpdb->get_row($query);

            $data_info = array(
                'phone' => $_POST['phone'],
                'nationality' => $_POST['nationality'],
                'address' => $_POST['address'],
                'update_at' => current_time('mysql'),
            );
            if(!$user_info){
                $data_info['user_id'] = $user_id;
                $this->_wpdb->insert($this->_table_info,$data_info);
            }else{
                $this->_wpdb->update($this->_table_info,$data_info,array('user_id' => $user_id));
            }
        }

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
}
