<?php
// if (!is_user_logged_in()) {
//     wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
//     exit;
// }
$user_id = get_current_user_id();

global $post;
$m_booking = \RVN\Models\Booking::init();
$m_ship = \RVN\Models\Ships::init();
$m_addon = \RVN\Models\Addon::init();
$m_user = \RVN\Models\Users::init();

$cart_detail = $m_booking->getCartInfo($user_id, $post->ID);

$user_info = $m_user->getUserInfo(get_current_user_id());
$billing_address = empty($user_info->address) ? '' : $user_info->address;

// Booking drop reminder
$timestamp = wp_next_scheduled('booking_drop_reminder', [$cart_detail['cart_info']->id]);
wp_unschedule_event($timestamp, 'booking_drop_reminder', [$cart_detail['cart_info']->id]);
wp_schedule_single_event(strtotime('+30 minutes'), 'booking_drop_reminder', [$cart_detail['cart_info']->id]);


// get_header();
global $post;
?>
<style>
    .b-addr-showing {
        display: block;
    }

    .b-addr-hidden {
        display: none;
    }

    .btn-edit-address {
        color: #333333;
    }
</style>

<div class="journey-detail">

    <?php view('blocks/booking-topbar', ['journey_id' => $post->ID]); ?>

    <div class="content-booking content-booking-review">
        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-12 ">
                    <p class="text-tt">
                        Review your booking
                    </p>
                </div>
            </div>
            <form id="review_form">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <b class="title color-main">Stateroom</b>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Room type</th>
                                <th>Person</th>
                                <th class="text-right">Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $stateroom_total = 0;
                            if (!empty($cart_detail['cart'])) {
                                foreach ($cart_detail['cart'] as $k => $v) {
                                    $stateroom_total += $v->total;

                                    $room_info = $m_ship->getRoomInfo($v->room_id);

                                    $room_size = '';
                                    if ($v->type == 'single') {
                                        $room_size = 'Single Use';
                                        $quantity = 1;
                                    }
                                    else {
                                        $room_size = 'Twin Sharing';
                                        $quantity = 2;
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo $room_info->room_type_name . ' ' . $room_size . ' - Room ' . $room_info->room_name; ?></td>
                                        <td><?php echo $quantity; ?></td>
                                        <td class="text-right color-main">
                                            US$<?php echo number_format($v->total); ?></td>
                                    </tr>

                                <?php }
                            } ?>

                            <tr>
                                <td colspan="3" class="text-right">
                                    <b>Total:<span class="color-main"
                                                   style="font-size: 17px"> US$<?php echo number_format($stateroom_total); ?></span></b>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                        <b class="title color-main">Extension and service addon</b>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th>Person</th>
                                <th class="text-right">Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $addon_total = 0;
                            if (!empty($cart_detail['cart_addon'])) {
                                foreach ($cart_detail['cart_addon'] as $key => $value) {
                                    $addon_info = $m_addon->getInfo($value->object_id);
                                    if (!empty($addon_info)) {
                                        $addon_total += $value->total; ?>

                                        <tr>
                                            <td>
                                                <?php echo $addon_info->post_title; ?>
                                            </td>
                                            <td>
                                                <?php echo $value->quantity; ?>
                                            </td>
                                            <td class="text-right color-main">
                                                US$<?php echo number_format($value->total); ?></td>
                                        </tr>

                                    <?php }
                                }
                            } ?>

                            <!--<----- >-->
                            <tr>
                                <td colspan="3" class="text-right">
                                    <b>Total:<span class="color-main"
                                                   style="font-size: 17px"> US$<?php echo number_format($addon_total); ?></span></b>
                                </td>
                            </tr>
                            <tr style="background: #d5b76e">
                                <td colspan="3" class="text-right">
                                    Grand Total:<b><span
                                            style="font-size: 17px;color: white">  US$<?php echo number_format($stateroom_total + $addon_total); ?></span></b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <b style="color: #e4a611">Deposit Due:
                                                              US$<?php echo number_format($stateroom_total + $addon_total); ?></b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="info-bk">
                            <div>
                                <label for="billing_address" class="title">
                                    <b>Billing address</b>
                                    <a href="javascript:void(0)" class="pull-right btn-edit-address">EDIT</a>
                                </label>
                                <p class="b-addr-showing"><?php echo $billing_address; ?></p>
                                <div class="b-addr-hidden">
                                    <input type="text" name="billing_address" id="billing_address" class="form-control" value="<?php echo $billing_address; ?>">
                                    <a href="javascript:void(0)" class="btn btn-main btn-change-bill-addr"
                                       style="margin-top: 15px;">Confirm</a>
                                </div>
                            </div>


                            <label class="title" style="margin: 30px 0 10px">
                                <b>Payment method</b>
                            </label>
                            <div class="radio">
                                <label><input type="radio" name="payment_method" checked>Credit Card</label>
                            </div>

                            <label class="title" style="margin: 30px 0 10px">
                                <b>Additional information</b>
                            </label>
                            <textarea class="form-control" rows="5" name="comment" id="additional_information"
                                      placeholder="Additional information"></textarea>

                            <div class="checkbox">
                                <label>
                                    <a href="<?php echo WP_SITEURL . '/terms-of-use'; ?>" style="color: #333333;"
                                       target="_blank"> I have read and agree to the terms & conditions</a>
                                    <input id="agree_terms" type="checkbox" value="1">
                                </label>
                            </div>

                            <div class="text-center btt-box">
                                <?php $url = strtok($_SERVER["REQUEST_URI"], '?'); ?>

                                <a href="<?php echo $url . '?step=services-addons' ?>" class="back">Back</a>
                                <a href="<?php echo $url . '?step=process&payment_type=credit_card' ?>"
                                   class="btn-main btn-pay">Pay Deposit</a>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="billing_address" value="<?php echo $billing_address; ?>">
            </form>
        </div>
    </div>
</div>

<script>
    var $ = jQuery.noConflict();
    $(document).ready(function () {
        var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

        $('.btn-pay').on('click', function (e) {
            e.preventDefault();


            var is_move = confirm('You will be moved to payment page to finish booking, are you sure?');
            if (is_move) {
                var url = $(this).attr('href');
                if (!$('#agree_terms').is(':checked')) {
                    swal({
                        title: 'Please agree terms & conditions',
                        type: 'error'
                    });
                } else {
                    switch_loading(true);

                    // Save cart addtional information ajax
                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_booking',
                            method: 'SaveAdditionalInformation',
                            cart_id: <?php echo $cart_detail['cart_info']->id; ?>,
                            additional_information: $('#additional_information').html(),
                            billing_address: $('#billing_address').val()
                        },
                        success: function (data) {
                            switch_loading(false);

                            if (data.status == 'success') {
                                window.location.href = url;
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

                    // window.location.href = url;
                }

            }

        });


        $('.btn-edit-address').on('click', function (e) {
            $('.b-addr-showing').hide();
            $('.b-addr-hidden').fadeIn();
        });


        $('.btn-change-bill-addr').on('click', function (e) {
            $('.b-addr-showing').html($('#billing_address').val());

            $('.b-addr-hidden').hide();
            $('.b-addr-showing').fadeIn();
        });


        $('#billing_address').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                $('.b-addr-showing').html($('#billing_address').val());

                $('.b-addr-hidden').hide();
                $('.b-addr-showing').fadeIn();
            }
        });


        $('#review_form').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

<?php //get_footer() ?>
