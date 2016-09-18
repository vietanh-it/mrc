<?php

get_header();
$list_journey_type = !empty($list_journey_type) ? $list_journey_type : array();

view('journey/quick-search');
?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main" style="margin: 75px 0 40px;">All Journeys
            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="row">
                <?php if($list_journey_type['data']){
                    foreach ($list_journey_type['data'] as $v){
                        ?>
                        <div class="col-xs-12 col-sm-4">
                            <div class="box-journey">
                                <div class="image">
                                    <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                        <img src="<?php echo $v->images->featured ?>" alt="<?php echo $v->post_title ?>" class="lazy">
                                    </a>
                                </div>
                                <div class="desc">
                                    <a href="<?php echo $v->permalink ?>" class="title" title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                    <p><?php echo cut_string_by_char($v->post_excerpt,150) ?></p>
                                    <a href="<?php echo $v->permalink ?>" class="explore" title="">Explore</a>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (function_exists('wp_pagenavi')) wp_pagenavi(array(
                        'before' => '  <div class="wrap-pagination">',
                        'after' => '</div>'
                    ));
                } else { ?>
                    <div class="col-xs-12 col-sm-12" style="    margin: 0 0 20px;">No result match found</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
