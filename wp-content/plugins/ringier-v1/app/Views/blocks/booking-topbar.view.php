<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 12-Aug-16
 * Time: 12:26 AM
 */

if (!empty($journey_id)) {

    // Journey Detail
    $journey_model = \RVN\Models\Journey::init();
    $journey_detail = $journey_model->getInfo($journey_id);
    $jt_info = $journey_detail->journey_type_info;

    // Booking detail
    $modelBooking = \RVN\Models\Booking::init();
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $cart_detail = $modelBooking->getCartInfo($user_id, $journey_detail->ID);
    } else {
        $user_id = false;
        $cart_detail = [
            'cart'                   => null,
            'room_type_twin_count'   => 0,
            'room_type_single_count' => 0,
            'total'                  => 0
        ];
    }
    ?>

    <div class="nav-bar">
        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-5">
                    <h3 class="title-main white"><?php echo $journey_detail->post_title; ?></h3>
                    <p>
                        From <?php echo $jt_info->starting_point; ?>
                        to <?php echo $jt_info->destination_info->post_title; ?>,
                        <?php echo $journey_detail->duration; ?>,
                        departure on <b><?php echo date('d M Y', strtotime($journey_detail->departure)); ?></b>
                    </p>
                </div>
                <div class="col-xs-12 col-sm-7 right">
                    <span class="total-price">Total: US$<span
                            class="booking-total"><?php echo number_format($cart_detail['total']); ?></span></span>
                    <a href="javascript:void(0)" class="btn-menu-jn">
                        <img src="<?php echo VIEW_URL . '/images/icon-menu-1.png' ?>" class="">
                    </a>
                    <span class="ctn-btn-action" style="display: none;">
                        <a href="<?php echo $journey_detail->permalink; ?>" class="btn-menu-edit">
                            <img src="<?php echo VIEW_URL . '/images/icon-edit.png' ?>">
                            <br>See journey detail
                        </a><a href="<?php echo $journey_detail->journey_type_info->permalink; ?>" class="btn-menu-info">
                            <img src="<?php echo VIEW_URL . '/images/icon-info.png' ?>">
                            <br>Edit journey
                        </a><a href="javascript:void(0)" class="btn-menu-delete">
                            <img src="<?php echo VIEW_URL . '/images/icon-delete.png' ?>">
                            <br>Delete
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>

<?php } ?>