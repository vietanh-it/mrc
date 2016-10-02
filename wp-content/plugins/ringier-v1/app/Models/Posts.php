<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Posts
{
    protected $_wpdb;
    protected $_table_post_info;

    private static $instance;

    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;

        $this->_table_post_info = $wpdb->prefix . "post_info";
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
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
    public function getList($params = [])
    {
        $args_default = [
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 24,
            'paged'          => 1,
            'orderby'        => 'ID',
            'order'          => 'DESC',
            'is_cache'       => true,
            'is_paging'      => false,
        ];

        if ($params) {
            $args = array_merge($args_default, $params);
        }
        else {
            $args = $args_default;
        }
        //var_dump($args);
        $cacheId = __CLASS__ . 'getPostBy' . md5(serialize($args));

        if ($args['is_cache']) {
            $result = wp_cache_get($cacheId);
        }
        else {
            $result = false;
        }

        if ($result == false) {
            $query = new \WP_Query($args);
            //var_dump($query->request);
            $data = $query->posts;
            $total = $query->found_posts;

            if ($data) {
                foreach ($data as &$val) {
                    $val = $this->getInfo($val);
                }
            }

            $result = [
                'data'  => $data,
                'total' => $total,
            ];

            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
            //set_cache_tag($cacheId, 'getPostBy');
        }

        if (!empty($args['is_paging'])) {
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
    public function getInfo($post)
    {
        if (is_numeric($post)) {
            $post = get_post($post);
        }

        $cacheId = __CLASS__ . 'getInfo' . $post->ID;
        $result = wp_cache_get($cacheId);
        if ($result == false) {

            $objImages = Images::init();
            $post->images = $objImages->getPostImages($post->ID,
                ['thumbnail', 'featured', 'small', 'full', 'widescreen']);
            $post->permalink = get_permalink($post->ID);
            if (empty($post->post_excerpt)) {
                $post->post_excerpt = cut_string_by_char(150, strip_tags($post->post_content));
            }

            $query = 'SELECT * FROM ' . $this->_table_post_info . ' as pi 
            WHERE pi.object_id = ' . $post->ID;
            $post_info = $this->_wpdb->get_row($query);
            $post = (object)array_merge((array)$post, (array)$post_info);

            $objGallery = Gallery::init();
            $gallery = $objGallery->getGalleryBy($post->ID);
            $post->gallery = $gallery;

            $result = $post;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
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
    private function _set_paging($data, $total, $limit, $page)
    {
        global $wp_query;
        $wp_query->posts = $data;
        $wp_query->is_paged = ($page >= 1) ? true : false;
        $wp_query->found_posts = $total;
        $wp_query->max_num_pages = ceil($total / $limit);
    }

    public function getPostBySlug($slug, $post_type)
    {
        $args = [
            'name'        => $slug,
            'post_type'   => $post_type,
            'numberposts' => 1
        ];
        $my_posts = get_posts($args);
        if (!empty($my_posts[0])) {
            $my_posts = array_shift($my_posts);
        }

        return $my_posts;
    }

    /**
     * Lay bai viet nhap
     *
     * @param $user_id
     * @param $post_type
     * @return array|null|object|void|\WP_Post
     */
    public function getPostDraft($user_id, $post_type)
    {
        $query = "SELECT * FROM " . $this->_wpdb->posts . " WHERE post_status='auto-draft' AND post_author=$user_id AND post_type='" . $post_type . "'";
        $result = $this->_wpdb->get_row($query);
        if (empty($result)) {
            $post_id = wp_insert_post(
                [
                    'post_title'  => 'Auto Draft',
                    'post_author' => $user_id,
                    'post_status' => 'auto-draft',
                    'post_type'   => $post_type,
                    'post_date'   => current_time('mysql')
                ]
            );
            $result = get_post($post_id);
        }
        return $result;
    }

}
