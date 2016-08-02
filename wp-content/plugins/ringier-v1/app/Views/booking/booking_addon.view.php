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
                <div class="col-xs-12 col-sm-6">
                    <h3 class="title-main white">CLASSIC MEKONG</h3>
                    <p>From Saigon to Siem Reap, 7 nights, departure on <b>14 July 2016</b></p>
                </div>
                <div class="col-xs-12 col-sm-6 right">
                    <span class="total-price">Total: US$<span class="booking-total">0</span></span>
                    <a href="javascript:void(0)" class="btn-menu-jn"><img
                            src="<?php echo VIEW_URL . '/images/icon-menu-1.png' ?>" class=""></a>
                </div>
            </div>
        </div>
    </div>

    <div class="content-booking">
        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-12 ">
                    <p class="text-tt">
                        Check availability and book online <span>Please select guests and starterooms</span>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="booking-addon">
                        <div class="title">
                            Pre-cruise extensions
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3">
                                <div class="images">
                                    <img src="<?php echo VIEW_URL.'/images/laos.png' ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-9">
                                <div class="desc">
                                    <p><b>Saigon & Surroundings (InterContinental Asiana)</b></p>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-9">
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
                                                    <td>US$325.00</td>
                                                    <td> <a class="action-quantity" href="javascript:void(0)">-</a> <span style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span> <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                                    <td>US$650.00</td>
                                                </tr>
                                                <tr>
                                                    <td>One Person</td>
                                                    <td>US$640.00</td>
                                                    <td> <a class="action-quantity" href="javascript:void(0)">-</a> <span style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span> <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                                    <td>US$0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: right;padding-right: 10%">
                                                        <b>Total</b>
                                                    </td>
                                                    <td><b>US$0.0</b></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            Add this service?
                                            <a class="add-addon" href="#">
                                                Yes, please
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-xs-12 col-sm-12">
                    <div class="booking-addon">
                        <div class="title">
                            Pre-cruise extensions
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3">
                                <div class="images">
                                    <img src="<?php echo VIEW_URL.'/images/laos.png' ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-9">
                                <div class="desc">
                                    <p><b>Saigon & Surroundings (InterContinental Asiana)</b></p>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-9">
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
                                                    <td>US$325.00</td>
                                                    <td> <a class="action-quantity" href="javascript:void(0)">-</a> <span style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span> <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                                    <td>US$650.00</td>
                                                </tr>
                                                <tr>
                                                    <td>One Person</td>
                                                    <td>US$640.00</td>
                                                    <td> <a class="action-quantity" href="javascript:void(0)">-</a> <span style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span> <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                                    <td>US$0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: right;padding-right: 10%">
                                                        <b>Total</b>
                                                    </td>
                                                    <td><b>US$0.0</b></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            Add this service?
                                            <a class="add-addon" href="#">
                                                Yes, please
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
