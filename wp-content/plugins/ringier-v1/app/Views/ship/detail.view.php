<?php
$ship_detail = !empty($ship_detail) ? $ship_detail : array();
get_header();
//var_dump($ship_detail);
?>
<div class="journey-detail">
    <div class="container container-big">
        <div class="row">
            <div class="col-xs-12 col-sm-4 list-img-left">
                <h3 class="title-main">Map</h3>
                <a href="<?php echo $ship_detail->map ?>" class="fancybox"><img src="<?php echo $ship_detail->map ?>" alt=""></a>

                <?php if($ship_detail->gallery){ ?>
                    <h3 class="title-main">List gallery</h3>
                    <div class="list-galary">
                        <?php foreach ($ship_detail->gallery as $g){ ?>
                            <div><a href="<?php echo $g->full ?>" class="fancybox" rel="ship photos"><img src="<?php echo $g->small ?>"></a></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-xs-12 col-sm-8">
                <h1 class="title-main" ><?php the_title() ?></h1>
                <?php echo apply_filters('the_content',$ship_detail->post_content) ?>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3>Basic specification</h3>
                        <?php echo apply_filters('the_content',$ship_detail->basic_specs) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <h3>Accommodation</h3>
                        <?php echo apply_filters('the_content',$ship_detail->accommodation) ?>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xs-12 col-sm-6">
                        <h3>Wining & dining</h3>
                        <?php echo apply_filters('the_content',$ship_detail->wining_dining) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <h3>Onboard staff</h3>
                        <?php echo apply_filters('the_content',$ship_detail->onboard_staff) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3>On excursion</h3>
                        <?php echo apply_filters('the_content',$ship_detail->on_excursion) ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <h3>Safety & Security</h3>
                        <?php echo apply_filters('the_content',$ship_detail->safety_security) ?>
                    </div>
                </div>
                <h3>Public spaces</h3>
                <?php echo apply_filters('the_content',$ship_detail->public_spaces) ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer() ?>
