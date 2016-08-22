<?php

get_header();
$list_post = !empty($list_post) ? $list_post : array();

?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main"><?php the_title() ?>
            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="row">
                <?php if(!empty($list_post['data'])){
                    foreach ($list_post['data'] as $v){

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
                                    <a href="<?php echo $v->permalink ?>" class="explore" title="Read more">Read more</a>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (function_exists('wp_pagenavi')) wp_pagenavi(array(
                        'before' => '  <div class="wrap-pagination">',
                        'after' => '</div>'
                    ));
                } ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
