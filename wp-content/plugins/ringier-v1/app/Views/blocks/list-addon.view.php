<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 10:47 AM
 * Block: Item Offer
 */

if (!empty($list_addon)) {
    var_dump($list_addon);
    $addon_model = \RVN\Models\Addon::init();
    $cart_id = valueOrNull($cart_id, 0);

    foreach ($list_addon as $key => $item) {
        if ($item->type == 'addon' && empty($item->addon_option)) {
            break;
        }

        $cart_addons = $addon_model->getCartAddon($cart_id, $item->ID);
        if (!empty($cart_addons)) {
            $cart_addon_status = $cart_addons[0]->status;
        } else {
            $cart_addon_status = 'inactive';
        }
        ?>

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
                                                        <td>US$<?php echo !empty($v->price) ? number_format($v->price) : 0; ?></td>
                                                        <td data-type="addon" data-addon-object-id="<?php echo $v->object_id ?>" data-addon-option="<?php echo $v->id ?>">

                                                            <a href="javascript:void(0)" class="action-quantity" data-action-type="minus">-</a>

                                                            <span style="padding: 0 10px; float: left;" class="option-person">
                                                                <?php echo valueOrNull($addon->quantity, 0); ?>
                                                            </span>

                                                            <a href="javascript:void(0)" class="action-quantity" data-action-type="plus">+</a>

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

                                        $tour_total = 0;
                                        if (!empty($tour_info_twin->total)) {
                                            $tour_total += $tour_info_twin->total;
                                        }
                                        if (!empty($tour_info_single->total)) {
                                            $tour_total += $tour_info_single->total;
                                        } ?>

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
                                                <td data-type="twin-tour" data-addon-object-id="<?php echo $item->ID ?>">

                                                    <a href="javascript:void(0)" class="action-quantity" data-action-type="minus">-</a>

                                                    <span style="float: left; padding: 0 10px;">
                                                        <?php echo !empty($tour_info_twin->quantity) ? ($tour_info_twin->quantity) : 0; ?>
                                                    </span>

                                                    <a href="javascript:void(0)" class="action-quantity" data-action-type="plus">+</a>

                                                </td>
                                                <td>US$<?php echo !empty($tour_info_twin->total) ? number_format(valueOrNull($tour_info_twin->total, 0)) : 0; ?></td>
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
                                                <td>US$<?php echo !empty($tour_info_single->total) ? number_format(valueOrNull($tour_info_single->total, 0)) : 0; ?></td>
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
                                    <?php if ($cart_addon_status == 'inactive') { ?>
                                        <a class="add-addon" data-object-id="<?php echo $item->ID; ?>" href="javascript:void(0)">
                                            Yes, please
                                        </a>
                                    <?php } else { ?>
                                        <a class="add-addon active" data-object-id="<?php echo $item->ID; ?>" href="javascript:void(0)">
                                            No, thanks
                                        </a>
                                    <?php } ?>
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
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

            var switch_status = true;

            // [data-object-id] click
            $(document).delegate('[data-object-id]', 'click', function (e) {
                e.preventDefault();
                var object_id = $(this).attr('data-object-id');

                if (switch_status) {
                    switch_status = false;

                    // Switch addon status ajax
                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_booking',
                            method: 'SwitchAddonStatus',
                            object_id: object_id,
                            cart_id: <?php echo $cart_id ?>
                        },
                        success: function (data) {

                            if (data.status == 'success') {
                                switch_status = true;
                                $('.booking-total').html(data.data.cart_total_text);

                                // process button
                                if (data.data.current_status == 'active') {
                                    console.log(data.data);
                                    $('[data-object-id="' + object_id + '"]').html('No, thanks').addClass('active');
                                } else {
                                    $('[data-object-id="' + object_id + '"]').html('Yes, please').removeClass('active');
                                }
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
                var parent = $(this).parent('td');

                var type = parent.attr('data-type'); // addon | single-tour | twin-tour
                var action_type = $(this).attr('data-action-type'); // minus | plus
                var object_id = parent.attr('data-addon-object-id');
                var addon_option = parent.attr('data-addon-option');

                console.log(type, action_type, object_id, addon_option);

                // Save cart addon ajax
                $.ajax({
                    url: ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'ajax_handler_booking',
                        method: 'SaveAddon',
                        cart_id: <?php echo $cart_id ?>,
                        object_id: object_id,
                        addon_option_id: addon_option,
                        action_type: action_type,
                        addon_type: type
                    },
                    success: function (data) {

                        if (data.status == 'success') {
                            switch_status = true;
                            console.log(data.data);
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