<?php
namespace RVN\Hooks;

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

        <div class="ctn-box">
            <div class="option-box-wrapper">
                <div class="option-box acf_postbox">
                    <table>
                        <tr>
                            <td>
                                <p class="label">
                                    <label class="label">Option name:</label>
                                </p>
                                <input type="text" placeholder="Enter option name">
                            </td>
                            <td>
                                <p class="label">
                                    <label>Price / 1 person</label>
                                </p>
                                <input type="text" placeholder="Enter price">
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="option-box acf_postbox">
                    <table>
                        <tr>
                            <td>
                                <p class="label">
                                    <label class="label">Option name:</label>
                                </p>
                                <input type="text" placeholder="Enter option name">
                            </td>
                            <td>
                                <p class="label">
                                    <label>Price / 1 person</label>
                                </p>
                                <input type="text" placeholder="Enter price">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div style="text-align: center" class="add-option-box">
                <i class="fa fa-plus-square-o"></i>
            </div>
        </div>

        <script>
            var $ = jQuery.noConflict();

            jQuery(document).ready(function ($) {
                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

            });

        </script>

        <?php
    }


    public function save()
    {

    }

}
