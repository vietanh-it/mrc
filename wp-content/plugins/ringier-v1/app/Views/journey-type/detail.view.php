<?php
get_header();
$journey_type_info = valueOrNull($journey_type_info);
$journey_min_price = valueOrNull($journey_min_price);

$min_price = $journey_min_price->min_price;
if (!empty($journey_min_price->min_price_offer)) {
    $min_price = $journey_min_price->min_price_offer;
}

if (!empty($journey_type_info)) { ?>

    <div class="journey-detail">
        <div class="featured-image">
            <a href="<?php echo $journey_type_info->images->full ?>" class="fancybox"><img
                    src="<?php echo $journey_type_info->images->full ?>"
                    alt="<?php echo $journey_type_info->post_title ?>"></a>

            <div class="container container-big">
                <div class="info">
                    <h1><?php the_title() ?></h1>
                    <h2>
                        <?php echo $journey_type_info->post_content ?>
                    </h2>
                    <ul>
                        <li><b><?php echo $journey_type_info->duration ?></b></li>
                        <?php if (!empty($journey_type_info->offer_main_info)) { ?>
                            <li>
                                <b>Promotion:</b> Save up
                                                  to <?php echo $journey_type_info->offer_main_info->promotion ?>% on selected dates
                            </li>
                        <?php } ?>
                    </ul>
                    <a href="javascript:void(0)" class="btn-show-journey"
                       data-journey_type="<?php echo $journey_type_info->ID ?>">choose your date</a>
                    <span>from <span class="price-if"><?php echo number_format($min_price) ?></span> pp</span>
                </div>
            </div>

        </div>

        <div class="container container-big" id="ctn-list-journey" style="display: none"
             data-img_ticket="<?php echo VIEW_URL . '/images/icon-ticket.png' ?>">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="ctn-list-journey">
                        <div class="title">Check availability and book online
                            <span>Prices shown are per person in USD$ and include all discounts.</span></div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Departure date</th>
                                <th>From - to</th>
                                <th>Ship</th>
                                <th>
                                    <a href="javascript:void(0)" class="order-navigation active" data-navigation="all">All</a>
                                    |
                                    <a href="javascript:void(0)" class="order-navigation" data-navigation="upstream">Upstream</a>
                                    |
                                    <a href="javascript:void(0)" class="order-navigation" data-navigation="downstream">Downstream</a>
                                </th>
                                <th><b>Price</b></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if (!empty($journey_list['data'])) {

                                $m_journey = \RVN\Models\Journey::init();
                                $m_offer = \RVN\Models\Offer::init();

                                foreach ($journey_list['data'] as $k => $v) {
                                    $jt_info = $v->journey_type_info;
                                    $offer = $m_offer->getOfferByJourney($v->ID);
                                    $j_min_price = $m_journey->getJourneyMinPrice($v->ID, true); ?>


                                    <tr class="<?php echo $v->navigation; ?>" data-jid="<?php echo $v->ID; ?>">
                                        <td>
                                            <b><?php echo $v->departure_fm; ?></b>
                                        </td>
                                        <td>
                                            <?php echo $v->journey_type_info->starting_point ?><br>
                                            <?php echo $jt_info->duration; ?><br>

                                            <?php if (!empty($offer)) { ?>

                                                <b>
                                                    <?php echo $offer->post_title ?> <br>
                                                    Book by <?php echo $v->departure_fm; ?>
                                                    and save <?php echo $offer->offers[0]->promotion; ?>%.
                                                </b>

                                                <img src="<?php echo VIEW_URL ?>/images/icon-ticket.png">

                                            <?php } ?>
                                        </td>
                                        <td style="text-decoration: underline">
                                            <a href="<?php echo $jt_info->ship_info->permalink; ?>" target="_blank" style="color: rgb(84, 84, 84);">
                                                <?php echo $jt_info->ship_info->post_title; ?>
                                            </a>
                                        </td>

                                        <td> <?php echo $v->navigation; ?></td>

                                        <td> from

                                            <?php if (!empty($offer)) { ?>
                                                <span style="text-decoration: line-through;color: burlywood; padding-right: 5px;">
                                                $<?php echo number_format($j_min_price->min_price); ?>
                                            </span>
                                            <?php } ?>

                                            <b style="color: #e4a611">
                                                US$<?php echo number_format($j_min_price->min_price_offer); ?>
                                            </b> pp
                                            <br>based on <?php echo $j_min_price->type; ?> cabin
                                        </td>
                                        <td>
                                            <a href="<?php echo $v->permalink; ?>" class="bnt-jn">Select</a>
                                            Some availability
                                        </td>
                                    </tr>

                                <?php }
                            }
                            else { ?>
                                <tr>
                                    <td colspan="6"> No result is found</td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-4 list-img-left">
                    <?php if ($journey_type_info->gallery) { ?>
                        <h3 class="title-main">Journey photos</h3>
                        <div class="list-galary">
                            <?php foreach ($journey_type_info->gallery as $g) {
                                ?>
                                <div>
                                    <a href="<?php echo $g->full ?>" class="fancybox" rel="Journey photos">
                                        <img src="<?php echo $g->small ?>">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <h3 class="title-main">Journey map</h3>
                    <a href="<?php echo $journey_type_info->map_image ?>" class="fancybox">
                        <img src="<?php echo $journey_type_info->map_image ?>" alt="">
                    </a>

                    <?php if ($journey_type_info->ship_info->gallery) { ?>
                        <h3 class="title-main">The ship</h3>
                        <div class="list-galary">
                            <?php foreach ($journey_type_info->ship_info->gallery as $g) { ?>
                                <div>
                                    <a href="<?php echo $g->full ?>" class="fancybox" rel="ship photos"><img
                                            src="<?php echo $g->small ?>"></a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="col-xs-12 col-sm-8">
                    <h3 class="title-main">Itinerary</h3>
                    <?php
                    if (!empty($journey_type_info->itinerary)) {
                        $itinerary = ($journey_type_info->itinerary);
                        foreach ($itinerary as $it) {
                            ?>
                            <div class="box-day-in">
                                <div class="day-in">
                                    DAY <?php echo $it->day ?> <?php echo $it->location_info->post_title ?></div>
                                <p><?php echo apply_filters('the_content', $it->content) ?></p>
                            </div>
                        <?php }
                    } ?>
                    <div class="title-main">WHAT’S INCLUDED</div>
                    <p><b>Cruise Price Includes:</b> <?php echo $journey_type_info->include ?></p>
                </div>
            </div>
            <?php if (!empty($list_add_on['data'])) { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <h3 class="title-related">Extensions and Service Addons</h3>

                        <div class="row">
                            <?php foreach ($list_add_on['data'] as $v) { ?>
                                <div class="col-xs-12 col-sm-2">
                                    <div class="related">
                                        <div class="images">
                                            <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                                <img src="<?php echo $v->images->featured ?>"
                                                     alt="<?php echo $v->post_title ?>">
                                            </a>
                                        </div>
                                        <div class="title">
                                            <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                                <?php echo $v->post_title ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
<?php }

get_footer() ?>

<script>
    var $ = jQuery.noConflict();
    $(document).ready(function ($) {

        // Scroll to jid
        var jhash = location.hash;
        if (jhash.substr(0, 3) == '#j=') {

            $('#ctn-list-journey').fadeIn();


            setTimeout(function () {
                $('html, body').animate({
                    scrollTop: $('#ctn-list-journey').offset().top - 50
                }, 500);

                var jid = jhash.substr(3);
                $('[data-jid="' + jid + '"]').css('background', '#f5dda5').attr('style', '');
            }, 1000);

        }
    });
</script>