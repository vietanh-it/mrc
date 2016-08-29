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

$cart_detail = $m_booking->getCartInfo($user_id, $post->ID);

// get_header();
global $post;
?>
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
            <form>
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
                                        <td class="text-right color-main">US$<?php echo number_format($v->total); ?></td>
                                    </tr>

                                <?php }
                            } ?>

                            <tr>
                                <td colspan="3" class="text-right">
                                    <b>Total:<span class="color-main" style="font-size: 17px"> US$<?php echo number_format($stateroom_total); ?></span></b>
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
                                            <td class="text-right color-main">US$<?php echo number_format($value->total); ?></td>
                                        </tr>

                                    <?php }
                                }
                            } ?>

                            <!--<----- >-->
                            <tr>
                                <td colspan="3" class="text-right">
                                    <b>Total:<span class="color-main" style="font-size: 17px"> US$<?php echo number_format($addon_total); ?></span></b>
                                </td>
                            </tr>
                            <tr style="background: #d5b76e">
                                <td colspan="3" class="text-right">
                                    Grand Total:<b><span style="font-size: 17px;color: white">  US$<?php echo number_format($stateroom_total + $addon_total); ?></span></b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <b style="color: #e4a611">Deposit Due: US$<?php echo number_format($stateroom_total + $addon_total); ?></b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="info-bk">
                            <b class="title">Billing address</b>
                            <p style="padding-top: 7px">280 Luong Dinh Cua, An Phu Ward, District 2, HCMC, Vietnam</p>


                            <b class="title" style="margin: 30px 0 10px">Payment method</b>
                            <div class="radio">
                                <label><input type="radio" name="payment_method" checked>Credit Card</label>
                            </div>

                            <b class="title" style="margin: 30px 0 10px">Additional information</b>
                            <textarea class="form-control" rows="5" name="comment" id="additional_information">I need vegetable food and need visa application support.</textarea>

                            <div class="checkbox">
                                <label>
                                    <a href="<?php echo WP_SITEURL . '/terms-of-use'; ?>" style="color: #333333;" target="_blank"> I have read and agree to the terms & conditions</a>
                                    <input id="agree_terms" type="checkbox" value="1" checked>
                                </label>
                            </div>

                            <div class="text-center btt-box">
                                <?php $url = strtok($_SERVER["REQUEST_URI"], '?'); ?>

                                <a href="<?php echo $url . '?step=services-addons' ?>" class="back">Back</a>
                                <a href="<?php echo $url . '?step=process&payment_type=credit_card' ?>" class="btn-main btn-pay">Pay Deposit</a>
                            </div>
                        </div>
                    </div>
                </div>
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
                        additional_information: $('#additional_information').html()
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
        });
    });
</script>

<?php //get_footer() ?>
