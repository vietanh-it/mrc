<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 29-Aug-16
 * Time: 3:17 PM
 */

namespace RVN\Hooks;

class BoxJourneySeries
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new BoxJourneySeries();
        }

        return self::$instance;
    }


    function __construct()
    {
        add_action('add_meta_boxes', [$this, 'boxJourneySeries']);
        add_action('save_post', [$this, 'save']);
    }


    public function boxJourneySeries()
    {
        add_meta_box('box_journey_series', 'Journey Series', [$this, 'show'], 'journey_series', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        ?>
        <style>
            .single-journey:not(:first-child) {
                border-top: 1px dashed #dddddd;
                padding-top: 10px;
                margin-top: 10px;
            }

            .item-wrapper {
                padding: 25px 40px;
                border: 1px solid #dddddd;
                border-radius: 5px;
                margin-top: 25px;
            }

            .item-wrapper h3 {
                margin: 0;
            }

            .form-group {
                margin: 10px auto;
            }
        </style>

        <!--General Information-->
        <div class="general-info">
            <h3>
                General Information
            </h3>
            <div class="form-group">
                <label>Journey Type</label>
                <select>
                    <option>--- Select Journey Type ---</option>
                    <option>Classic Mekong</option>
                    <option>Classic Mekong 2</option>
                </select>
            </div>
            <div class="form-group">
                <label>Journey Prefix</label>
                <input type="text" placeholder="Enter journey prefix">
            </div>
            <div class="form-group">
                Journey Duration <span>5</span> nights
            </div>
        </div>


        <div class="item-wrapper">
            <h3>Journey Series</h3>

            <!--Single Journey-->
            <div class="single-journey">
                <div class="form-group">
                    <label>Journey Code:</label>
                    <span class="journey-code">
                    <span class="prefix">AB</span>1234
                </span>
                </div>
                <div class="form-group">
                    <label>Depature:</label>
                    <input type="date">
                </div>
                <div class="form-group">
                    <label>Navigation</label>
                    <select>
                        <option>Upstream</option>
                        <option>Downstream</option>
                    </select>
                </div>
            </div>
        </div>

        <button class="btn-add-new-item button button-primary button-large" style="margin-top: 20px;">Add new Journey</button>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

                $('.btn-add-new-item').on('click', function (e) {
                    e.preventDefault();

                    var html = singleJourneySeries();
                    $('.item-wrapper').append(html);
                });
            });

            function singleJourneySeries() {
                return '<div class="single-journey">' +
                    '<div class="form-group">' +
                    '<label>Journey Code:</label>' +
                    '<span class="journey-code">' +
                    '<span class="prefix">AB</span>1234' +
                    '</span>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Depature:</label>' +
                    '<input type="date">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Navigation</label>' +
                    '<select>' +
                    '<option>Upstream</option>' +
                    '<option>Downstream</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>';
            }
        </script>

        <?php
    }


    public function save()
    {

    }

}