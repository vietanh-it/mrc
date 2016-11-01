<?php

get_header();
$list_post = !empty($list_post) ? $list_post : array();
?>

    <div class="container">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-bottom: 40px; margin-top: 0;">Why us
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
            </h1>
        </div>
    </div>

<?php
$slider_page = get_page_by_path(PAGE_HOME_SLIDER_SLUG);
$cover_id = get_post_meta($slider_page->ID,'cover_whyus',true);
if($cover_id){
    $cover= wp_get_attachment_image_src($cover_id,'full');
    if($cover){ $cover = array_shift($cover);
        ?>
        <div class="featured-image cover-img" >
            <img src="<?php echo $cover ?>" alt="bg" style="width: 100%">
        </div>
    <?php }
} ?>

    <!--<div style="background: #d5b76e;">-->
    <!--    <div class="row">-->
    <!--        <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-bottom: 0">Why us-->
    <!--            <br> <img src="--><?php //echo VIEW_URL . '/images/line.png' ?><!--">-->
    <!--        </h1>-->
    <!--    </div>-->
    <!--</div>-->

    <div class="why-us" style="padding-top: 50px">
        <div class="container ">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div class="row list-slide-mb">
                        <?php
                        if(!empty($list_post['data'])){
                            foreach ($list_post['data'] as $k => $p){
                                $i = $k + 1;
                                if($i > 3) $i =3;
                                ?>
                                <div class="col-xs-12 col-sm-12">
                                    <div class="box-why">
                                        <div class="image">
                                            <a href="<?php echo $p->permalink ?>" > <img src="<?php echo $p->images->featured ?>" alt="" class="img-main"></a>
                                            <img src="<?php echo VIEW_URL.'/images/why-'.$i.'.png' ?>" class="img-icon">
                                        </div>
                                        <div class="desc">
                                            <p class="title"><a href="<?php echo $p->permalink ?>" > <?php echo $p->post_title ?></a></p>
                                            <p><?php echo  cut_string_by_char(strip_tags($p->post_content),150) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center">
                <div style="margin: 80px 0">
                    <a href="<?php echo WP_SITEURL.'/journeys/' ?>" title="FIND YOUR JOURNEY" class="bnt-primary" >FIND YOUR JOURNEY</a>
                </div>
            </div>
        </div>
    </div>

<?php  get_footer() ?>