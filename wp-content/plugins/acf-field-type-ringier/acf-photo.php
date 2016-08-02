<?php
define("VENDORS_POST_PHOTO", "mrc_post_photos");
class r_photo {
    protected $_options;

    function __construct($options=null){
        add_action( 'wp_ajax_r_photo_upload_custom_photo', array( $this, 'r_photo_upload_custom_photo' ) );
        //add_action( 'wp_ajax_nopriv_r_photo_upload_custom_photo', array( $this, 'r_photo_upload_custom_photo' ) );

        add_action( 'wp_ajax_r_photo_delete_photo', array( $this, 'r_photo_delete_photo' ) );
        //add_action( 'wp_ajax_nopriv_r_photo_delete_photo', array( $this, 'r_photo_delete_photo' ) );

        add_action( 'wp_ajax_r_photo_set_featured_photo', array( $this, 'r_photo_set_featured_photo' ) );
        //add_action( 'wp_ajax_nopriv_r_photo_delete_photo', array( $this, 'r_photo_delete_photo' ) );
    }

    function setOption($options){
        $this->_options = array(
            //'path'  => '',
            //'url'   => ''
            'param_name' => 'files',
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            'max_file_size' => 2*1024*1024, //2M
            'min_file_size' => 1024,
            'limit_upload_file' => 5,
            'image_versions' => array(
                /*'avatar' => array(
                    'width' => 100,
                    'height' => 100,
                    'crop' => true
                ),*/
                'thumbnail' => array(
                    'width' => 150,
                    'height' => 150,
                    'crop' => true
                ),
                'featured' => array(
                    'width' => 600,
                    'height' => 600,
                    'crop' => true
                ),
                'small' => array(
                    'width' => 500,
                    'height' => 281,
                    'crop' => true
                )
            )
        );

        if ($options) {
            $this->_options = $options + $this->_options;
        }
    }

    public function upload(){
        $dataBack = false;
        $path = $this->_options['path'];
        $url = $this->_options['url'];
        if( isset( $_FILES[$this->_options['param_name']] ) ){
            $files = $_FILES[$this->_options['param_name']];

            if( is_array($files['name']) ){ //upload multi images

                foreach( $files['name'] as $index => $filename ){
                    $dataBack[$index]['file_name'] = $filename;
                    $size = $files['size'][$index];
                    $tmp_name = $files['tmp_name'][$index];
                    if( $validate = $this->validate($filename, $size) === true ){

                        $new_filename = wp_unique_filename( $path, sanitize_title( pathinfo($filename, PATHINFO_FILENAME)) . "." . pathinfo($filename, PATHINFO_EXTENSION) );
                        $upload_result = move_uploaded_file( $tmp_name, $path . "/" . $new_filename);

                        if ( $upload_result ) {
                            //$original = $path . "/" . $new_filename;
                            $dataBack[$index]['url'] = $url;
                            $dataBack[$index]['path'] = $path;
                            $dataBack[$index]['original'] = $new_filename;
                            if( !empty( $this->_options['image_versions']) ){
                                foreach( $this->_options['image_versions'] as $version => $option){
                                    $success = $this->gd_create_scaled_image($new_filename, $option);
                                    if( $success ){
                                        $dataBack[$index][$version] = $success;
                                    }else{
                                        $dataBack[$index][$version] = $new_filename;
                                    }
                                }
                            }
                        }else{
                            $dataBack[$index]['error_message'] = "Đăng hình không thành công.";
                        }
                    }else{
                        $dataBack[$index]['error_message'] = $validate;
                    }
                }

            }else{
                $index=0;
                $filename = $files['name'];
                $dataBack[$index]['file_name'] = $filename;
                $size = $files['size'];
                $tmp_name = $files['tmp_name'];
                if( $validate = $this->validate($filename, $size) === true ){

                    $new_filename = wp_unique_filename( $path, sanitize_title( pathinfo($filename, PATHINFO_FILENAME)) . "." . pathinfo($filename, PATHINFO_EXTENSION) );
                    $upload_result = move_uploaded_file( $tmp_name, $path . "/" . $new_filename);

                    if ( $upload_result ) {
                        //$original = $path . "/" . $new_filename;
                        $dataBack[$index]['url'] = $url;
                        $dataBack[$index]['path'] = $path;
                        $dataBack[$index]['original'] = $new_filename;
                        if( !empty( $this->_options['image_versions']) ){
                            foreach( $this->_options['image_versions'] as $version => $option){
                                $success = $this->gd_create_scaled_image($new_filename, $option);
                                if( $success ){
                                    $dataBack[$index][$version] = $success;
                                }else{
                                    $dataBack[$index][$version] = $new_filename;
                                }
                            }
                        }
                    }else{
                        $dataBack[$index]['error_message'] = "Đăng hình không thành công.";
                    }
                }else{
                    $dataBack[$index]['error_message'] = $validate;
                }

            }
        }

        return $dataBack;
    }

    public function validate($file_name, $size ){
        if (!preg_match($this->_options['image_file_types'], $file_name)) {
            return "Định dạng hình không hợp lệ.";
        }
        if( $this->_options['max_file_size'] < $size || $size < $this->_options['min_file_size']){
            return "Dung lượng hình không được quá " . $this->_options['max_file_size'] . "M";
        }
        return true;
    }

    protected function gd_create_scaled_image($file_name, $options) {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');
            return false;
        }
        list($file_path, $new_file_path, $new_filename) =
            $this->get_scaled_image_file_paths($file_name, $options);
        $type = strtolower(substr(strrchr($file_name, '.'), 1));

        switch ($type) {
            case 'jpg':
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = 75;
                break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = 9;
                break;
            default:
                return false;
        }
        $src_img =  $src_func($file_path);
        $max_width = $img_width = imagesx($src_img);
        $max_height = $img_height = imagesy($src_img);
        if (!empty($options['width'])) {
            $max_width = $options['width'];
        }
        if (!empty($options['height'])) {
            $max_height = $options['height'];
        }
        $scale = min(
            $max_width / $img_width,
            $max_height / $img_height
        );
        if ($scale >= 1) {
            /* if ($image_oriented) {
                 return $write_func($src_img, $new_file_path, $image_quality);
             }*/
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }

        if ( empty($options['crop']) ) {
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
            $dst_x = 0;
            $dst_y = 0;
            $new_img = imagecreatetruecolor($new_width, $new_height);
        } else {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = $img_width / ($img_height / $max_height);
                $new_height = $max_height;
            } else {
                $new_width = $max_width;
                $new_height = $img_height / ($img_width / $max_width);
            }
            $dst_x = 0 - ($new_width - $max_width) / 2;
            $dst_y = 0 - ($new_height - $max_height) / 2;
            $new_img = imagecreatetruecolor($max_width, $max_height);
        }
        // Handle transparency in GIF and PNG images:
        switch ($type) {
            case 'gif':
            case 'png':
                imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
            case 'png':
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                break;
        }
        $success = imagecopyresampled(
                $new_img,
                $src_img,
                $dst_x,
                $dst_y,
                0,
                0,
                $new_width,
                $new_height,
                $img_width,
                $img_height
            ) && $write_func($new_img, $new_file_path, $image_quality);

        if( $success == true ) return $new_filename;
        return $success;
    }

    /*
     * TODO tạo thư mục chứa image scale
     * $file_name : tên file hình
     * $version : loại hình
     */
    protected function get_scaled_image_file_paths($file_name, $option) {
        $file_path = $this->_options['path'] . "/" . $file_name;
        if( !empty($option) ) {
            $new_file_name = pathinfo($file_name, PATHINFO_FILENAME) . "_"
                . ( ( !empty($option['width']) )?$option['width'] : 0 )
                . "x" . ( ( !empty($option['height']) )?$option['height'] : 0 )
                . "." . pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_path = $this->_options['path'] . '/' . $new_file_name;

        } else {
            $new_file_name = $file_name;
            $new_file_path = $file_path;
        }
        return array($file_path, $new_file_path, $new_file_name);
    }

    public function get_photo($post_id){
        /* @var wpdb $wpdb*/
        global $wpdb;
        $query = "SELECT * FROM ".VENDORS_POST_PHOTO." WHERE post_id=$post_id ORDER BY is_featured DESC, ordering ASC";
        $list = $wpdb->get_results($query);
        $result = new StdClass();
        $str = '';
        if( $list ){
            foreach( $list as $photo ){
                $image = @unserialize($photo->images);
                if( $image) {
                    $id = $photo->id;
                    $image = $image['url'] . "/" . $image['thumbnail'];
                    $str .= '<div class="has-image" id="div-photo-id-' . $id . '"><img  src="' . $image . '" width="100"><br/><a class=" delete_photo" href="javascript:void(0)" data-photo_id="' . $id . '" data-post_id="' . $post_id . '"><i class="fa fa-times"></i> Delete</a></div>';
                }
            }
            $result->list = $str;
            $result->total = count($list);
        } else {
            $result->list = $str;
            $result->total = 0;
        }
        return $result;
    }

    public function getFeaturedPhoto($post_id) {
        /* @var wpdb $wpdb*/
        global $wpdb;
        $query = "SELECT * FROM ".VENDORS_POST_PHOTO." WHERE post_id=$post_id AND is_featured = 1";
        $result = $wpdb->get_row($query);
        $str = '';
        if( $result ){
            $image = @unserialize($result->images);
            if( $image) {
                $str = $image['url'] . "/" . $image['thumbnail'];
            }
        }
        return $str;
    }


    /**
     * Handle plupload AJAX
     *
     * @since 2.3
     */
    function r_photo_upload_custom_photo(){
        global $post;
        $post_id = (!empty($_POST['post_id']))? intval($_POST['post_id']) : 0;
        if( is_user_logged_in() && $post_id ){
            //$title = (!empty($_POST['title']))? ($_POST['title']) : $post->post_title;
            $user = wp_get_current_user();
            $user_id = $user->data->ID;
            $limit = intval(5);
            $filesize = intval(2);
            $upload_path = $this->r_photo_create_path_images($user);

            $objUpload = $this->setOption(
                array(
                    'path' => $upload_path['path'],
                    'url' => $upload_path['url'],
                    'limit_upload_file' => $limit,
                    'max_file_size' => $filesize * 1024 * 1024
                )
            );

            $result = $this->upload();
            if( $result ){
                $tmp = array();

                $fearturedPhoto = $this->getFeaturedPhoto($post_id);
                if ($fearturedPhoto=='') {
                    $autoUpdateFeatured = true;
                } else {
                    $autoUpdateFeatured = false;
                }
                foreach( $result as $key => $image){
//                    unset($image['file_name']);
                    $isFeatured = ($autoUpdateFeatured && $key==0) ? 1 : 0;
                    $data = array(
                        'user_id' => intval($user_id),
                        'post_id' => $post_id,
                        //'title'   => $title . " - ". $key+1,
                        'images'  => serialize($image),
                        'is_featured' => $isFeatured,
                        'ordering' => 0
                    );
                    /* @var wpdb $wpdb*/
                    global $wpdb;
                    $result = $wpdb->insert(
                        VENDORS_POST_PHOTO,
                        $data,
                        array('%d', '%d', '%s', '%d', '%d')
                    );
                    if( $result ){
                        $photo_id = $wpdb->insert_id;
                        $image['photo_id'] = $photo_id;
                        $image['post_id'] = $post_id;
                    }else{

                        if( !empty( $this->_options['image_versions']) ){
                            foreach( $this->_options['image_versions'] as $version => $option){
                                $file = $image['path'] . "/" . $image[$version];
                                if( file_exists($file) && is_file($file) ) unlink($file);
                            }
                        }

                        $file = $image['path'] . "/" . $image['original'];
                        if( file_exists($file) && is_file($file) ) unlink($file);

                        $image['error'] = 'Đăng hình không thành công.';
                    }

                    $tmp[] = $image;
                }
                $result = $tmp;
                echo json_encode($result);
            }else{
                echo json_encode(array('error' => 1));
            }
        }
        exit();
    }

    function r_photo_create_path_images($user=null, $type=''){
        $upload = wp_upload_dir();
        if( $type == 'temp' ){
            $dest = $upload["basedir"] . "/temp" . ( ($user)?"/".$user->ID:'' ) . $upload['subdir'];
            $url = "/wp-content/uploads/temp" . ( ($user)?"/".$user->ID:'' ) . $upload['subdir'];
        }else{
            $dest = $upload["basedir"] . "/users" . ( ($user)?"/".$user->ID:'' ) . $upload['subdir'] . "/" . date('d', time());
            $url = "/wp-content/uploads/users" . ( ($user)?"/".$user->ID:'' ) . $upload['subdir'] . "/" . date('d', time());
        }

        if( !$flag=file_exists( $dest ) ) {
            $flag = mkdir($dest, 0755, true);
        }
        if( $flag ) return array('path' => $dest, 'url' => $url );
        else return false;
    }

    function r_photo_delete_photo(){
        $result = false;
        $photo_id = $_POST['photo_id'];
        /* @var wpdb $wpdb*/
        global $wpdb;
        $query = "SELECT * FROM " . VENDORS_POST_PHOTO . " WHERE id=$photo_id";
        $photo = $wpdb->get_row($query);
        $flag_delete = false;
        if( $photo ){
            if( is_admin() ){
                $flag_delete = true;
            }else{
                if( is_user_logged_in() ){
                    $user_id = get_current_user_id();
                    if( $photo->user_id == $user_id ){
                        $flag_delete = true;
                    }
                }
            }
        }


        if( $flag_delete ){
            $image = unserialize($photo->images);
            if( !empty( $this->_options['image_versions']) ){
                foreach( $this->_options['image_versions'] as $version => $option){
                    $file = $image['path'] . "/" . $image[$version];
                    if( file_exists($file) && is_file($file) ) unlink($file);
                }
            }

            $file = $image['path'] . "/" . $image['original'];
            if( file_exists($file) && is_file($file) ) unlink($file);

            $result = $wpdb->delete(
                VENDORS_POST_PHOTO,
                array('id' => $photo_id)
            );
        }

        if  ( $result ) {
            $response = array(
                'status' => 1,
                'photo_id' => $photo_id,
            );

            // update default featured photo
            $query = "SELECT * FROM " . VENDORS_POST_PHOTO . " WHERE post_id=$photo->post_id";
            $result = $wpdb->get_row($query);
            if ($result) {
                $wpdb->update(
                    VENDORS_POST_PHOTO,
                    array('is_featured' => 1),
                    array('id' => $result->id)
                );
            }
        } else {
            $response = array(
                'status' => 0,
                'photo_id' => $photo_id,
            );
        }
        echo json_encode($response);
        exit();
    }

    function r_photo_set_featured_photo(){
        $result = false;
        $post_id = $_POST['post_id'];
        $photo_id = $_POST['photo_id'];
        /* @var wpdb $wpdb*/
        global $wpdb;

        $result = $wpdb->update(
            VENDORS_POST_PHOTO,
            array('is_featured' => 0),
            array('post_id' => $post_id)
        );

        if  ( $result ) {
            $result = $wpdb->update(
                VENDORS_POST_PHOTO,
                array('is_featured' => 1),
                array('id' => $photo_id)
            );

            if  ( $result ) {
                $response = array(
                    'status' => 1,
                    'photo_id' => $photo_id,
                );
            } else {
                $response = array(
                    'status' => 0,
                    'photo_id' => $photo_id,
                );
            }

        } else {
            $response = array(
                'status' => 0,
                'photo_id' => $photo_id,
            );
        }
        echo json_encode($response);
        exit();
    }
}


class acf_field_photo extends acf_field {

    // vars
    var $settings, // will hold info such as dir / path
        $defaults; // will hold default field options


    /*
    *  __construct
    *
    *  Set name / label needed for actions / filters
    *
    *  @since	3.6
    *  @date	23/01/13
    */

    function __construct()
    {
        // vars
        $this->name = 'photo';
        $this->label = __('Photos');
        $this->category = __("Basic",'acf'); // Basic, Content, Choice, etc
        $this->defaults = array(
            // add default here to merge into your field.
            // This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
            //'preview_size' => 'thumbnail'
        );


        // do not delete!
        parent::__construct();


        // settings
        $this->settings = array(
            'path' => apply_filters('acf/helpers/get_path', __FILE__),
            'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
            'version' => '1.0.0'
        );

    }

    /*
    *  create_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param	$field - an array holding all the field's data
    *
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    */

    function create_field( $field )
    {
        global $post;
        // create Field HTML
        // build hidden filed
        $o = array( 'id', 'class', 'name', 'value' );
        $e = '';
        $e .= '<input type="hidden"';
        foreach( $o as $k )
        {
            $e .= ' ' . $k . '="' . esc_attr( $field[ $k ] ) . '"';
        }
        $e .= ' />';

        // ---

        $objPhotos = new r_photo();
        $listPhoto = $objPhotos->get_photo($post->ID);
        ?>

        <style>
            #list_photo .has-image { float: left}
        </style>
        <div class=" clearfix active">
            <?php echo $e; ?>
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Select files...</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="files[]" multiple data-post_id="<?php echo $post->ID ?>" >
            </span>
                    <br>
                    <br>
                    <!-- The global progress bar -->
                    <div id="progress" class="progress">
                        <div class="progress-bar progress-bar-success"></div>
                    </div>
                    <!-- The container for the uploaded files -->
                    <div id="list_photo" class="list_photo"><?php echo $listPhoto->list ?></div>
        </div>
    <?php
    }


    /*
    *  input_admin_enqueue_scripts()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your create_field() action.
    *
    *  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    */

    function input_admin_enqueue_scripts()
    {
        wp_enqueue_script( 'jquery-fileupload', $this->settings['dir'] . 'js/jquery.fileupload.js' , array( 'jquery' ), false, true );
        wp_enqueue_script( 'jquery-fileupload-process', $this->settings['dir'] . 'js/jquery.fileupload-process.js' , array( 'jquery' ), false, true );
        wp_enqueue_script( 'jquery-fileupload-validate', $this->settings['dir'] . 'js/jquery.fileupload-validate.js' , array( 'jquery' ), false, true );
        wp_enqueue_script( 'jquery-iframe-transport', $this->settings['dir'] . 'js/jquery.iframe-transport.js' , array( 'jquery' ), false, true );

        wp_enqueue_script( 'af-field-photos', $this->settings['dir'] . 'js/photos.js' , array( 'jquery' ), '2', true );

       // wp_enqueue_style( 'bootrap', $this->settings['dir'] . 'css/bootstrap.min.css', array(), $this->settings['version'] );
        wp_enqueue_style( 'jquery-fileupload', $this->settings['dir'] . 'css/jquery.fileupload-ui.css', array(), $this->settings['version'] );
        wp_enqueue_style( 'input-css', $this->settings['dir'] . 'css/input.css', array(), $this->settings['version'] );
    }
}


// create field
new acf_field_photo();
new r_photo();
