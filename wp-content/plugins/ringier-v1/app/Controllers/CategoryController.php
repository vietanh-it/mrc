<?php
namespace RVN\Controllers;

class CategoryController extends _BaseController
{
    private static $instance;

    protected function __construct() {
        parent::__construct();
    }

    public static function init()
    {
        if ( ! isset( self::$instance )) {
            self::$instance = new CategoryController();
        }

        return self::$instance;
    }

    public function index(){
        $category = [1, 2, 3];
        $listArticles = ['bai 1', 'bai 2'];

        return view('category/index', compact('category', 'listArticles'));
    }

    /**
     *
     * ajax xóa bài blog
     *
     * @return array
     */
    public function ajaxDelete(){
        $objID = $_POST['objectID'];

        // success
        if ($objID) {
            $result = array(
                'status' => 'success',
                'message' => 'Xóa category thành công.'
            );
        } else {
            $result = array(
                'status' => 'error',
                'message' => 'Không xóa category thành công, vui lòng thử lại.'
            );
        }
        return $result;
    }
}
