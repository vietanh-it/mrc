<?php
namespace RVN\Hooks;

use RVN\Models\Addon;

class MetaboxAddon
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new MetaboxAddon();
        }

        return self::$instance;
    }


    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addServiceAddon']);
        add_action('save_post', [$this, 'save']);
    }


    public function addServiceAddon()
    {
        add_meta_box('addon', 'Addon Options', [$this, 'show'], 'addon', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        ?>

        <style>
            .option-box {
                border: 1px solid #d5b76e;
                padding: 10px 20px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                margin-top: 10px;
                margin-bottom: 5px;
                position: relative;
            }
            .option-box .delete_option_box{
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 20px;
            }

            .option-box table {
                width: 100%;
            }

            .option-box input {
                width: 90%;
            }

            .add-option-box {

            }
        </style>

        <?php
        $objAddon= Addon::init();
        $addon_option = $objAddon->getAddonOptions($post->ID);
        if(!empty($addon_option)){ ?>
            <div class="ctn-box-option">
                <div class="option-box-wrapper">
                     <?php foreach ($addon_option as $o){ ?>
                         <div class="option-box acf_postbox">
                             <table>
                                 <tr>
                                     <td>
                                         <p class="label">
                                             <label class="label">Option name:</label>
                                         </p>
                                         <input type="text" placeholder="Enter option name" name="addon_option_name[]" value="<?php echo  $o->option_name?>">
                                     </td>
                                     <td>
                                         <p class="label">
                                             <label>Price / 1 person</label>
                                         </p>
                                         <input type="text" placeholder="Enter price" name="addon_option_price[]" value="<?php echo  $o->option_price ?>">
                                     </td>
                                 </tr>
                             </table>
                             <a href="javascript:void(0)" class="delete_option_box" title="Delete box"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                         </div>
                     <?php } ?>
                </div>

                <div style="text-align: center" class="add-option-box">
                    <a href="javascript:void(0)" class="add_new_option_box"> <i class="fa fa-plus-square-o"></i> Add new </a>
                </div>
            </div>
        <?php } else{ ?>
            <div class="ctn-box-option">
                <div class="option-box-wrapper">
                    <div class="option-box acf_postbox">
                        <table>
                            <tr>
                                <td>
                                    <p class="label">
                                        <label class="label">Option name:</label>
                                    </p>
                                    <input type="text" placeholder="Enter option name" name="addon_option_name[]">
                                </td>
                                <td>
                                    <p class="label">
                                        <label>Price / 1 person</label>
                                    </p>
                                    <input type="text" placeholder="Enter price" name="addon_option_price[]">
                                </td>
                            </tr>
                        </table>
                        <a href="javascript:void(0)" class="delete_option_box" title="Delete box"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                    </div>
                </div>

                <div style="text-align: center" class="add-option-box">
                    <a href="javascript:void(0)" class="add_new_option_box"> <i class="fa fa-plus-square-o"></i> Add new </a>
                </div>
            </div>
        <?php } ?>

        <script>
            var $ = jQuery.noConflict();

            jQuery(document).ready(function ($) {
                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

                $('.add_new_option_box').click(function () {
                    var obj = $(this);
                    var html = '<div class="option-box acf_postbox"> ' +
                        '<table> ' +
                        '<tr> ' +
                        '<td> ' +
                        '<p class="label"> ' +
                        '<label class="label">Option name:</label> ' +
                        '</p> ' +
                        '<input type="text" placeholder="Enter option name" name="addon_option_name[]"> ' +
                        '</td> ' +
                        '<td> ' +
                        '<p class="label"> ' +
                        '<label>Price / 1 person</label> ' +
                        '</p> ' +
                        '<input type="text" placeholder="Enter price" name="addon_option_price[]"> ' +
                        '</td> ' +
                        '</tr> ' +
                        '</table> ' +
                        '<a href="javascript:void(0)" class="delete_option_box" title="Delete box"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a> ' +
                        '</div>';
                    $('.option-box-wrapper').append(html);
                });


                $(document).delegate('.delete_option_box', 'click', function () {
                    $(this).closest('.option-box').remove();
                });
            });

        </script>

        <?php
    }


    public function save()
    {
        if(!empty($_POST['addon_option_name']) && !empty($_POST['addon_option_price'])){
            $objAddOn = Addon::init();
            $addon_option = $objAddOn->getAddonOptions($_POST['post_ID']);
            if(!empty($addon_option)){
                $objAddOn->delete(array('object_id' => $_POST['post_ID']));
            }
            foreach ($_POST['addon_option_name'] as $k => $v){
                $args = array(
                    'object_id' => $_POST['post_ID'],
                    'option_name' => $v,
                    'option_price' => $_POST['addon_option_price'][$k],
                );

                $save = $objAddOn->saveAddon($args);
            }
        }
    }

}
