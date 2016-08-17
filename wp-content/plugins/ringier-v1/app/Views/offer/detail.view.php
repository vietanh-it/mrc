<?php
$offer_info = !empty($offer_info) ? $offer_info : array();
get_header();
?>

<div class="journey-detail">
    <div class="container container-big">
        <div class="row">
            <div class="col-xs-12 col-sm-4 list-img-left">
                <a href="<?php echo $offer_info->images->full ?>" class="fancybox"><img src="<?php echo $offer_info->images->small ?>" alt=""></a>
            </div>
            <div class="col-xs-12 col-sm-8">
                <h1 class="title-main"><?php the_title() ?></h1>
                <?php echo apply_filters('the_content',$offer_info->post_content) ?>

                <p><b>Promotion : </b> <?php echo $offer_info->promotion ?> %</p>
                <p><b>Start date : </b> <?php echo date('d M Y', strtotime($offer_info->start_date)) ?></p>
                <p><b>End date : </b> <?php echo date('d M Y', strtotime($offer_info->end_date))  ?></p>
            </div>
        </div>
        <?php if(!empty($list_journey_type_related['data'])){ ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <h3 class="title-related">Journeys have this offer</h3>

                    <div class="row">
                        <?php foreach ($list_journey_type_related['data'] as $v){ ?>
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

<?php get_footer() ?>