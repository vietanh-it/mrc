<?php
get_header();
!empty($journey_type_info) ? $journey_type_info = $journey_type_info : $journey_type_info = '';
if($journey_type_info){ ?>

    <div class="journey-detail">
        <div class="featured-image">
            <img src="<?php echo $journey_type_info->images->full ?>" alt="<?php echo $journey_type_info->post_title ?>" >

            <div class="info">
                <h1><?php the_title() ?></h1>
                <h2>
                    <?php the_excerpt() ?>
                </h2>
                <ul>
                    <li><b>7 nights 6 days</b></li>
                    <li><b>Promotion:</b> Save up to 20% on selected dates</li>
                </ul>
                <a href="javascript:void(0)" class="btn-show-journey" data-journey_type="<?php echo $journey_type_info->ID ?>">choose your date</a>
                <span>from <span class="price-if">US$1,755</span> pp</span>
            </div>
        </div>

        <div class="container container-big" id="ctn-list-journey" style="display: none">
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
                            <div><img src="<?php echo $g->featured ?>"></div>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <h3 class="title-main">Journey map</h3>
                    <img src="<?php echo $journey_type_info->map_image ?>" alt="">

                    <?php if($journey_type_info->ship_info->gallery){ ?>
                    <h3 class="title-main">The ship</h3>
                    <div class="list-galary">
                        <?php foreach ($journey_type_info->ship_info->gallery as $g){ ?>
                            <div><img src="<?php echo $g->featured ?>"></div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>

                <div class="col-xs-12 col-sm-8">
                    <h3 class="title-main">Itinerary</h3>
                    <?php echo apply_filters('the_content',$journey_type_info->post_content) ?>
                </div>
            </div>
            <?php if($list_add_on['data']){ ?>
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
