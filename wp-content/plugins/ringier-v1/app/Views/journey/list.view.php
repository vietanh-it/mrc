<?php

get_header();

 view('journey/quick-search', compact('params'));
?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">Your expected journeys
        </h1>

        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="row">
                <?php if(!empty($list_journey['data'])){
                    foreach ($list_journey['data'] as $v){
                        //var_dump($v);
                        $m_offer = \RVN\Models\Offer::init();
                        $offer = $m_offer->getOfferByJourney($v->ID);
                        ?>
                        <div class="col-xs-12 col-sm-12">
                            <div class="box-journey-2">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="images">
                                            <a href="<?php echo $v->permalink ?>" title="<?php echo $v->journey_type_info->post_title ?>">
                                                <img src="<?php echo $v->journey_type_info->images->small ?>" alt="<?php echo $v->journey_type_info->post_title ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="desc">
                                            <a href="<?php echo $v->permalink ?>" title="<?php echo $v->journey_type_info->post_title ?>" class="title">
                                                <?php echo $v->journey_type_info->post_title ?>
                                            </a>
                                            <p><?php
                                                if(!empty($v->journey_type_info->post_excerpt)){
                                                    $content = strip_tags($v->journey_type_info->post_excerpt);
                                                    echo cut_string_by_char($content,200);
                                                }
                                                ?>
                                            </p>
                                            <ul>
                                                <li><b>Departure :</b> <?php echo $v->departure_fm ?></li>
                                                <li><b><?php echo $v->journey_type_info->duration ?></b></li>
                                                <?php if(!empty($offer)){ ?>
                                                    <li><b>Promotion:</b>   <?php echo $offer->post_title ?>
                                                        Book by <?php echo $v->departure_fm; ?>
                                                        and save <?php echo $offer->offers[0]->promotion; ?>%. <img src="<?php echo  VIEW_URL.'/images/icon-ticket.png'?>"></li>
                                                <?php } ?>
                                            </ul>
                                            <a href="<?php echo $v->permalink ?>" class="explore">Explore Now >></a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2">
                                        <div class="bk">
                                            <p style="margin-bottom: 0;">from US$<b><?php echo $v->min_price ?></b> pp</p>
                                            <p><b><?php echo number_format($v->min_price * CURRENCY_RATE) ?></b> VND pp</p>
                                            <a href="<?php echo $v->permalink ?>">Book now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (function_exists('wp_pagenavi')) wp_pagenavi(array(
                        'before' => '  <div class="wrap-pagination">',
                        'after' => '</div>'
                    ));
                }
                else{ ?>
                    <div class="col-xs-12 col-sm-12" style="margin: 0 0 20px;">No result match found</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<?php get_footer() ?>
