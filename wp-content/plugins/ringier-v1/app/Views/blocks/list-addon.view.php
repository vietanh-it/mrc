<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 10:47 AM
 * Block: Item Offer
 */

if (!empty($list_addon)) {
    $addon_model = \RVN\Models\Addon::init();
    $cart_id = valueOrNull($cart_id);

    $addon_model->switchAddonStatus($cart_id, 105);
    foreach ($list_addon as $key => $item) { ?>

        <!--Single Item-->
        <div class="col-xs-12 col-sm-12">
            <div class="booking-addon">

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
                            echo 'CRUISE ADDON';
                            break;
                        default:
                            echo 'CRUISE ADDON';
                            break;
                    } ?>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <div class="images">
                            <img src="<?php echo $item->images->small; ?>" alt="<?php echo $item->post_title; ?>">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-9">
                        <div class="desc">
                            <p><b><?php echo $item->post_title; ?></b></p>
                            <div class="row">
                                <div class="col-xs-12 col-sm-9">
                                    <?php if ($item->type == 'addon') {
                                        $addon_options = $addon_model->getAddonOptions($item->ID); ?>

                                        <!--Addon-->
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Option</th>
                                                <th>Price per person</th>
                                                <th>Person</th>
                                                <th>Sub Total</th>
                                            </tr>
                                            </thead>

                                            <tbody>

                                            <?php if (!empty($addon_options)) {
                                                $addon_total = 0;
                                                foreach ($addon_options as $k => $v) {
                                                    $addon = $addon_model->getCartAddon($cart_id, $v->object_id, $v->id);
                                                    $addon = array_shift($addon);
                                                    $addon_total += valueOrNull($addon->total, 0); ?>
                                                    <tr>
                                                        <td><?php echo $v->option_name; ?></td>
                                                        <td>US$<?php echo number_format($v->price); ?></td>
                                                        <td>
                                                            <a href="javascript:void(0)" class="action-quantity minus">-</a>
                                                            <span style="padding: 0 10px; float: left;" class="option-person">
                                                            <?php echo valueOrNull($addon->quantity, 0); ?>
                                                        </span>
                                                            <a href="javascript:void(0)" class="action-quantity plus">+</a>
                                                        </td>
                                                        <td>
                                                            US$<?php echo number_format(valueOrNull($addon->total, 0)); ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } ?>

                                            <tr>
                                                <td colspan="3" style="text-align: right;padding-right: 10%">
                                                    <b>Total</b>
                                                </td>
                                                <td><b>US$<?php echo number_format($addon_total); ?></b></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    <?php } else {
                                        // Twin
                                        $tour_info_twin = $addon_model->getCartAddon($cart_id, $item->ID, 0, 'twin');
                                        if (!empty($tour_info_twin)) {
                                            $tour_info_twin = array_shift($tour_info_twin);
                                        }

                                        // Single
                                        $tour_info_single = $addon_model->getCartAddon($cart_id, $item->ID, 0, 'single');
                                        if (!empty($tour_info_single)) {
                                            $tour_info_single = array_shift($tour_info_single);
                                        }

                                        $tour_total = valueOrNull($tour_info_twin->total,
                                                0) + valueOrNull($tour_info_single->total, 0); ?>

                                        <!--Tour-->
                                        <table class="table">
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
                                                <td>
                                                    <a class="action-quantity" href="javascript:void(0)">-</a>
                                                    <span style="float: left; padding: 0 10px;"><?php echo valueOrNull($tour_info_twin->quantity); ?></span>
                                                    <a href="javascript:void(0)" class="action-quantity">+</a>
                                                </td>
                                                <td>US$<?php echo number_format(valueOrNull($tour_info_twin->total, 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>One Person</td>
                                                <td>US$<?php echo number_format($item->single_price); ?></td>
                                                <td>
                                                    <a class="action-quantity" href="javascript:void(0)">-</a>
                                                    <span style="float: left; padding: 0 10px;"><?php echo valueOrNull($tour_info_single->quantity); ?></span>
                                                    <a href="javascript:void(0)" class="action-quantity">+</a>
                                                </td>
                                                <td>US$<?php echo number_format(valueOrNull($tour_info_single->total, 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="text-align: right;padding-right: 10%">
                                                    <b>Total</b>
                                                </td>
                                                <td><b>US$<?php echo number_format($tour_total); ?></b></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    <?php } ?>

                                </div>
                                <div class="col-xs-12 col-sm-3">
                                    Add this service?
                                    <a class="add-addon" data-addon-id="<?php echo $item->ID; ?>" href="javascript:void(0)">
                                        Yes, please
                                    </a>
                                </div>
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

            // [data-addon-id] click
            $(document).delegate('[data-addon-id]', 'click', function (e) {
                e.preventDefault();
                var addon_id = $(this).attr('data-addon-id');

                // Add to cart ajax
                $.ajax({
                    url: ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'ajax_handler_booking',
                        method: 'SwitchAddonStatus',
                        object_id: addon_id
                    },
                    beforeSend: function () {
                        // $('input, select', $('.room-info')).attr('disabled', true).css('opacity', 0.5);
                    },
                    success: function (data) {
                        // $('input, select', $('.room-info')).attr('disabled', false).css('opacity', 1);

                        if (data.status == 'success') {
                            booking_ready = true;

                            // TOTAL
                            $('.booking-total').html(data.data.booking_total_text);
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
    </script>

<?php }