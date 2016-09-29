<?php

get_header();
$list_post = !empty($list_post) ? $list_post : array();
$list_rs_people = !empty($list_rs_people) ? $list_rs_people : array();
$list_rs_services = !empty($list_rs_services) ? $list_rs_services : array();
$list_rs_food = !empty($list_rs_food) ? $list_rs_food : array();
/*var_dump($list_rs_people);
var_dump($list_rs_services);
var_dump($list_rs_food);*/
?>
<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">resources
            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
        </h1>
    </div>
</div>
<?php if(!empty($list_post['data'])){ ?>
    <div class="featured-rs">
        <div class="row">
            <div class="list-galary-3">
                <?php foreach ($list_post['data'] as $v){ ?>
                    <div class="col-xs-12 col-sm-12">
                        <div class="box-ft-rs" style="position: relative">
                            <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                <img src="<?php echo $v->images->small ?>" alt="<?php echo $v->post_title ?>">
                            </a>
                            <a href="<?php echo $v->permalink ?>" class="title" title="<?php echo $v->post_title ?>"> <?php echo $v->post_title ?> </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>


<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 main-content">
            <?php if(!empty($list_rs_people['data'])){
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <h3 class="title-sm">Mekong Princess Team</h3>
                    </div>
                    <?php foreach ($list_rs_people['data'] as $v){
                        $position = get_post_meta($v->ID,'position',true);
                        ?>
                        <div class="rs-20 box-rs-people">
                            <div class="image">
                                <img src="<?php echo $v->images->featured ?>" width="100%">
                            </div>
                            <div class="title"><?php echo $v->post_title ?></div>
                            <div class="job"><?php echo !empty($position) ? $position : '' ?></div>
                            <div class="desc">
                                <?php echo strip_tags($v->post_content) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if(!empty($list_rs_services['data'])){ ?>
                <div class="row box-rs-2">
                    <div class="col-xs-12 col-sm-12">
                        <h3 class="title-sm">Onboard Services</h3>
                    </div>
                    <?php foreach ($list_rs_services['data'] as $v){ ?>
                        <div class="col-xs-12 col-sm-4">
                            <div class="box-journey">
                                <div class="image">
                                    <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                        <img src="<?php echo $v->images->small ?>" alt="<?php echo $v->post_title ?>" class="lazy">
                                    </a>
                                </div>
                                <div class="desc">
                                    <a href="<?php echo $v->permalink ?>" class="title" title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                    <p><?php echo cut_string_by_char(strip_tags($v->post_content),150) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if(!empty($list_rs_food['data'])){ ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <h3 class="title-sm">Food and Beverage</h3>
                    </div>

                    <div class="col-xs-6 col-sm-6">
                        <div class="row">
                            <?php foreach ($list_rs_food['data'] as $kf => $v){ ?>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="box-smal-food">
                                        <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                            <img src="<?php echo $v->images->featured ?>" alt="<?php echo $v->post_title ?>" class="lazy">
                                        </a>
                                        <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                            <?php echo $v->post_title ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
                                if($kf == 2){
                                    echo '<div class ="clearfix"></div>';
                                }
                            } ?>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        <div class="list-galary-2">
                            <?php foreach ($list_rs_food['data'] as $v) {
                                $gallery = $v->gallery;
                                if (!empty($gallery)) {
                                    ?>
                                    <div>
                                        <?php foreach ($gallery as $kg => $img) { ?>
                                            <a <?php echo $kg != 0 ? "style ='display:none'" : '' ?>
                                                href="<?php echo $img->full ?>" title="<?php echo $img->caption ?>"
                                                class="fancybox" rel="list_room_<?php echo $v->ID ?>">
                                                <img src="<?php echo $img->featured ?>" alt="" class=="lazy">
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php }
                            }?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<?php get_footer() ?>
