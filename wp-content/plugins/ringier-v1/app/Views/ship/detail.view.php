<?php
$ship_detail = !empty($ship_detail) ? $ship_detail : array();
get_header();
?>
<div class="journey-detail">
    <div class="container container-big">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="info-ship">
                    <?php echo apply_filters('the_content',$ship_detail->post_excerpt) ?>
                </div>
            </div>
        </div>

    </div>
    <div class="featured-image" style="margin-bottom: 200px">
        <a href="<?php echo $ship_detail->images->full ?>" class="fancybox"><img src="<?php echo $ship_detail->images->full ?>" alt="<?php echo $ship_detail->post_title ?>" ></a>
    </div>

    <div class="container container-big">
        <div class="row">
            <div class="col-xs-12 col-sm-4 list-img-left">
                <?php if($ship_detail->gallery){ ?>
                    <h3 class="title-main">Ship photos</h3>
                    <div class="list-galary">
                        <?php foreach ($ship_detail->gallery as $g){ ?>
                            <div><a href="<?php echo $g->full ?>" class="fancybox" rel="ship photos"><img src="<?php echo $g->small ?>"></a></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <h3 class="title-main">Ship video</h3>
                <iframe src="https://www.youtube.com/embed/<?php echo $ship_detail->youtube_id ?>"
                        frameborder="0" allowfullscreen=""
                        class="embed-responsive-item" style="width: 100%;min-height: 250px"></iframe>


                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <h3>Basic specification</h3>
                        <?php echo apply_filters('the_content',$ship_detail->basic_specs) ?>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <h3>Accommodation</h3>
                        <?php echo apply_filters('the_content',$ship_detail->accommodation) ?>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xs-12 col-sm-12">
                        <h3>Wining & dining</h3>
                        <?php echo apply_filters('the_content',$ship_detail->wining_dining) ?>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <h3>Onboard staff</h3>
                        <?php echo apply_filters('the_content',$ship_detail->onboard_staff) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <h3>On excursion</h3>
                        <?php echo apply_filters('the_content',$ship_detail->on_excursion) ?>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <h3>Safety & Security</h3>
                        <?php echo apply_filters('the_content',$ship_detail->safety_security) ?>
                    </div>
                </div>
                <h3>Public spaces</h3>
                <?php echo apply_filters('the_content',$ship_detail->public_spaces) ?>
            </div>
            <div class="col-xs-12 col-sm-8">
                <?php echo apply_filters('the_content',$ship_detail->post_content) ?>

            </div>
        </div>
    </div>
</div>
<?php get_footer() ?>
