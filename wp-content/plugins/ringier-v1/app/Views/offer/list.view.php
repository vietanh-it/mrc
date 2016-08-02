<?php
get_header();
$list_offer = $list_offer ? $list_offer : array(); ?>


<?php if($list_offer['data']){ ?>
    <div class="container">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main">All Offers
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
            </h1>
            <?php foreach ($list_offer['data'] as $v){
                ?>
                <div class="col-xs-12 col-sm-4">
                    <div class="box-journey box-white">
                        <div class="image">
                            <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                <img src="<?php echo $v->images->small ?>" alt="<?php echo $v->post_title ?>" class="lazy">
                            </a>
                        </div>
                        <div style="border: 1px solid #ccc">
                            <div class="desc">
                                <a href="<?php echo $v->permalink ?>" class="title" title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                <p><?php echo cut_string_by_char(strip_tags($v->post_content),150) ?></p>
                                <p><b>Start Date:</b> <?php echo  date("j F Y", strtotime($v->start_date)); ?> <a href="<?php echo $v->permalink ?>" class="read-more" title="read more"><i class="fa fa-angle-right" aria-hidden="true"></i></a> </p>
                            </div>
                            <div class="price">  $<?php echo number_format($v->journey_info->min_price_offer) ?></div>
                            <div class="star"><i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>


<?php get_footer() ?>

