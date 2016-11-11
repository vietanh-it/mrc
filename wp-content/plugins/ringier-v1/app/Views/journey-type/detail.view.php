<?php
get_header();

$min_price = valueOrNull($min_price, 0);

if (!empty($journey_type_info)) { ?>

    <div class="journey-detail">
        <div class="featured-image">
            <a href="<?php echo $journey_type_info->images->full ?>" class="fancybox"><img
                    src="<?php echo $journey_type_info->images->full ?>"
                    alt="<?php echo $journey_type_info->post_title ?>"></a>

            <div class="container hide-on-med-and-up" style="margin-top: 30px">
                <div class="row">
                    <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-top: 0;"><?php echo the_title() ?>
                        <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
                    </h1>
                </div>
            </div>

            <div class="container container-big">
                <div class="info">
                    <h2>
                        <?php echo limitWords($journey_type_info->post_content, 55); ?>
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
                    <span>from US$<span
                            class="price-if"><?php echo number_format($min_price) ?></span> per person</span>
                </div>
            </div>

        </div>

        <div class="container hide-on-med-and-down" style="margin-top: 30px">
            <div class="row">
                <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-top: 0;"><?php echo the_title() ?>
                    <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
                </h1>
            </div>
        </div>

        <div class="container container-big" id="ctn-list-journey" style="display: none"
             data-img_ticket="<?php echo VIEW_URL . '/images/icon-ticket.png' ?>">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="ctn-list-journey hide-on-med-and-down">
                        <div class="title">Check availability and book online
                            <span>Prices shown are per person in USD and include all discounts.</span></div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Departure date</th>
                                <th>From - to</th>
                                <th>Ship</th>
                                <th style="display: none;">
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
                                            <b><?php echo date('d M Y', strtotime($v->departure)); ?></b>
                                        </td>
                                        <td>
                                            <?php echo $jt_info->starting_point ?>
                                            - <?php echo $jt_info->destination_info->post_title; ?>
                                            <br>
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
                                            <a href="<?php echo $jt_info->ship_info->permalink; ?>" target="_blank"
                                               style="color: rgb(84, 84, 84);">
                                                <?php echo $jt_info->ship_info->post_title; ?>
                                            </a>
                                        </td>

                                        <td style="display: none;"> <?php echo $v->navigation; ?></td>

                                        <td> from

                                            <?php if (!empty($offer) && ($j_min_price->min_price > $j_min_price->min_price_offer)) { ?>
                                                <span
                                                    style="text-decoration: line-through;color: burlywood; padding-right: 5px;">
                                                US$<?php echo number_format($j_min_price->min_price); ?>
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
                                    <td colspan="6"> No journey is available at the moment</td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="ctn-list-journey-mb hide-on-med-and-up">
                        <?php if (!empty($journey_list['data'])) {

                            $m_journey = \RVN\Models\Journey::init();
                            $m_offer = \RVN\Models\Offer::init();

                            foreach ($journey_list['data'] as $k => $v) {
                                $jt_info = $v->journey_type_info;
                                $offer = $m_offer->getOfferByJourney($v->ID);
                                $j_min_price = $m_journey->getJourneyMinPrice($v->ID, true);
                                ?>

                                <div class="panel-heading" role="tab" id="heading_<?php echo $k + 1 ?>">
                                    <div class="panel-title be-travel">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse_<?php echo $k + 1 ?>" aria-expanded="true"
                                           aria-controls="collapseOne">
                                            <?php echo date('d M Y', strtotime($v->departure)); ?>
                                        </a>
                                    </div>
                                </div>

                                <div id="collapse_<?php echo $k + 1 ?>"
                                     class="panel-collapse collapse in ctn-show-hide-traveller" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <b>Departure </b>:
                                            </div>
                                            <div class="col-xs-8">
                                                <?php echo date('d M Y', strtotime($v->departure)); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <b>From - to </b>
                                            </div>
                                            <div class="col-xs-8">
                                                <?php echo $jt_info->starting_point ?>
                                                - <?php echo $jt_info->destination_info->post_title; ?>
                                                <br>
                                                <?php echo $jt_info->duration; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <b>Ship </b>
                                            </div>
                                            <div class="col-xs-8">
                                                <a href="<?php echo $jt_info->ship_info->permalink; ?>" target="_blank"
                                                   style="color: #e4a611;">
                                                    <?php echo $jt_info->ship_info->post_title; ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <b>Offer </b>
                                            </div>
                                            <div class="col-xs-8">
                                                <?php if (!empty($offer)) { ?>

                                                    <b>
                                                        <?php echo $offer->post_title ?> <br>
                                                        Book by <?php echo $v->departure_fm; ?>
                                                        and save <?php echo $offer->offers[0]->promotion; ?>%.
                                                    </b>

                                                    <img src="<?php echo VIEW_URL ?>/images/icon-ticket.png">

                                                <?php }
                                                else {
                                                    echo 'none';
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <b>Price </b>
                                            </div>
                                            <div class="col-xs-8">
                                                from

                                                <?php if (!empty($offer) && ($j_min_price->min_price > $j_min_price->min_price_offer)) { ?>
                                                    <span
                                                        style="text-decoration: line-through;color: burlywood; padding-right: 5px;">
                                                US$<?php echo number_format($j_min_price->min_price); ?>
                                            </span>
                                                <?php } ?>

                                                <b style="color: #e4a611">
                                                    US$<?php echo number_format($j_min_price->min_price_offer); ?>
                                                </b> pp
                                                <br>based on <?php echo $j_min_price->type; ?> cabin
                                            </div>
                                        </div>
                                        <div class="select-mb">
                                            <a href="<?php echo $v->permalink; ?>" class="bnt-jn">Select</a>
                                        </div>
                                    </div>

                                </div>
                            <?php }
                        }
                        else { ?>
                            <div class="panel-heading" role="tab" id="heading_1">
                                <div class="panel-title be-travel">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion"
                                       href="#collapse_1" aria-expanded="true"
                                       aria-controls="collapseOne">
                                        No journeys available
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
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

                    <?php if (!empty($journey_type_info->map_image)) { ?>
                        <h3 class="title-main">Journey map</h3>
                        <a href="<?php echo $journey_type_info->map_image ?>" class="fancybox">
                            <img src="<?php echo $journey_type_info->map_image ?>" alt="">
                        </a>
                    <?php } ?>

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

                <?php if (!empty($journey_type_info->itinerary)) { ?>
                    <div class="col-xs-12 col-sm-8">
                        <h3 class="title-main">Itinerary</h3>
                        <?php
                        if (!empty($journey_type_info->related_journey_type)) {
                            $related = get_permalink($journey_type_info->related_journey_type); ?>
                            <a href="<?php echo $related ?>" class="btn-jt-navigation">
                                <?php echo $journey_type_info->navigation == 'upstream' ? 'Downstream' : 'Upstream'; ?>
                            </a>
                        <?php }

                        $itinerary = ($journey_type_info->itinerary);
                        foreach ($itinerary as $k => $it) {
                            if (!empty($it->day)) {
                                $extra_text = '';
                                if ($k == 0) {
                                    $extra_text = '/ EMBARKATION';
                                }
                                if ($k == (count($itinerary) - 1)) {
                                    $extra_text = '/ DISEMBARKATION';
                                }

                                $title_lc = ' ';
                                if(!empty($it->location_info) && is_array($it->location_info)){
                                    $total_lc = count($it->location_info);
                                    foreach ($it->location_info as $kc => $lc){
                                        if($kc+1 >= $total_lc){
                                            $title_lc .= $lc->post_title;
                                        }else{
                                            $title_lc .= $lc->post_title .', ';
                                        }
                                    }
                                }
                                ?>
                                <div class="box-day-in">
                                    <div class="day-in">
                                        DAY <?php echo $it->day ?> <?php echo $title_lc ?> <?php echo $extra_text; ?></div>
                                    <p><?php echo apply_filters('the_content', $it->content) ?></p>
                                    <?php
                                    if(!empty($it->location_info) && is_array($it->location_info)) {
                                        foreach ($it->location_info as  $lc) { ?>
                                            <a href="<?php echo $lc->permalink ?>" class="see-more-lc">See more
                                                about <?php echo $lc->post_title ?>  </a> <br>
                                        <?php }
                                    }
                                    ?>

                                </div>
                            <?php }
                        } ?>

                        <?php if (!empty($journey_type_info->include)) { ?>
                            <div class="title-main">WHAT’S INCLUDED</div>
                            <p>
                                <?php echo apply_filters('the_content', $journey_type_info->include) ?>
                            </p>
                        <?php } ?>

                    </div>
                <?php } ?>

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

            var jid = jhash.substr(3);

            setTimeout(function () {
                $('html, body').animate({
                    scrollTop: $('#ctn-list-journey').offset().top - 50
                }, 500);
                $('[data-jid="' + jid + '"]').css('background', '#f5dda5');
            }, 1000);

            setTimeout(function () {
                $('[data-jid="' + jid + '"]').attr('style', '');
            }, 2500);

        }

    });
</script>