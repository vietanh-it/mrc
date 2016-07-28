<?php

get_header();
global $post;

$journey_ctrl = \RVN\Controllers\JourneyController::init();
$journey_detail = $journey_ctrl->getJourneyDetail($post->ID);
// var_dump($journey_detail);
?>

    <div class="journey-detail">

        <div class="nav-bar">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3 class="title-main white"><?php the_title(); ?></h3>
                        <p>From Saigon to Siem Reap, <?php echo $journey_detail->duration; ?>, departure on <b><?php echo date('d M Y', strtotime($journey_detail->departure)); ?></b></p>
                    </div>
                    <div class="col-xs-12 col-sm-6 right">
                        <span class="total-price">Total: US$8,250</span>
                        <a href="javascript:void(0)" class="btn-menu-jn"><img
                                src="<?php echo VIEW_URL . '/images/icon-menu-1.png' ?>" class=""></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-booking">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 "><p class="text-tt">Check availability and book online <span>Please select guests and starterooms
</span></p></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-7">
                        <div class="img-ship">
                            <p>Mekong Princess Deck Plan</p>
                            <img src="<?php echo VIEW_URL . '/images/ship_maps/mekong_princess.jpg' ?>" alt="">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-5">
                        <div class="booking-info">
                            <form>
                                <div class="bk-box " style="padding: 20px 30px">
                                    <span style="text-transform: uppercase;font-weight: bold">Stateroom Prices</span>
                                    (per
                                    person, including any discount)
                                </div>
                                <div class="bk-box bk-box-gray">
                                    <span class="text">Main Deck Twin Share</span> <span class="price"><span
                                            class="big">US$2,750</span> <br> US$3,250</span>
                                </div>

                                <div class="bk-box ">
                                    <span class="text">Main Deck Twin Single Use</span> <span class="price"><span
                                            class="big">US$2,750</span> <br> US$3,250</span>
                                </div>

                                <div class="bk-box bk-box-gray">
                                    <span class="text">Upper Deck Twin Share</span> <span class="price"><span
                                            class="big">US$2,750</span> <br> US$3,250</span>
                                </div>

                                <div class="bk-box ">
                                    <span class="text">Upper Deck Twin Single Us</span> <span class="price"><span
                                            class="big">US$2,750</span> <br> US$3,250</span>
                                </div>


                                <div class="bk-box bk-box-2" style="background: #d5b76e;margin-top: 50px">
                                <span
                                    style="text-transform: uppercase;font-weight: bold">Your stateroom selection</span>
                                    <span class="price-2">Total: <b>US$8,250</b></span>
                                </div>
                                <div class="bk-box bk-box-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <span class="text">Main Deck Twin Share</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            2 persons
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <span class="price-2">US$<b>5,500</b></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bk-box bk-box-gray bk-box-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <span class="text">Main Deck Twin Single Use</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            2 persons
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <span class="price-2">US$<b>5,500</b></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bk-box  bk-box-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <span class="text">Upper Deck Twin Share</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            2 persons
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <span class="price-2">US$<b>5,500</b></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bk-box bk-box-gray bk-box-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <span class="text">Upper Deck Twin Single Us</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            2 persons
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <span class="price-2">US$<b>5,500</b></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center btt-box">
                                    <a href="#" class="back">Back</a>
                                    <button type="submit">Continue</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer();
