<?php
namespace RVN\Controllers;

use RVN\Library\Images;

class _BaseController
{
    protected $session;
    protected $validate;

    protected function __construct() {
        // view docs https://github.com/auraphp/Aura.Session
        $session_factory = new \Aura\Session\SessionFactory;
        $this->session = $session_factory->newInstance($_COOKIE);

        // view doc https://github.com/vlucas/valitron
        $this->validate = new \Valitron\Validator($_POST, array(), 'vi');
    }


    public function ajaxHandler(){
        // view docs http://labs.omniti.com/labs/jsend
        $result = array(
            'status'  => 'error',
            'message' => 'Đã xảy ra lỗi, vui lòng thử lại'
        );
        if ( ! empty( $_POST["method"] )) {
            $method = sanitize_text_field($_POST["method"]);
            if (method_exists($this, "ajax" . $method)) {
                $result = call_user_func([$this, "ajax" . $method], $_POST);
            }
        }

        echo json_encode($result);
        exit;
    }
}
