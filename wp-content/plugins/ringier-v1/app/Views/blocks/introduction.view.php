<?php
$page_setting = get_page_by_path(PAGE_HOME_SLIDER_SLUG);
$introduction_title = get_post_meta($page_setting->ID,'introduction_title',true);
$introduction_content = get_post_meta($page_setting->ID,'introduction_content',true);
if(!empty($introduction_title) && !empty($introduction_content)){
    ?>

    <div id="introduction">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <h2 class="tile-main"><?php echo $introduction_title ?>
                        <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
                    </h2>
                    <div class="content"><?php echo apply_filters('the_content',$introduction_content) ?></div>
                </div>
            </div>
        </div>

    </div>
<?php } ?>