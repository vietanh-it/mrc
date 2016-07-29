<?php

get_header();

 view('journey/quick-search', compact('params'));
?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">Your expected journeys
        </h1>

        <?php if($list_journey['data']){
            foreach ($list_journey['data'] as $v){
                //var_dump($v);
                ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="box-journey-2">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <div class="images">
                                    <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                        <img src="<?php echo $v->images->featured ?>" alt="<?php echo $v->post_title ?>">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="desc">
                                    <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>" class="title">
                                        <?php echo $v->post_title ?>
                                    </a>
                                    <p><?php
                                        if(!empty($v->post_content)){
                                            $content = strip_tags($v->post_content);
                                            echo cut_string_by_char($content,200);
                                        }
                                         ?>
                                    </p>
                                    <ul>
                                        <li><b>7 nights 6 days</b></li>
                                        <li><b>Promotion:</b> Save up to 20% on selected dates <img src="<?php echo  VIEW_URL.'/images/icon-ticket.png'?>"></li>
                                    </ul>
                                    <a href="<?php echo $v->permalink ?>" class="explore">Explore Now >></a>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <div class="bk">
                                    <p>from US$<b>1,755</b> pp</p>
                                    <a href="<?php echo $v->permalink ?>">Book now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        }else{ ?>
            <div class="col-xs-12 col-sm-12" style="    margin: 0 0 20px;">No result is found</div>
        <?php } ?>
    </div>
</div>


<?php get_footer() ?>
