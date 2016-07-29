<?php
namespace RVN\Models;

class Gallery
{
    protected      $_wpdb;
    protected      $_table_photos;

    private static $instance;

    /**
     * Location constructor.
     *
     */
    protected function __construct()
    {
        global $wpdb;
        $this->_wpdb           = $wpdb;
        $this->_table_photos     = $wpdb->prefix . "post_photos";
    }

    public static function init()
    {
        if ( ! isset( self::$instance )) {
            self::$instance = new Gallery();
        }

        return self::$instance;
    }


    public function getGalleryBy($post_id){

        $result = array();
        $query = 'SELECT * FROM '.$this->_table_photos .' WHERE post_id = '.$post_id;
        $list = $this->_wpdb->get_results($query);

        if($list){
            foreach ($list as $v){
                $image = unserialize($v->images);
                $image_thumbnail = WP_SITEURL.'/'.$image['url'].'/'.$image['thumbnail'];
                $image_featured = WP_SITEURL.'/'.$image['url'].'/'.$image['featured'];

                $v->featured = $image_featured;
                $v->thumbnail = $image_thumbnail;
                $result[] = $v;
            }
        }
        return $result;
    }
}