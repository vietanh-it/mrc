<?php
get_header();
$list_offer = !empty($list_offer) ? $list_offer : array();
$JourneyType = \RVN\Controllers\JourneyTypeController::init();
?>


<?php if($list_offer['data']){ ?>
    <div class="container">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main">All Offers
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
            </h1>
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <?php foreach ($list_offer['data'] as $v){
                        $min_price = 0;
                        if(!empty($v->journey_type_id)){
                            $journey_min_price = $JourneyType->getJourneyMinPrice($v->journey_type_id,'offer');
                            if(!empty($journey_min_price) && !empty($journey_min_price->min_price_offer)){
                                $min_price =  $journey_min_price->min_price_offer;
                            }
                        }

                        ?>
                        <div class="col-xs-12 col-sm-4">
                            <div class="box-journey box-white">
                                <div class="image" style="position: relative">
                                    <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                        <img src="<?php echo $v->images->small ?>" alt="<?php echo $v->post_title ?>" class="lazy">
                                    </a>
                                    <div class="price">  $<?php echo number_format($min_price) ?></div>
                                </div>
                                <div style="border: 1px solid #ccc">
                                    <div class="desc">
                                        <a href="<?php echo $v->permalink ?>" class="title" title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                        <p><?php echo cut_string_by_char(strip_tags($v->post_content),150) ?></p>
                                        <p><b>Start Date:</b> <?php echo  date("j F Y", strtotime($v->start_date)); ?> <a href="<?php echo $v->permalink ?>" class="read-more" title="read more"><i class="fa fa-angle-right" aria-hidden="true"></i></a> </p>
                                    </div>
                                    <div class="star"><i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (function_exists('wp_pagenavi')) wp_pagenavi(array(
                        'before' => '  <div class="wrap-pagination">',
                        'after' => '</div>'
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<?php get_footer() ?>

