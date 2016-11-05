<?php
get_header();
$list_offer = !empty($list_offer) ? $list_offer : [];
$JourneyType = \RVN\Controllers\JourneyTypeController::init();
?>
<?php
$slider_page = get_page_by_path(PAGE_HOME_SLIDER_SLUG);
$cover_id = get_post_meta($slider_page->ID, 'cover_offer', true);
if ($cover_id) {
    $cover = wp_get_attachment_image_src($cover_id, 'full');
    if ($cover) {
        $cover = array_shift($cover); ?>
        <div class="journey-detail">
            <div class="featured-image cover-img">
                <img src="<?php echo $cover ?>" alt="bg">
            </div>
        </div>
    <?php }
} ?>
<?php view('blocks/introduction', ['intro_type' => 'offer_introduction']); ?>

<?php if (!empty($list_offer)) { ?>
    <div class="container">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main">All Offers
                <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
            </h1>
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <?php foreach ($list_offer as $v) {
                        $min_price = $v->journey_min_price->min_price_offer;
                        if (!empty($min_price)) {
                            ?>
                            <div class="col-xs-12 col-sm-4">
                                <div class="box-journey box-white">
                                    <div class="image" style="position: relative">
                                        <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                            <img src="<?php echo $v->images->small ?>"
                                                 alt="<?php echo $v->post_title ?>" class="lazy">
                                        </a>
                                        <div class="price"> $<?php echo number_format($min_price) ?></div>
                                    </div>
                                    <div style="border: 1px solid #ccc">
                                        <div class="desc">
                                            <a href="<?php echo $v->permalink ?>" class="title"
                                               title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                            <p><?php echo cut_string_by_char(strip_tags($v->post_content), 150) ?></p>
                                            <p><b>Departure Date:</b> <?php echo $v->journey_info->departure_fm; ?>
                                                <a href="<?php echo $v->permalink ?>" class="read-more"
                                                   title="read more">
                                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="star"><i class="fa fa-star" aria-hidden="true"></i> <i
                                                class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star"
                                                                                              aria-hidden="true"></i> <i
                                                class="fa fa-star" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }
                    if (function_exists('wp_pagenavi')) {
                        wp_pagenavi([
                            'before' => '  <div class="wrap-pagination">',
                            'after'  => '</div>'
                        ]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<?php get_footer() ?>

