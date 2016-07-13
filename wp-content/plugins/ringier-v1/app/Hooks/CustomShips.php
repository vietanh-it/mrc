<?php
namespace RVN\Hooks;

use RVN\Library\CPTColumns;
use RVN\Models\Posts;

/**
 * Created by PhpStorm.
 *
 * Date: 2/9/2015
 * Time: 9:44 AM
 */
class CustomShips
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomShips();
        }

        return self::$instance;
    }


    public function __construct()
    {
        global $wpdb;

        $this->_table_size = $wpdb->prefix . "ship_info";

        add_action('add_meta_boxes', array($this, 'add_product_size'));
        add_action('save_post', array($this, 'save'));
    }

    public function add_product_size()
    {
        add_meta_box('ship_detail', 'Ship room', array($this, 'show'), 'ship', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        $ship_info = $this->getShipInfo($post->ID);
        $result = array();
        if ($ship_info && $ship_info->room_types) {
            $result = unserialize($ship_info->room_types);
        }

        echo '<div class = "ctn-box-size">';
        if ($result) {
            foreach ($result as $v) {
                $stateroom_type = $v['room_type'];
                $quantity = $v['quantity'];
                echo $this->html_size($stateroom_type, $quantity);
            }
        } else {
            echo $this->html_size();
        }
        echo '</div>';
        echo '
            <a href = "javascript:void(0)" class="add-new-size" data-id="2">Add new STATEROOM TYPE</a>
        ';

        ?>
        <script>
            var $ = jQuery.noConflict();

            jQuery(document).ready(function ($) {

                var html_size = '<div class ="size-product" style="margin-bottom: 30px;border-bottom: 1px solid darkgrey;padding-bottom: 20px"> ' +
                    '<div class="size" style="float: left;width: 50%"> ' +
                    '<label >ROOM TYPE : </label> ' +
                    '<input type="text" value="" name="size[]"> ' +
                    '</div> ' +
                    '<div class ="quantity" style="float: left;width: 50%;    margin-bottom: 10px;"> ' +
                    '<label >Quantity : </label> ' +
                    '<input type="number" value="" name="quantity_size[]" > ' +
                    '</div> ' +
                    '<div class="close" style=""> ' +
                    '<a href="javascript:void(0)" class="close-box-size" >Delete</a> ' +
                    '</div>' +
                    '</div>';

                $('.add-new-size').click(function () {
                    var id = $(this).attr('data-id');

                    $('.ctn-box-size').append(html_size);
                });

                $(document).delegate('.close-box-size', 'click', function () {
                    $(this).closest('div').closest('div.size-product').remove();
                });

            });

        </script>

        <?php
    }

    public function html_size($stateroom_type = '', $quantity = '')
    {
        $html = '<div class ="size-product" style="margin-bottom: 30px;border-bottom: 1px solid darkgrey;padding-bottom: 20px">
                    <div class="size" style="float: left;width: 50%">
                        <label >ROOM TYPE : </label>
                        <input type="text" value="' . $stateroom_type . '" name="size[]">
                    </div>
                    <div class ="quantity" style="float: left;width: 50%;    margin-bottom: 10px;">
                        <label >Quantity : </label>
                        <input type="number" value="' . $quantity . '" name="quantity_size[]" >
                    </div>
                     <div class="close" style="">
                        <a href="javascript:void(0)" class="close-box-size" >Delete</a>
                    </div>
                </div>
                 ';

        return $html;
    }

    public function save()
    {
        // var_dump($_POST);
        global $wpdb;
        if ($_POST['size'] && $_POST['quantity_size']) {
            $list_size = array();
            foreach ($_POST['size'] as $k => $stateroom_type) {
                $size_quantity = array(
                    'room_type' => $stateroom_type,
                    'quantity'  => $_POST['quantity_size'][$k],
                );

                $list_size[] = $size_quantity;
            }


            $ship_info = $this->getShipInfo($_POST['post_ID']);
            if ($ship_info) {
                $wpdb->update($this->_table_size, array('room_types' => serialize($list_size)),
                    array('object_id' => $_POST['post_ID']));
            } else {
                $wpdb->insert($this->_table_size, array(
                    'room_types' => serialize($list_size),
                    'object_id'  => $_POST['post_ID']
                ));
            }

        }


    }

    public function getShipInfo($ship_id)
    {
        global $wpdb;

        $query = 'SELECT * FROM ' . $this->_table_size . ' WHERE object_id = ' . $ship_id;

        // echo  $query;
        $result = $wpdb->get_row($query);

        return $result;
    }
}
