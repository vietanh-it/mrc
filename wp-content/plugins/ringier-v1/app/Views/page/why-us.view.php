<?php

get_header();
/*$list_journey_type = !empty($list_journey_type) ? $list_journey_type : array();

view('journey/quick-search');*/
?>
    <div class="journey-detail">
        <div class="featured-image" >
            <img src="<?php echo VIEW_URL.'/images/bg-news.png' ?>" alt="bg" >
        </div>
    </div>
    <div class="container">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-bottom: 40px">Why us
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
            </h1>
        </div>
    </div>

    <div class="why-us" style="padding-top: 50px">
        <div class="container ">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div class="row list-slide-mb">
                        <div class="col-xs-12 col-sm-12">
                            <div class="box-why">
                                <img src="<?php echo VIEW_URL . '/images/why-1.png' ?>" alt="">
                                <div class="desc">
                                    <p class="title">The differences</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque
                                        ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="box-why">
                                <img src="<?php echo VIEW_URL . '/images/why-2.png' ?>" alt="">
                                <div class="desc">
                                    <p class="title">Our care</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque
                                        ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="box-why">
                                <img src="<?php echo VIEW_URL . '/images/why-3.png' ?>" alt="">
                                <div class="desc">
                                    <p class="title">Ship owner and partner</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque
                                        ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center">
                <div style="margin: 80px 0">
                    <a href="<?php echo WP_SITEURL.'/journeys/' ?>" title="FIND YOUR JOURNEY" class="bnt-primary" >FIND YOUR JOURNEY</a>
                </div>
            </div>
        </div>
    </div>

<?php  get_footer() ?>