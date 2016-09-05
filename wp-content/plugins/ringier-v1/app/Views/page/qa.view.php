<?php
get_header();
$list_qa = !empty($list_qa) ? $list_qa : array();
?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main"> Q&A
            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <?php the_content() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <?php if(!empty($list_qa)){
                $list_qa_new = unserialize($list_qa);
                foreach ($list_qa_new as $v){ ?>
                    <div class="box-qa">
                        <div class="question">
                            <a href="javascript:void(0)" class="show-answer">
                                <?php echo $v['question'] ?>
                            </a>
                            <a href="javascript:void(0)" class="hide-answer">
                                <?php echo $v['question'] ?>
                            </a>
                        </div>
                        <div class="answer" style="display: none">
                            <?php echo apply_filters('the_content',$v['answer']) ?>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>


<?php get_footer() ?>

