<?php
get_header();
$journey_type_info =  !empty($journey_type_info) ? $journey_type_info : $journey_type_info = '';
if($journey_type_info){ ?>

    <div class="journey-detail">
        <div class="featured-image">
            <a href="<?php echo $journey_type_info->images->full ?>" class="fancybox"><img src="<?php echo $journey_type_info->images->full ?>" alt="<?php echo $journey_type_info->post_title ?>" ></a>

            <div class="container container-big">
                <div class="info">
                    <h1><?php the_title() ?></h1>
                    <h2>
                        <?php echo $journey_type_info->post_content ?>
                    </h2>
                    <ul>
                        <li><b>7 nights 6 days</b></li>
                        <li><b>Promotion:</b> Save up to 20% on selected dates</li>
                    </ul>
                    <a href="javascript:void(0)" class="btn-show-journey" data-journey_type="<?php echo $journey_type_info->ID ?>">choose your date</a>
                    <span>from <span class="price-if">US$1,755</span> pp</span>
                </div>
            </div>

        </div>

        <div class="container container-big" id="ctn-list-journey" style="display: none" data-img_ticket="<?php echo VIEW_URL.'/images/icon-ticket.png' ?>">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="ctn-list-journey">
                        <div class="title">Check availability and book online <span>Prices shown are per person in USD$ and include all discounts.</span></div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Departure date</th>
                                <th>From - to</th>
                                <th>Ship</th>
                                <th>All | Upstream | Downstream</th>
                                <th><b>Price</b></th>
                                <th> </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-4 list-img-left">
                    <?php if($journey_type_info->gallery){ ?>
                    <h3 class="title-main">Journey photos</h3>
                    <div class="list-galary">
                        <?php foreach ($journey_type_info->gallery as $g){
                            ?>
                            <div><a href="<?php echo $g->full ?>" class="fancybox" rel="Journey photos"><img src="<?php echo $g->small ?>"></a></div>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <h3 class="title-main">Journey map</h3>
                    <a href="<?php echo $journey_type_info->map_image ?>" class="fancybox"><img src="<?php echo $journey_type_info->map_image ?>" alt=""></a>

                    <?php if($journey_type_info->ship_info->gallery){ ?>
                    <h3 class="title-main">The ship</h3>
                    <div class="list-galary">
                        <?php foreach ($journey_type_info->ship_info->gallery as $g){ ?>
                            <div><a href="<?php echo $g->full ?>" class="fancybox" rel="ship photos"><img src="<?php echo $g->small ?>"></a></div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>

                <div class="col-xs-12 col-sm-8">
                    <h3 class="title-main">Itinerary</h3>
                    <?php if(!empty($journey_type_info->itinerary)){
                        $itinerary = unserialize($journey_type_info->itinerary);
                        foreach ($itinerary as $it){ ?>
                            <div class="box-day-in">
                                <div class="day-in">DAY <?php echo $it['day_name'] ?> <?php echo $it['day_port'] ?></div>
                                <p><?php echo apply_filters('the_content',$it['day_content']) ?></p>
                            </div>
                        <?php }
                    } ?>
                    <div class="title-main">WHAT’S INCLUDED</div>
                    <p><b>Cruise Price Includes:</b> <?php echo $journey_type_info->include ?></p>
                </div>
            </div>
            <?php if(!empty($list_add_on['data'])){ ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <h3 class="title-related">Extensions and Service Addons</h3>

                        <div class="row">
                            <?php foreach ($list_add_on['data'] as $v){ ?>
                                <div class="col-xs-12 col-sm-2">
                                    <div class="related">
                                        <div class="images">
                                            <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                                <img src="<?php echo $v->images->featured ?>" alt="<?php echo $v->post_title ?>">
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
?>
<?php get_footer() ?>
