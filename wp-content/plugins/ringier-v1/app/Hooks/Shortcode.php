<?php
namespace RVN\Hooks;

class Shortcode {
    private static $instance;

    public static function init()
    {
        if ( ! isset( self::$instance )) {
            self::$instance = new Shortcode();
        }

        return self::$instance;
    }

    function __construct(){
        add_action("wp_ajax_rvn_ajax_handler_inlineform", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_rvn_ajax_handler_inlineform", [$this, "ajaxHandler"]);
        add_shortcode( 'inline_form', array($this,'inline_form'));
    }

    public function ajaxHandler(){
        // view docs http://labs.omniti.com/labs/jsend
        if (isAjax()) {
            $result = array(
                'status'  => 'error',
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại'
            );
            if ( ! empty( $_POST["method"] )) {
                $method = $_POST["method"];
                if (method_exists($this, "ajax" . $method)) {
                    $result = call_user_func([$this, "ajax" . $method], $_POST);
                }
            }

            echo json_encode($result);
            exit;
        }
    }

    function inline_form( $atts ) {
        $_ten_chuong_trinh                   = $atts['ten_chuong_trinh'];
        $_ho_ten_nguoi_di_cung               = $atts['ho_ten_nguoi_di_cung'];
        $_so_luong_nguoi_di_cung             = $atts['so_luong_nguoi_di_cung'];
        $_so_dien_thoai_nguoi_di_cung        = $atts['so_dien_thoai_nguoi_di_cung'];
        $_so_luong_thanh_vien_trong_gia_dinh = $atts['so_luong_thanh_vien_trong_gia_dinh'];
        $_nghe_nghiep                        = $atts['nghe_nghiep'];
        $_thu_nhap                           = $atts['thu_nhap'];
        $_hoc_van                            = $atts['hoc_van'];
        $_link_facebook                      = $atts['link_facebook'];


        $html = '
            <style>.inline-form-wrap .panel-body{padding:9px!important;}.inline-form-wrap .child-wrap{background: #ddd;}.inline-form-wrap input::-webkit-input-placeholder,.inline-form-wrap input::-moz-placeholder{color: #00addb!important;}.inline-form-wrap :checked+.radioinput__label{color: #00addb!important;}.inline-form-wrap .radioinput__label:after{background-color:#00addb!important;}.inline-form-wrap .radioinput__label:before{border-color:#00ADDB!important;}.inline-form-wrap .radioinput__label{color:#00addb!important;}.inline-form-wrap .inline_form_radioinput{padding-left: 7px!important;padding-top: 7px!important;}.inline-form-wrap .radio input[type="radio"]{margin-top: 8px;}.clear-both{clear: both;}.inline-form-wrap .inline-form label.error{color:red;}.inline-form-wrap label.control-label::after{display: block;content: ":";position: absolute;top: 7px;right: 4px;}@media(max-width: 400px){.inline-form-wrap label.control-label::after{position: relative!important;top: -20px!important;left: 80%!important;}.inline-form-wrap input.radio{width: 20%!important;}.inline-form-wrap .radio{width: 30%;float: left;margin: 0px;}.inline-form-wrap .radioinput__label{float:left;}.inline-form-wrap input.radioinput__input{float: left;width: 20px!important;}}@media(min-width: 401px){.mobile-only{display: none;}} </style>
            <script type="text/javascript" src="'.plugins_url().'/ringier-v1/app/Library/js/bootstrap-datepicker.js"></script>
            <script type="text/javascript" src="'.plugins_url().'/ringier-v1/app/Library/js/bootstrap-datepicker.min.js"></script>
            <style type="text/css" src="'.plugins_url().'/ringier-v1/app/Library/css/bootstrap-datepicker3.css"></style>
            <div class="inline-form-wrap">
                <form class="inline-form form-horizontal" id="inlineForm" >    
                <input type="hidden" value="'.$_ten_chuong_trinh.'" name="ten_chuong_trinh" id="ten_chuong_trinh">
                
                <div class="form-group">
                    <label class="control-label col-sm-4" for="ho_ten">Họ và tên</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="ho_ten" name="ho_ten" placeholder="Họ và tên" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Ngày sinh</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker3" id="ngay_sinh" name="ngay_sinh" placeholder="Ngày sinh" >
                            <label for="ngay_sinh" class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="email">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Ngày cưới</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker3" id="ngay_cuoi" name="ngay_cuoi" placeholder="Ngày cưới" >
                            <label for="ngay_cuoi" class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></label>
                        </div>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-sm-4" for="dia_chi">Địa chỉ</label>
                    <div class="col-sm-8">
                        <select name="dia_chi" id="dia_chi" class="form-control">
                            <option value="Tp.HCM" selected>Tp. Hồ Chí Minh</option>
                            <option value="Hà Nội">Hà Nội</option>
                            <option value="Hải Phòng">Hải Phòng</option>
                            <option value="Cần Thơ">Cần Thơ</option>
                            <option value="Đà Nẵng">Đà Nẵng</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="so_dien_thoai">Số điện thoại</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="so_dien_thoai" name="so_dien_thoai" placeholder="Số điện thoại" >
                    </div>
                </div>';

        if (isset($_ho_ten_nguoi_di_cung)){
            $html .=   '<div class="form-group">
                            <label class="control-label col-sm-4" for="ho_ten_nguoi_di_cung">Họ tên người đi cùng</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ho_ten_nguoi_di_cung" name="ho_ten_nguoi_di_cung" placeholder="Họ tên người đi cùng">
                            </div>
                        </div>';
        }
        if (isset($_so_dien_thoai_nguoi_di_cung)){
            $html .=   '<div class="form-group">
                            <label class="control-label col-sm-4" for="so_dien_thoai_nguoi_di_cung">Số điện thoại người đi cùng</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="so_dien_thoai_nguoi_di_cung" name="so_dien_thoai_nguoi_di_cung" placeholder="Số điện thoại người đi cùng">
                            </div>
                        </div>';
        }
        if (isset($_so_luong_nguoi_di_cung)){
            $html .=   '<div class="form-group">
                            <label class="control-label col-sm-4" for="so_luong_nguoi_di_cung">Số lượng người đi cùng</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="so_luong_nguoi_di_cung" name="so_luong_nguoi_di_cung" placeholder="Số lượng người đi cùng">
                            </div>
                        </div>';
        }
        if (isset($_so_luong_thanh_vien_trong_gia_dinh)){
            $html .=   '<div class="form-group">
                            <label class="control-label col-sm-4" for="so_luong_thanh_vien_trong_gia_dinh">Số lượng thành viên trong gia đình</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="so_luong_thanh_vien_trong_gia_dinh" name="so_luong_thanh_vien_trong_gia_dinh" placeholder="Số lượng thành viên trong gia đình">
                            </div>
                        </div>';
        }
        if (isset($_nghe_nghiep)){
            $html .=   '<div class="form-group">
                    <label class="control-label col-sm-4" for="nghe_nghiep">Nghề nghiệp</label>
                    <div class="col-sm-8">
                        <select name="nghe_nghiep" id="nghe_nghiep" class="form-control">
                            <option value="Học sinh/sinh viên" selected>Học sinh/sinh viên</option>
                            <option value="Công nhân">Công nhân</option>
                            <option value="Nhân viên văn phòng">Nhân viên văn phòng</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>';
        }
        if (isset($_thu_nhap )){
            $html .=    '<div class="form-group">
                            <label class="control-label col-sm-4" for="thu_nhap">Thu nhập</label>
                            <div class="col-sm-8">
                                <select name="thu_nhap" id="thu_nhap" class="form-control">
                                    <option value="Dưới 7,500,000" selected>Dưới 7,500,000</option>
                                    <option value="Từ 7,500,000 - 10,000,000">Từ 7,500,000 - 10,000,000</option>
                                    <option value="Từ 10,000,000 - 15,000,000">Từ 10,000,000 - 15,000,000</option>
                                    <option value="Trên 15,000,000">Trên 15,000,000</option>
                                </select>
                            </div>
                        </div>';
        }
        if (isset($_hoc_van)){
            $html .=   '<div class="form-group">
                            <label class="control-label col-sm-4" for="hoc_van">Học vấn</label>
                            <div class="col-sm-8">
                                <select name="hoc_van" id="hoc_van" class="form-control">
                                    <option value="Phổ Thông" selected>Phổ Thông</option>
                                    <option value="Đại Học">Đại Học</option>
                                    <option value="Trên Đại Học">Trên Đại Học</option>
                                </select>
                            </div>
                        </div>';
        }
        if (isset($_link_facebook)){
            $html .=  '<div class="form-group">
                            <label class="control-label col-sm-4" for="link_facebook">Link facebook</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="link_facebook" name="link_facebook" placeholder="Link facebook">
                            </div>
                        </div>';
        }
        $wp_nonce = wp_nonce_field('form_event_register','mb_wp_nonce');
        $html .= $wp_nonce;
        $html .= '<button type="submit" class="btn-default btn inline_form_submit">Đăng ký</button>
                    <input type="hidden" name="action" value="rvn_ajax_handler_inlineform">
                    <input type="hidden" name="method" value="InlineFormRegister">
                        </form>
                     <script type="text/javascript" src="'.plugins_url().'/ringier-v1/app/Library/js/inline_form.js"></script>
            </div>';
        return $html;
    }
    public function ajaxInlineFormRegister($data){
        if (
            ! isset( $data['mb_wp_nonce'] )
            || ! wp_verify_nonce( $data['mb_wp_nonce'], 'form_event_register' )
        ) {

            print 'Sorry, your nonce did not verify.';
            exit;

        } else {
            $result = [];
            $v = new \Valitron\Validator($data, array(), 'vi');
            $v->rule('required', ['ho_ten','ngay_sinh','email','so_dien_thoai','ngay_cuoi']);
            $v->labels(array(
                'ho_ten' => 'Họ tên',
                'ngay_sinh' => 'Ngày sinh',
                'email' => 'Email',
                'so_dien_thoai' => 'Số điện thoại',
                'ngay_cuoi' => 'Ngày cưới'
            ));

            if ($v->validate()) {

                    $objUsers = \RVN\Models\Users::init();
                    $result = $objUsers->inlineFormRegister($data);

            }else{
                $messageError = '';
                $messages = $v->errors();
                foreach ($messages as $errors) {
                    foreach ($errors as $e) {
                        $messageError .= $e . "<br />";
                    }
                }

                $result = array(
                    'status' => 'error',
                    'message' => $messageError
                );
            }

            return $result;
        }
    }
}