<?php
namespace RVN\Controllers;

use RVN\Library\Images;

class _BaseController
{
    protected $session;
    protected $validate;

    protected function __construct()
    {
        // view docs https://github.com/auraphp/Aura.Session
        $session_factory = new \Aura\Session\SessionFactory;
        $this->session = $session_factory->newInstance($_COOKIE);

        // view doc https://github.com/vlucas/valitron
        $this->validate = new \Valitron\Validator($_POST, [], 'vi');
    }


    public function ajaxHandler()
    {
        // view docs http://labs.omniti.com/labs/jsend
        $result = [
            'status'  => 'error',
            'message' => ['Đã xảy ra lỗi, vui lòng thử lại']
        ];
        if (!empty($_REQUEST["method"])) {
            $method = sanitize_text_field($_REQUEST["method"]);
            if (method_exists($this, "ajax" . $method)) {
                $rs = call_user_func([$this, "ajax" . $method], $_POST);

                if (!empty($rs)) {
                    $result = $rs;
                }
            }
        }

        echo json_encode($result);
        exit;
    }
}
