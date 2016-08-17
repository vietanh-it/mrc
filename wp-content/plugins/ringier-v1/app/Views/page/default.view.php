<?php
get_header();

?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main"><?php the_title() ?>
            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <?php the_content() ?>
        </div>
    </div>
</div>


<?php get_footer() ?>

