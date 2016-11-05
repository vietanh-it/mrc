<?php
$return = !empty($return) ? $return : array(
    'status' => 'error',
    'message' => 'An error, please check your email and try again.',
);
get_header();

?>
<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">Change e-mail
            <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="message">
                <?php if(!empty($return)){
                    if($return['status'] == 'error'){
                        foreach ($return['message'] as $er){ ?>
                            <p class="text-danger bg-danger"><?php echo $er ?></p>
                        <?php }
                    } else { ?>
                        <p class="text-success bg-success"><?php echo $return['message'] ?></p>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
</div>


<?php get_footer() ?>
