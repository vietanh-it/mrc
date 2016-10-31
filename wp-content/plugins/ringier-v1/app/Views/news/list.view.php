<?php

get_header();
$list_post = !empty($list_post) ? $list_post : array();

?>

<?php
$slider_page = get_page_by_path(PAGE_HOME_SLIDER_SLUG);
$cover_id = get_post_meta($slider_page->ID,'cover_whyus',true);
if($cover_id){
    $cover= wp_get_attachment_image_src($cover_id,'full');
    if($cover){ $cover = array_shift($cover); ?>
        <div class="journey-detail" style="margin-bottom: 40px">
            <div class="featured-image" >
                <img src="<?php echo $cover ?>" alt="bg" >
            </div>
        </div>
    <?php }
} ?>


<div class="container">
    <div class="row">
        <!--<div class="col-xs-12 col-sm-10 col-sm-offset-1">-->
            <!--<div class="row">-->
                <?php if(!empty($list_post['data'])){
                    foreach ($list_post['data'] as $v){
                        $author = get_userdata($v->post_author);
                        ?>
                        <div class="box-news">
                            <div class="col-xs-12 col-sm-3 col-sm-offset-1">
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
                                    <p class="time"><?php echo date("j F Y",strtotime($v->post_date)) ?><br>
                                        <span style="font-style: italic">article by <?php echo $author->data->display_name; ?></span>
                                    </p>
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
            <!--</div>-->
        <!--</div>-->
    </div>
</div>

<?php get_footer() ?>
