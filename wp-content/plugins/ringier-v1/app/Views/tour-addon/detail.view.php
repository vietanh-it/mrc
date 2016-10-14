<?php
get_header();
global $post;
$permalink =  get_permalink($post->ID);
$content_mail =  (cut_string_by_char(strip_tags($post->post_content),200)) . '%0D%0A%0D%0A' . $permalink;
$author = get_userdata($post->post_author);
?>

<div class="container single-news" style="margin-bottom: 55px">
    <div class="row">
        <h1 class="col-xs-12 col-sm-8 tile-main"><?php the_title() ?>
        </h1>
    </div>
    <div class="row">

        <div class="col-xs-12 col-sm-7 content">
           <!-- <p class="time">by <?php /*echo $author->data->display_name; */?> on <?php /*echo date("j F Y",strtotime($post->post_date)) */?></p>-->
            <ul class="share">
                <li><a target="_blank" title="Share width Google+" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink; ?>" rel="nofollow"><img src="<?php echo VIEW_URL . '/images/icon-google-2.png' ?>"></a></li>
                <li><a target="_blank" title="Share width Pinterest" href="https://twitter.com/intent/tweet?source=webclient&text=<?php echo $permalink . "+" . $post->post_title; ?>" rel="nofollow"><img src="<?php echo VIEW_URL . '/images/icon-prin-2.png' ?>"></a></li>
                <li><a target="_blank" title="Share width Facebook" href="https://plus.google.com/share?url={<?php echo $permalink; ?>}" rel="nofollow"><img src="<?php echo VIEW_URL . '/images/icon-facebook-2.png' ?>"></a></li>
                <li><a target="_blank" title="Share width Twitter" href="mailto:?Subject=<?php echo $post->post_title; ?>&body=<?php echo $content_mail; ?>" rel="nofollow"><img src="<?php echo VIEW_URL . '/images/icon-twiter-2.png' ?>"></a></li>
            </ul>
            <div class="content-wrapper">
                <?php the_content() ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-sm-offset-1">
            <?php if(!empty($list_related['data'])){
                foreach ($list_related['data'] as $v){ ?>
                    <div class="news-related">
                        <div class="image">
                            <a href="<?php echo $v->permalink ?>" title="<?php echo  $v->post_title ?>">
                                <img src="<?php echo  $v->images->widescreen ?>" alt="<?php echo  $v->post_title ?>">
                            </a>
                        </div>
                        <a href="<?php echo $v->permalink ?>" title="<?php echo  $v->post_title ?>" class="title">
                            <?php echo  $v->post_title ?>
                        </a>
                    </div>
                <?php }
            } ?>
        </div>

    </div>
</div>


<?php get_footer() ?>

