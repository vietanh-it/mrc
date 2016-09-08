<?php
/**
 * Template name: Demo html Detail Booking
 */

get_header();
while (have_posts()) : the_post(); ?>
    <div class="container">
        <div class="row detail-your-booking">
            <h1 class="col-xs-12 col-sm-12 tile-main">Booking Detail
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
                <br><span class="text">Please review your booking carefully</span>
                <a href="#"> <img src="<?php echo VIEW_URL .'/images/icon-question.png'?>" class="img-icon"></a>
            </h1>
            <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                <div class="row">
                    <div class="col-xs-6 col-sm-6" style="white-space: nowrap">
                        <div class="tt-left">Booking ID</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 " >
                        <b>MP160816-1</b>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 " >
                        <div class="tt-left" >Booking date</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 " >
                        MP160816-1
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 " >
                        <div class="tt-left">Departure date</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 " >
                        MP160816-1
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 " >
                        <div class="tt-left">From - to</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 " >
                        MP160816-1
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 " >
                        <div class="tt-left">Length</div>
                    </div>
                    <div class="col-xs-6 col-sm-6" >
                        MP160816-1
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6" >
                        <div class="tt-left" >Journey</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 " >
                        MP160816-1
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6" >
                        <div class="tt-left" >Ship</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 " >
                        MP160816-1
                    </div>
                </div>
                <div class="row" style="background: #e1e1e1;font-weight: bold">
                    <div class="col-xs-6 col-sm-6 " >
                        <div class="tt-left" >Travellers</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 " >
                        2
                    </div>
                </div>
                <div class="row">



                    <div class="col-xs-6 col-sm-6 " >
                        <a href="#"><img src="<?php echo VIEW_URL .'/images/icon-edit-2.png'?>" style="margin-right: 20%"></a> <b>Dong Tran</b>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="line-height: 25px" >
                        Passport ID: <b>B88868</b><br>
                        Date of issue: <b>24 July 2015</b><br>
                        Valid until: <b>24 July 2025</b><br>
                        Birthday: <b>20 July 1980</b><br>
                        Gender: <b>Male</b><br>
                        Address: <b>15 5th Avenue, New York, MA999595</b><br>
                        Nationality: <b>Vietnamese</b><br>
                        Country: <b>USA</b><br>
                        Special note: <b>Vegeterian. Allergic with peanut</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 " >
                        <div class="tt-left"><b>Thao Pham</b></div>
                    </div>
                    <div class="col-xs-6 col-sm-6" >
                        Passport ID: <b>B88868</b>
                    </div>
                </div>




                <div class="row" style="background: #d5b76e;font-weight: bold;color: white;margin-top: 10px;margin-bottom: 10px">
                    <div class="col-xs-6 col-sm-6 " >
                        <div class="tt-left">Payment</div>
                    </div>
                    <div class="col-xs-6 col-sm-6" >
                        US$968
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 "  >
                        <div class="tt-left"><b>Status</b></div>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="color: #d5b76e">
                        Befor you go
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 text-center" style="margin : 50px 0">
                <p style="margin-bottom: 30px;font-weight: bold">Share your feeling to inspire people on TripAdvisor</p>
                <img src="<?php echo VIEW_URL.'/images/icon-green-booking.png'?>" style="">
            </div>
        </div>
    </div>
<?php endwhile;
get_footer();