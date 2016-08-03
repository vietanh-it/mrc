<?php
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
    exit;
}
$user_id = get_current_user_id();

get_header();
global $post;
?>
<div class="journey-detail">

    <div class="nav-bar">
        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-5">
                    <h3 class="title-main white">CLASSIC MEKONG</h3>
                    <p>From Saigon to Siem Reap, 7 nights, departure on <b>14 July 2016</b></p>
                </div>
                <div class="col-xs-12 col-sm-7 right">
                    <span class="total-price">Total: US$<span class="booking-total">0</span></span>
                    <a href="javascript:void(0)" class="btn-menu-jn"><img src="<?php echo VIEW_URL . '/images/icon-menu-1.png' ?>" class=""></a>
                        <span class="ctn-btn-action" style="display: none;">
                            <a href="#" class="btn-menu-edit"><img src="<?php echo VIEW_URL.'/images/icon-edit.png'?>"><br>See journey detail</a><a href="#" class="btn-menu-info"><img src="<?php echo VIEW_URL.'/images/icon-info.png'?>"><br>Edit journey</a><a href="#" class="btn-menu-delete"><img src="<?php echo VIEW_URL.'/images/icon-delete.png'?>"><br>Delete</a>
                        </span>
                </div>
            </div>
        </div>
    </div>

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
                            <tr>
                                <td>Upper Deck Twin Sharing</td>
                                <td>2</td>
                                <td class="text-right color-main" >US$650.00</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <b>Total:<span class="color-main" style="font-size: 17px"> US$0.0</span></b>
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
                            <tr>
                                <td>Saigon & Surroundings
                                    (InterContinental Asiana)
                                </td>
                                <td>2</td>
                                <td class="text-right color-main" >US$650.00</td>
                            </tr>

                            <!--<----- >-->
                            <tr>
                                <td colspan="3" class="text-right">
                                    <b>Total:<span class="color-main" style="font-size: 17px"> US$0.0</span></b>
                                </td>
                            </tr>
                            <tr style="background: #d5b76e">
                                <td colspan="3" class="text-right">
                                    Grand Total:<b><span style="font-size: 17px;color: white">  US$8,250.00</span></b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <b style="color: #e4a611">Deposit Due: US$8,250.00</b>
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
                                <label><input type="radio" name="payment_method" checked>Onepay - Credit Card</label>
                            </div>

                            <b class="title" style="margin: 30px 0 10px">Additional information</b>
                            <textarea class="form-control" rows="5" name="comment" value="I need vegetable food and need visa application support."></textarea>

                            <div class="checkbox">
                                <label><input type="checkbox" value="">I have read and agree to the terms & conditions</label>
                            </div>

                            <div class="text-center btt-box">
                                <a href="http://local.mrc.com/journeys" class="back">Back</a>
                                <button type="submit">Pay Deposit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php get_footer() ?>
