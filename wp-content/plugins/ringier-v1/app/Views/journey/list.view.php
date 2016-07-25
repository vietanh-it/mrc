<?php

get_header();
$params = $params ? $params : array();
$list_journey = $list_journey ? $list_journey : array();

var_dump($list_journey);
 view('journey/quick-search', compact('params'));
?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">All Journeys
            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
        </h1>


        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" title="">
                        <img src="<?php echo VIEW_URL . '/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="title" title="">Laos</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum
                        dapibus...</p>
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" title="">
                        <img src="<?php echo VIEW_URL . '/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="title" title="">Vietnam</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum
                        dapibus...</p>
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" title="">
                        <img src="<?php echo VIEW_URL . '/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="title" title="">Cambodia</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum
                        dapibus...</p>
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" title="">
                        <img src="<?php echo VIEW_URL . '/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="title" title="">Laos</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum
                        dapibus...</p>
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" title="">
                        <img src="<?php echo VIEW_URL . '/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="title" title="">Vietnam</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum
                        dapibus...</p>
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" title="">
                        <img src="<?php echo VIEW_URL . '/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="title" title="">Cambodia</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum
                        dapibus...</p>
                    <a href="<?php echo WP_SITEURL . '/detail-journey' ?>" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

    </div>
</div>


<?php get_footer() ?>
