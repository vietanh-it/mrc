<?php

get_header();
$list_post = !empty($list_post) ? $list_post : array();

?>
<div class="journey-detail">
    <div class="featured-image" >
        <img src="<?php echo VIEW_URL.'/images/bg-news.png' ?>" alt="bg" >
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="row">
                <?php if(!empty($list_post['data'])){
                    foreach ($list_post['data'] as $v){
                        ?>
                            <div class="box-news">
                                <div class="col-xs-12 col-sm-4">
                                    <div class="image">
                                        <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                            <img src="<?php echo $v->images->small ?>" alt="<?php echo $v->post_title ?>" class="lazy">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-8">
                                    <div class="desc">
                                        <a href="<?php echo $v->permalink ?>" class="title" title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                        <p><?php echo cut_string_by_char(strip_tags($v->post_content),250) ?></p>
                                        <p class="time"><?php echo date("j F Y",strtotime($v->post_date)) ?></p>
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
