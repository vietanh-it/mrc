<?php
get_header();
$list_qa = !empty($list_qa) ? $list_qa : array();
?>

<div class="container" style="margin-bottom: 50px">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main"> FAQ
            <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <?php the_content() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="list_qa_df">
                <?php  if(!empty($list_qa)){
                    $list_qa_new = unserialize($list_qa);
                    foreach ($list_qa_new as $k => $v){?>
                        <div class="list_qa_df_box">
                            <a href="javascript:void(0)" class="show-answer" data-id="<?php echo $k+1 ?>" title="<?php echo $v['question'] ?>">
                                <i class="fa fa-circle" aria-hidden="true"></i>    <?php echo $v['question'] ?>
                            </a>
                        </div>
                    <?php }
                } ?>
            </div>

            <div class="clearfix"></div>

            <?php if(!empty($list_qa)){
                $list_qa_new = unserialize($list_qa);
                foreach ($list_qa_new as $k => $v){ ?>
                    <div class="box-qa" id="box-qa-<?php echo $k+1 ?>">
                        <div class="question">
                                <?php echo $v['question'] ?>
                        </div>
                        <div class="answer" >
                            <?php echo apply_filters('the_content',$v['answer']) ?>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>


<?php get_footer() ?>

