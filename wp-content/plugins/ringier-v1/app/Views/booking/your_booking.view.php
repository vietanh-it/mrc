<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 08-Sep-16
 * Time: 11:08 PM
 */


get_header();
while (have_posts()) : the_post(); ?>
    <div class="container">
        <div class="row detail-your-booking">
            <h1 class="col-xs-12 col-sm-12 tile-main">Booking Detail
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
            </h1>

            <div class="col-xs-12 col-sm-12">
                <div class="ctn-list-journey" style="padding-top: 0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Departure date</th>
                            <th>From - to</th>
                            <th>Journey</th>
                            <th>SHip</th>
                            <th>Travellers</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Ask for help</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="upstream">
                            <td><b>MP160816-4</b></td>
                            <td>1 September 2016</td>
                            <td>Saigon - Phnom Penh 7 nights</td>
                            <td style="text-decoration: underline"><a href="http://local.mrc.com/ship/mekong-princess"
                                                                      target="_blank" style="color: rgb(84, 84, 84);">Mekong
                                                                                                                      Princess</a>
                            </td>
                            <td style="text-decoration: underline">
                                <a href="http://local.mrc.com/ship/mekong-princess"
                                   target="_blank" style="color: rgb(84, 84, 84);">Mekong
                                                                                   Princess</a></td>
                            <td> 2</td>
                            <td>
                                <b style="color: black;font-size: 17px;text-transform: uppercase">US$968</b>
                            </td>
                            <td>
                                <span style="color: #e4a611">Before you go</span>
                            </td>
                            <td style="text-align: center"><a href="#">
                                    <img src="<?php echo VIEW_URL . '/images/icon-question.png' ?>" class="img-icon"></a>
                            </td>
                        </tr>
                        <tr class="upstream">
                            <td><b>MP160816-4</b></td>
                            <td>1 September 2016</td>
                            <td>Saigon - Phnom Penh 7 nights</td>
                            <td style="text-decoration: underline"><a href="http://local.mrc.com/ship/mekong-princess"
                                                                      target="_blank" style="color: rgb(84, 84, 84);">Mekong
                                                                                                                      Princess</a>
                            </td>
                            <td style="text-decoration: underline">
                                <a href="http://local.mrc.com/ship/mekong-princess"
                                   target="_blank" style="color: rgb(84, 84, 84);">Mekong
                                                                                   Princess</a></td>
                            <td> 2</td>
                            <td>
                                <b style="color: black;font-size: 17px;text-transform: uppercase">US$968</b>
                            </td>
                            <td>
                                <span style="color: #e4a611">Before you go</span>
                            </td>
                            <td style="text-align: center"><a href="#">
                                    <img src="<?php echo VIEW_URL . '/images/icon-question.png' ?>" class="img-icon"></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 text-center" style="margin : 50px 0">
                <p style="margin-bottom: 30px;font-weight: bold">Share your feeling to inspire people on TripAdvisor</p>
                <img src="<?php echo VIEW_URL . '/images/icon-green-booking.png' ?>" style="">
            </div>
        </div>
    </div>
<?php endwhile;
get_footer();