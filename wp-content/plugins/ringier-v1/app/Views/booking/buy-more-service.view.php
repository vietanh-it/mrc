<?php
$booking = \RVN\Models\Booking::init();
$booking_ctrl = \RVN\Controllers\BookingController::init();

if(!empty($_GET['booking_id'])) {

    $booking_id = $_GET['booking_id'];

    global $post;
    $journey_id = $post->ID;
    $user_id = get_current_user_id();
    $booking_detail = $booking->getBookingDetail($booking_id);

    $journey_ctrl = \RVN\Controllers\JourneyController::init();
    $journey_detail = $journey_ctrl->getJourneyDetail($journey_id);


    $addon_model = \RVN\Models\Addon::init();
    $addon_list = $addon_model->getList(['journey_type_id' => $journey_detail->journey_type_info->ID]);

//var_dump($addon_list);
    get_header();

    ?>

    <div class="journey-detail">

        <?php //view('blocks/booking-topbar', ['journey_id' => $booking_detail->journey_id]);
        ?>

        <div class="content-booking">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 ">
                        <p class="text-tt">
                            Check availability and book online <span>Would you like extension and service addons?</span>
                        </p>
                    </div>
                </div>

                <!--List-->
                <div class="row">

                    <?php
                    if (!empty($addon_list['data'])) {
                        $list_addon = $addon_list['data'];
                        if (!empty($list_addon)) {
                            $addon_model = \RVN\Models\Addon::init();
                            $booking_id = valueOrNull($booking_id, 0);
                            foreach ($list_addon as $key => $item) {
                            if ($item->type == 'addon' && empty($item->addon_option)) {
                                break;
                            }
                            $cart_addon_status = 'buy-more';
                            /*$cart_addons = $addon_model->getCartAddon($booking_id, $item->ID);
                            if (!empty($cart_addons)) {
                                $cart_addon_status = $cart_addons[0]->status;
                            }
                            else {
                                $cart_addon_status = 'inactive';
                            }*/
                            ?>

                            <!--Single Item-->
                            <div class="col-xs-12 col-sm-12">
                                <div class="booking-addon">

                                    <!--Title-->
                                    <div class="title">
                                        <?php
                                        switch (strtolower($item->type)) {
                                            case 'pre-tour':
                                                echo 'PRE-CRUISE EXTENTIONS';
                                                break;
                                            case 'post-tour':
                                                echo 'POST-CRUISE EXTENTIONS';
                                                break;
                                            case 'addon':
                                            default:
                                                echo 'CRUISE ADDON';
                                                break;
                                        } ?>
                                    </div>

                                    <div class="row">

                                        <!--Image-->
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="images">
                                                <img src="<?php echo $item->images->small; ?>" alt="<?php echo $item->post_title; ?>">
                                            </div>
                                        </div>

                                        <!--Options-->
                                        <div class="col-xs-12 col-sm-9">
                                            <div class="desc">
                                                <p><b><?php echo $item->post_title; ?></b></p>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12">

                                                        <?php if ($item->type == 'addon') {
                                                            $addon_options = $addon_model->getAddonOptions($item->ID); ?>

                                                            <!--Addon-->
                                                            <table class="table" data-item-id="<?php echo $item->ID; ?>" data-status="<?php echo $cart_addon_status; ?>">
                                                                <thead>
                                                                <tr>
                                                                    <th>Option</th>
                                                                    <th>Price per person</th>
                                                                    <th>Person</th>
                                                                    <th>Sub Total</th>
                                                                </tr>
                                                                </thead>

                                                                <tbody>

                                                                <?php $addon_total = 0;
                                                                if (!empty($addon_options)) {

                                                                    foreach ($addon_options as $k => $v) {
                                                                        // Cart addon
                                                                        $addon = $addon_model->getCartAddon($booking_id, $v->object_id, $v->id);
                                                                        $addon = array_shift($addon);

                                                                        // Addon options total
                                                                        $addon_total += valueOrNull($addon->total, 0); ?>

                                                                        <tr>
                                                                            <td><?php echo $v->option_name; ?></td>
                                                                            <td>US$<?php echo !empty($v->option_price) ? number_format($v->option_price) : 0; ?></td>
                                                                            <td data-type="addon" data-addon-object-id="<?php echo $v->object_id ?>" data-addon-option="<?php echo $v->id ?>">

                                                                                <a href="javascript:void(0)" class="action-quantity" data-action-type="minus">-</a>

                                                            <span style="padding: 0 10px; float: left;" class="option-person">
                                                                <?php echo /*valueOrNull($addon->quantity, 0);*/ 0 ?>
                                                            </span>

                                                                                <a href="javascript:void(0)" class="action-quantity" data-action-type="plus">+</a>

                                                                            </td>
                                                                            <td>
                                                                                US$<span class="addon-subtotal"><?php echo number_format(valueOrNull($addon->total, 0)); ?></span>
                                                                            </td>
                                                                        </tr>

                                                                    <?php }
                                                                } ?>

                                                                <tr>
                                                                    <td colspan="3" style="text-align: right;padding-right: 10%">
                                                                        <b>Total</b>
                                                                    </td>
                                                                    <td>
                                                                        <b>
                                                                            US$<span class="addon-total"><?php echo /*number_format($addon_total);*/ 0 ?></span>
                                                                        </b>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                        <?php }
                                                        else {
                                                            // Twin
                                                            $tour_info_twin = $addon_model->getCartAddon($booking_id, $item->ID, 0, 'twin');
                                                            if (!empty($tour_info_twin)) {
                                                                $tour_info_twin = array_shift($tour_info_twin);
                                                            }

                                                            // Single
                                                            $tour_info_single = $addon_model->getCartAddon($booking_id, $item->ID, 0, 'single');
                                                            if (!empty($tour_info_single)) {
                                                                $tour_info_single = array_shift($tour_info_single);
                                                            }

                                                            $tour_total = 0;
                                                            if (!empty($tour_info_twin->total)) {
                                                                $tour_total += $tour_info_twin->total;
                                                            }
                                                            if (!empty($tour_info_single->total)) {
                                                                $tour_total += $tour_info_single->total;
                                                            } ?>

                                                            <!--Tour-->
                                                            <table class="table" data-item-id="<?php echo $item->ID; ?>" data-status="<?php echo $cart_addon_status; ?>">
                                                                <thead>
                                                                <tr>
                                                                    <th>Option</th>
                                                                    <th>Price per person</th>
                                                                    <th>Person</th>
                                                                    <th>Sub Total</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>Sharing</td>
                                                                    <td>US$<?php echo number_format($item->twin_share_price); ?></td>
                                                                    <td data-type="twin-tour" data-addon-object-id="<?php echo $item->ID ?>">

                                                                        <a href="javascript:void(0)" class="action-quantity" data-action-type="minus">-</a>

                                                    <span style="float: left; padding: 0 10px;">
                                                        <?php echo /*!empty($tour_info_twin->quantity) ? ($tour_info_twin->quantity) : 0; */ 0?>
                                                    </span>

                                                                        <a href="javascript:void(0)" class="action-quantity" data-action-type="plus">+</a>

                                                                    </td>
                                                                    <td>
                                                                        US$<span class="addon-subtotal"><?php echo  /*echo !empty($tour_info_twin->total) ? number_format(valueOrNull($tour_info_twin->total, 0)) : 0;*/ 0 ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>One Person</td>
                                                                    <td>US$<?php echo number_format($item->single_price); ?></td>
                                                                    <td data-type="single-tour" data-addon-object-id="<?php echo $item->ID ?>">

                                                                        <a href="javascript:void(0)" class="action-quantity" data-action-type="minus">-</a>

                                                    <span style="float: left; padding: 0 10px;">
                                                        <?php echo !empty($tour_info_single->quantity) ? valueOrNull($tour_info_single->quantity) : 0; ?>
                                                    </span>

                                                                        <a href="javascript:void(0)" class="action-quantity" data-action-type="plus">+</a>

                                                                    </td>
                                                                    <td>US$<span class="addon-subtotal"><?php echo /*!empty($tour_info_single->total) ? number_format(valueOrNull($tour_info_single->total, 0)) : 0;*/ 0 ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3" style="text-align: right;padding-right: 10%">
                                                                        <b>Total</b>
                                                                    </td>
                                                                    <td>
                                                                        <b>
                                                                            US$<span class="addon-total"><?php echo /*number_format($tour_total);*/ 0 ?></span>
                                                                        </b>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                        <?php } ?>

                                                    </div>
                                                    <!--<div class="col-xs-12 col-sm-3">
                                                        Add this service?
                                                        <?php /*//if ($cart_addon_status == 'inactive') { */?>
                                                            <a class="add-addon" data-object-id="<?php /*echo $item->ID; */?>" href="javascript:void(0)">
                                                                Yes, please
                                                            </a>
                                                    </div>-->
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end item -->

                        <?php } ?>

                            <script>
                                var $ = jQuery.noConflict();
                                $(document).ready(function () {
                                    var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

                                    var switch_status = true;

                                    // [data-object-id] click
                                    $(document).delegate('[data-object-id]', 'click', function (e) {
                                        e.preventDefault();
                                        var object_id = $(this).attr('data-object-id');

                                        if (switch_status) {
                                            switch_status = false;
                                            switch_loading(true);

                                            // Switch addon status ajax
                                            $.ajax({
                                                url: ajax_url,
                                                type: 'post',
                                                dataType: 'json',
                                                data: {
                                                    action: 'ajax_handler_booking',
                                                    method: 'SaveSessionBuyMoreService',
                                                    object_id: object_id,
                                                    cart_id: <?php echo $booking_id ?>
                                                },
                                                success: function (data) {
                                                    switch_loading(false);

                                                    if (data.status == 'success') {
                                                        switch_status = true;
                                                        $('[data-object-id="' + object_id + '"]').html('No, thanks').addClass('active');
                                                    }
                                                    else {
                                                        var html_msg = '<div>';
                                                        if (data.message) {
                                                            $.each(data.message, function (k_msg, msg) {
                                                                html_msg += msg + "<br/>";
                                                            });
                                                        } else if (data.data) {
                                                            $.each(data.data, function (k_msg, msg) {
                                                                html_msg += msg + "<br/>";
                                                            });
                                                        }
                                                        html_msg += "</div>";
                                                        swal({"title": "Error", "text": html_msg, "type": "error", html: true});
                                                    }
                                                }
                                            }); // end ajax
                                        }

                                    });

                                    // change quantity
                                    $('.action-quantity').on('click', function (e) {
                                        var current_element = $(this);
                                        var parent = $(this).parent('td');

                                        var type = parent.attr('data-type'); // addon | single-tour | twin-tour
                                        var action_type = $(this).attr('data-action-type'); // minus | plus
                                        var object_id = parent.attr('data-addon-object-id');
                                        var addon_option = parent.attr('data-addon-option');

                                        // Save cart addon ajax
                                        $.ajax({
                                            url: ajax_url,
                                            type: 'post',
                                            dataType: 'json',
                                            data: {
                                                action: 'ajax_handler_booking',
                                                method: 'SaveAddon',
                                                cart_id: <?php echo $booking_id ?>,
                                                object_id: object_id,
                                                addon_option_id: addon_option,
                                                action_type: action_type,
                                                addon_type: type,
                                                addon_status: parent.parents('table').attr('data-status')
                                            },
                                            success: function (data) {

                                                if (data.status == 'success') {
                                                    switch_status = true;

                                                    // Quantity
                                                    parent.find('span').html(data.data.quantity);

                                                    // Subtotal
                                                    parent.parent('tr').find('.addon-subtotal').html(data.data.total);

                                                    // Addon total
                                                    getAddonTotal(parent.parents('table').attr('data-item-id'));

                                                    // Cart total
                                                    $('.booking-total').html(data.data.cart_total);
                                                }
                                                else {
                                                    var html_msg = '<div>';
                                                    if (data.message) {
                                                        $.each(data.message, function (k_msg, msg) {
                                                            html_msg += msg + "<br/>";
                                                        });
                                                    } else if (data.data) {
                                                        $.each(data.data, function (k_msg, msg) {
                                                            html_msg += msg + "<br/>";
                                                        });
                                                    }
                                                    html_msg += "</div>";
                                                    swal({"title": "Error", "text": html_msg, "type": "error", html: true});
                                                }
                                            }
                                        }); // end ajax

                                    });

                                });

                                function getAddonTotal(item_id) {
                                    var table = $('table[data-item-id="' + item_id + '"]');
                                    var subtotal_elements = table.find('.addon-subtotal');
                                    var total = 0;

                                    $.each(subtotal_elements, function (k, v) {
                                        total += parseFloat($(v).html());
                                    });

                                    table.find('.addon-total').html(total);
                                }
                            </script>

                        <?php }
                    } ?>

                    <div class="text-center btt-box">
                        <?php $url = strtok($_SERVER["REQUEST_URI"], '?'); ?>

                        <a href="<?php echo WP_SITEURL.'/before-you-go/?id='.$booking_id; ?>" class="back">Back</a>
                        <a href="<?php echo $journey_detail->permalink . '?step=buy-more-service-review&booking_id='.$booking_id; ?>" class="btn-main">Continue</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    get_footer();
}
?>