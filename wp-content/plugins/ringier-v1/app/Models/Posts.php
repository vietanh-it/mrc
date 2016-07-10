<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;
use RVN\Library\Images;

class Posts {
    protected      $_wpdb;
    protected      $_table_post_info;

    private static $instance;

    /**
     * Users constructor.
     */
    function __construct(){
        global $wpdb;
        $this->_wpdb           = $wpdb;

        $this->_table_post_info = $wpdb->prefix . "post_info";
    }

    public static function init()
    {
        if ( ! isset( self::$instance )) {
            self::$instance = new Posts();
        }

        return self::$instance;
    }

    /**
     * Lấy bài viết dựa theo thông số truyền vào
     * Document: https://codex.wordpress.org/Class_Reference/WP_Query#Parameters
     *
     * @param array $args
     * @return array|bool
     */
    public function getList($args = array()){
        $args_default = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 20,
            'paged' => 1,
            'orderby' => 'ID',
            'order'   => 'DESC',
            'is_cache' => true,
            'is_paging' => false,
        );

        $args = array_merge($args_default,$args);
        //var_dump($args);
        $cacheId = __CLASS__ . 'getPostBy' . md5(serialize($args));

        if ($args['is_cache']) {
            $result = wp_cache_get($cacheId);
        } else {
            $result = false;
        }

        if ($result == FALSE) {
            $query = new \WP_Query( $args );
            //var_dump($query->request);
            $data = $query->posts;
            $total = $query->found_posts;

            if ($data) {
                foreach ($data as &$val) {
                    $val = $this->getInfo($val);
                }
            }

            $result = array(
                'data' => $data,
                'total' => $total,
            );

            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
            //set_cache_tag($cacheId, 'getPostBy');
        }

        if($args['is_paging']) {
            $this->_set_paging($result['data'], $result['total'], $args['posts_per_page'], $args['paged']);
        }

        return $result;
    }

    /**
     * Lấy chi tiết thêm của bài viết, có thể truyền post_id hoặc object post.
     *
     * @param $post
     * @return mixed
     */
    public function getInfo($post){
        if(is_numeric($post)){
            $post = get_post($post);
        }

        $cacheId = __CLASS__ . 'getInfo' . $post->ID;
        $result = wp_cache_get($cacheId);
        if($result == false){

            $objImages = Images::init();
            $post->images = $objImages->getPostImages($post->ID,array('thumbnail','thumbnail-mobile','feature-image','feature-image-mobile','small'));

            $love = get_post_meta($post->ID, 'love', true);
            $post->love = intval($love);
            $post->permalink = get_permalink($post->ID);

            switch ($post->post_type){
                case 'blog' :
                case 'topic' :
                    break ;
                case 'post' :
                    break;
            }

            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $post;
    }

    /**
     * Update post
     *
     * @param $data
     * @return bool
     */
    public function save($data){
        if( ($data['post_id']) ){

            $content = sanitize_post_field('post_content',$data['post_content'],$data['post_id'],'edit');
            //$content = strip_tags($data['post_content'],'<br />');
            $post_detail = array(
                'ID' => $data['post_id'],
                'post_title' => $data['post_title'],
                'post_name' => sanitize_title($data['post_title']),
                'post_content' => $content,
                'post_excerpt' => $data['post_excerpt'],
                'post_status' => $data['post_status'],
                'post_author' => $data['post_author'],
                'post_date' => current_time('mysql'),
            );

            foreach ($post_detail as $k => $v){
                if(empty($post_detail[$k])){
                    unset($post_detail[$k]);
                }
            }

            //var_dump($post_detail);

            switch ($data['post_type']){
                case 'blog' :
                case 'topic' :
                    break ;
                case 'post' :
                    break;
            }

            $result = wp_update_post($post_detail);

        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Set paging for wp_paging
     * @param array $data
     * @param int $total
     * @param int $limit
     * @param int $page
     */
    private function _set_paging($data, $total, $limit, $page){
        global $wp_query;
        $wp_query->posts = $data;
        $wp_query->is_paged = ($page >= 1) ? true : false;
        $wp_query->found_posts = $total;
        $wp_query->max_num_pages = ceil($total / $limit);
    }


    /**
     * Lay bai viet nhap
     *
     * @param $user_id
     * @param $post_type
     * @return array|null|object|void|\WP_Post
     */
    public function get_post_draft($user_id, $post_type)
    {
        $query = "SELECT * FROM ".$this->_wpdb->posts." WHERE post_status='auto-draft' AND post_author=$user_id AND post_type='" . $post_type . "'";
        $result = $this->_wpdb->get_row($query);
        if(empty($result)){
            $post_id = wp_insert_post(
                array(
                    'post_title' => 'Auto Draft',
                    'post_author' => $user_id,
                    'post_status' => 'auto-draft',
                    'post_type' => $post_type,
                    'post_date' => current_time('mysql')
                )
            );
            $result = get_post($post_id);
        }
        return $result;
    }

    /**
     * Lấy bài viết theo từ khóa
     *
     * @param $tag
     * @param $limit
     * @return mixed
     */
    public function getPostsByTag($tag, $limit)
    {
        $cacheId = __CLASS__ . __METHOD__ . $tag . $limit;
        $result = wp_cache_get($cacheId);
        if (false === $result) {
            $args = array(
                'posts_per_page' => $limit,
                'post_status' => 'publish',
                'tag' => $tag
            );

            $result = get_posts($args);

            foreach ($result as &$post) {
                /*$post->filter = 'sample';
                $post->permalink = get_permalink($post);

                $thumbnail_id = get_post_thumbnail_id($post->ID);
                $image = wp_get_attachment_image_src($thumbnail_id, $size, true);
                $post->image_thumbnail = $image[0];*/

                $post = $this->getInfo($post);
            }

            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }
        return $result;
    }
}
