<?php

use RVN\Models\JourneyType;

get_header();
$list_journey_type = !empty($list_journey_type) ? $list_journey_type : [];

view('journey/quick-search');
?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main" style="margin: 75px 0 40px;">All Journeys
            <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="row">
                <?php if ($list_journey_type['data']) {
                    $m_jt = JourneyType::init();
                    foreach ($list_journey_type['data'] as $v) {
                        $min_price = $m_jt->getJourneyTypeMinPrice($v->ID);
                        ?>
                        <div class="col-xs-12 col-sm-4">
                            <div class="box-journey">
                                <div class="image">
                                    <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                        <img src="<?php echo $v->images->featured ?>" alt="<?php echo $v->post_title ?>"
                                             class="lazy">
                                    </a>
                                    <?php if (!empty($min_price)) { ?>
                                        <div class="price">
                                            $<?php echo number_format($min_price) ?> <br/>
                                            <?php echo number_format($min_price * CURRENCY_RATE); ?> VND
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="desc">
                                    <div class="jt-title-wrapper">
                                        <a href="<?php echo $v->permalink ?>" class="title"
                                           title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                    </div>

                                    <ul>
                                        <li><b><?php echo $v->starting_point . ' - ' . $v->destination_info->post_title; ?></b></li>
                                        <li><b><?php echo $v->duration ?></b></li>
                                    </ul>

                                    <p><?php echo cut_string_by_char($v->post_excerpt, 150) ?></p>
                                    <a href="<?php echo $v->permalink ?>" class="explore" title="">Explore</a>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (function_exists('wp_pagenavi')) {
                        wp_pagenavi([
                            'before' => '  <div class="wrap-pagination">',
                            'after'  => '</div>'
                        ]);
                    }
                }
                else { ?>
                    <div class="col-xs-12 col-sm-12" style="    margin: 0 0 20px;">No journey match found</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
