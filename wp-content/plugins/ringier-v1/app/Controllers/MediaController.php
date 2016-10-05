<?php
namespace RVN\Controllers;

use RVN\Library\Images;
use RVN\Models\Posts;
use RVN\Models\Ships;

class MediaController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_media", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_media", [$this, "ajaxHandler"]);
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new MediaController();
        }

        return self::$instance;
    }

    /**
     * Ajax upload hình featured cho bài viết
     */
    public function ajaxUploadFeaturedImages($data)
    {
        $return = array(
            'status' => 'error',
            'message' => 'Đăng hình không thành công.'
        );
        if (isset($_FILES['featured_image']) && !empty($_FILES['featured_image']['name'])) {
            $post_id = intval($data['post_id']);
            if ($post_id) {
                $thumb_id_old = get_post_thumbnail_id($post_id);
                $objImages = Images::init();
                $result = $objImages->upload_image($post_id, $_FILES['featured_image']);
                if ($result['status'] == 'success') {
                    if ($thumb_id_old) {
                        wp_delete_attachment($thumb_id_old);
                    }
                    $size = sanitize_text_field(stripslashes($data['image_size']));
                    $thumb_id = get_post_thumbnail_id($post_id);
                    $image = wp_get_attachment_image_src($thumb_id, $size, TRUE);

                    $return = array(
                        'status' => 'success',
                        'message' => 'Đăng hình  thành công.',
                        'data' => $image,
                    );
                }
            }else{
                $objImages = Images::init();
                $return = $objImages->upload_image($post_id, $_FILES['featured_image']);
            }
        }
        return $return;
    }

    public function ajaxUploadImages($data)
    {
        $result = array(
            'status' => 'error',
            'message' => 'Đăng hình không thành công.',
        );
        if (isset($_FILES['featured_image']) && !empty($_FILES['featured_image']['name'])) {
            $file = $_FILES['featured_image'];
            $filename = $file['name'];
            $wp_filetype = wp_check_filetype($filename);
            $ext_allow = array('jpg', 'jpeg', 'png');
            $max_size = 2 * 1024 * 1024;
            if (in_array(strtolower($wp_filetype['ext']), $ext_allow)) {
                if ($file['size'] <= $max_size) {

                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    $attach_id = wp_insert_attachment($attachment, $filename);
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    $upload = wp_handle_upload($file, array('test_form' => false));
                    $attach_data = wp_generate_attachment_metadata($attach_id, $upload["file"]);
                    update_post_meta($attach_id, '_wp_attached_file', $attach_data["file"]);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    $img= wp_get_attachment_image_src($attach_id);
                    if($img) $img = array_shift($img);

                    $result = array(
                        'status' => 'success',
                        'message' => "Đăng hình thành công.",
                        'img' => $img,
                        'img_id' => $attach_id,
                    );
                }else {
                    $result['message'] = 'Kích thước hình quá hơn 2M.';
                }
            } else {
                $result['message'] = 'Định dạng hình ảnh không hợp lệ.';
            }
        }
        return $result;
    }
}
