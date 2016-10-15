<?php

get_header();
/*$list_journey_type = !empty($list_journey_type) ? $list_journey_type : array();

view('journey/quick-search');*/


?>

    <div class="container">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-bottom: 40px">Why us
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
            </h1>
        </div>
    </div>

    <div class="featured-image" >
        <img src="<?php echo VIEW_URL.'/images/bg-news.png' ?>" alt="bg" style="width: 100%">
    </div>

    <div class="why-us" style="padding-top: 50px">
        <div class="container ">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div class="row list-slide-mb">
                        <?php
                        global $post;
                        for ($i= 1;$i <=3 ; $i++){
                            $title = get_post_meta($post->ID,'title'.$i,true);
                            $content = get_post_meta($post->ID,'content'.$i,true);
                            $image = get_post_meta($post->ID,'image'.$i,true);

                            $img_full= wp_get_attachment_image_src($image,'featured');
                            if($img_full) $img_full = array_shift($img_full);
                            ?>
                            <div class="col-xs-12 col-sm-12">
                                <div class="box-why">
                                    <div class="image">
                                        <img src="<?php echo $img_full ?>" alt="" class="img-main">
                                        <img src="<?php echo VIEW_URL.'/images/why-'.$i.'.png' ?>" class="img-icon">
                                    </div>
                                    <div class="desc">
                                        <p class="title"><?php echo $title ?></p>
                                        <p><?php echo $content ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php }
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