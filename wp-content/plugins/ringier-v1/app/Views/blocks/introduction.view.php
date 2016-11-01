<?php
if (!empty($intro_type)) {
    $intro = get_option($intro_type);
    if (!empty($intro)) {
        ?>

        <div id="introduction">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                        <div class="content"><?php echo apply_filters('the_content', $intro) ?></div>
                    </div>
                </div>
            </div>
        </div>

    <?php }
} ?>