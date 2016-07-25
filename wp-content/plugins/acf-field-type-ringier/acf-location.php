<?php
class r_location {
    function __construct(){
        add_action('wp_ajax_get_district_by_province_id', array($this, 'get_district_by_province_id'));
        add_action('wp_ajax_nopriv_get_district_by_province_id', array($this, 'get_district_by_province_id'));

        add_action('wp_ajax_get_ward_by_district_id', array($this, 'get_ward_by_district_id'));
        add_action('wp_ajax_nopriv_get_ward_by_district_id', array($this, 'get_ward_by_district_id'));
    }

    public function getProvince(){
        $cacheId = __CLASS__ . __METHOD__;
        $result = wp_cache_get($cacheId, CACHE_GROUP);
        if (false === $result) {
            global $wpdb;
            $query = "SELECT * FROM " . $wpdb->prefix . "location_province ORDER BY orderby, name";
            $result = $wpdb->get_results($query);

            wp_cache_set($cacheId, $result, CACHE_GROUP, CACHE_TIME);
        }
        return $result;
    }

    public function getDistrictByProvinceId($id){
        $cacheId = __CLASS__ . __METHOD__.$id;
        $result = wp_cache_get($cacheId, CACHE_GROUP);
        if (false === $result) {
            global $wpdb;
            $query = "SELECT * FROM " . $wpdb->prefix . "location_district
                    WHERE province_id=%d
                    ORDER BY name";
            $result = $wpdb->get_results($wpdb->prepare($query, $id));

            wp_cache_set($cacheId, $result, CACHE_GROUP, CACHE_TIME);
        }
        return $result;
    }

    public function getWardByDistrictId($id){
        $cacheId = __CLASS__ . __METHOD__.$id;
        $result = wp_cache_get($cacheId, CACHE_GROUP);
        if (false === $result) {
            global $wpdb;
            $query = "SELECT * FROM " . $wpdb->prefix . "location_ward
                    WHERE district_id=%d
                    ORDER BY name";
            $result = $wpdb->get_results($wpdb->prepare($query, $id));

            wp_cache_set($cacheId, $result, CACHE_GROUP, CACHE_TIME);
        }
        return $result;
    }

    function get_district_by_province_id(){
        $province_id = intval($_POST['province_id']);
        $districtList = $this->getDistrictByProvinceId($province_id);
        $result = $data = array();
        if ($districtList) {
            foreach ($districtList as $district) {
                $data[] = array(
                    'key' => $district->id,
                    'value' => $district->name,
                );
            }
            $result['status'] = 1;
            $result['data'] = $data;
        } else {
            $result['status'] = 0;
            $result['data'] = $data;
        }
        echo json_encode($result); exit;
    }

    function get_ward_by_district_id(){
        $district_id = $_POST['district_id'];
        $wardList = $this->getWardByDistrictId($district_id);
        $result = $data = array();
        if ($wardList) {
            foreach ($wardList as $ward) {
                $data[] = array(
                    'key' => $ward->id,
                    'value' => $ward->name,
                );
            }
            $result['status'] = 1;
            $result['data'] = $data;
        } else {
            $result['status'] = 0;
            $result['data'] = $data;
        }
        echo json_encode($result); exit;
    }
}

class acf_field_location extends acf_field {

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
        $this->name = 'location';
        $this->label = __('Location');
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
        echo $e;
        // ---


        $data = array();
        if (isset($field['value'])) $data = $field['value'];
        $objLocation = new r_location();
        $provinceList = $objLocation->getProvince();
        if ($data['city_id']>0) {
            $districtList = $objLocation->getDistrictByProvinceId($data['city_id']);
        }
        if ($data['district_id']>0) {
            $wardList = $objLocation->getWardByDistrictId($data['district_id']);
        }
        ?>

        <div id="acf-location_address" class="field field_type-text" data-field_name="location_address" data-field_type="text" style="margin-bottom: 20px">
            <p class="label"><label for="acf-field-location_address">Địa chỉ</label></p>
            <div class="acf-input-wrap"><input type="text" id="acf-field-location_address" class="text location_address" name="location_address" value="<?php echo $data['address'] ?>" placeholder=""></div>
        </div>
        <div id="acf-location_province" class="field field_type-taxonomy" data-field_name="location_province" data-field_type="select" style="margin-bottom: 20px">
            <p class="label"><label for="acf-field-location_province">Tỉnh/TP</label></p>
            <select id="acf-field-location_province" name="location_province" class="r_location location_city" style="width: 100%">
                <option value="">--- chọn ---</option>
                <?php
                foreach ($provinceList as $value) {
                    $selected = ($data['city_id']==$value->id) ? "selected" : "";
                    echo "<option value='". $value->id ."' $selected>". $value->name ."</option>";
                }
                ?>
            </select>
        </div>
        <div id="acf-location_district" class="field field_type-taxonomy" data-field_name="location_district" data-field_type="select" style="margin-bottom: 20px">
            <p class="label"><label for="acf-field-location_district">Quận/Huyện</label></p>
            <select id="acf-field-location_district" name="location_district" class="r_location location_district" style="width: 100%">
                <option value="">--- chọn ---</option>
                <?php
                if (isset($districtList)) {
                    foreach ($districtList as $value) {
                        $selected = ($data['district_id']==$value->id) ? "selected" : "";
                        echo "<option value='". $value->id ."' $selected>". $value->name ."</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div id="acf-location_ward" class="field field_type-taxonomy" data-field_name="location_ward" data-field_type="select" style="margin-bottom: 20px">
            <p class="label"><label for="acf-field-location_ward">Phường/Xã</label></p>
            <select id="acf-field-location_ward" name="location_ward" class="r_location location_ward" style="width: 100%">
                <option value="">--- chọn ---</option>
                <?php
                if (isset($wardList)) {
                    foreach ($wardList as $value) {
                        $selected = ($data['ward_id']==$value->id) ? "selected" : "";
                        echo "<option value='". $value->id ."' $selected>". $value->name ."</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div id="acf-location_map" class="field field_type-text" data-field_name="location_map" data-field_type="text" style="margin-bottom: 20px">
            <p class="label"><label for="acf-field-location_address">Bản đồ <a href="javascript:void(0)" class="get_latlng">Lấy tọa độ từ địa chỉ</a></label></p>
            <div class="acf-input-wrap">
                <div class="map-canvas" id="map-canvas" style="height: 400px; width: 100%"></div>
                <p>
                    Latitude <input type="text" style="width:30%" class="store-lat" name="latitude" value="<?php echo $data['lat'] ?>" readonly/>         Longitute <input type="text" style="width:30%" class="store-lng" name="longitude" value="<?php echo $data['lng'] ?>" readonly/>
                </p>
            </div>
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
        // register ACF scripts
        wp_enqueue_script( 'acf-input-location', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'], true );

        // Google maps api
        wp_enqueue_script('vendor-googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places', array('jquery'), null, true);
        wp_enqueue_script( 'r-location', $this->settings['dir'] . 'js/location.js', array('acf-input'), $this->settings['version'], true );


        wp_enqueue_style( 'acf-input-location', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] );
        //wp_register_style( 'jquery-select2', $this->settings['dir'] . 'css/select2.css', array('acf-input'), $this->settings['version'] );
/*

        // scripts
        wp_enqueue_script(array(
            'acf-input-location',
            'r-location',
        ));

        // styles
        wp_enqueue_style(array(
            'acf-input-location',
            //'jquery-select2',
        ));*/


    }
}


// create field
new acf_field_location();
global $objLocation;
$objLocation = new r_location();
