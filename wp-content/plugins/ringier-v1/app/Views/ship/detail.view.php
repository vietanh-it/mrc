<?php
$ship_detail = !empty($ship_detail) ? $ship_detail : [];
get_header();
?>


<div class="journey-detail">
    <?php if (!empty($ship_detail->images->full)) { ?>
        <div class="featured-image featured-image-2 hide-on-med-and-up">
            <a href="<?php echo $ship_detail->images->full ?>" class="fancybox"><img
                    src="<?php echo $ship_detail->images->full ?>" alt="<?php echo $ship_detail->post_title ?>"></a>
        </div>
    <?php } ?>

    <div class="container hide-on-med-and-up" style="margin-bottom: 30px">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-top: 0;"><?php echo $ship_detail->post_title; ?>
                <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
            </h1>
        </div>
    </div>

    <div class="container container-big">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="info-ship mCustomScrollbar">
                    <?php echo strip_tags($ship_detail->post_content) ?>
                </div>

                <?php if (!empty($ship_detail->basic_specs)) { ?>
                    <div class="info-basic">
                        <?php echo apply_filters('the_content', $ship_detail->basic_specs) ?>
                    </div>
                <?php } ?>

                <div class="btn-ship-detail-list">
                    <a href="<?php echo WP_SITEURL; ?>/journeys?_destination=&_month=&_port=&_ship=<?php echo $ship_detail->post_name; ?>">
                        See journeys of this ship
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($ship_detail->images->full)) { ?>
        <div class="featured-image featured-image-2 hide-on-med-and-down">
            <a href="<?php echo $ship_detail->images->full ?>" class="fancybox"><img
                    src="<?php echo $ship_detail->images->full ?>" alt="<?php echo $ship_detail->post_title ?>"></a>
        </div>
    <?php } ?>

    <div class="container hide-on-med-and-down" style="margin-bottom: 30px">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 tile-main" style="margin-top: 0;"><?php echo $ship_detail->post_title; ?>
                <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
            </h1>
        </div>
    </div>


    <div class="container container-big">
        <div class="row">
            <div class="col-xs-12 col-sm-4 list-img-left">
                <?php if (!empty($ship_detail->gallery)) { ?>
                    <h3 class="title-main" style="margin-top: 0">Ship photos</h3>
                    <div class="list-galary">
                        <?php foreach ($ship_detail->gallery as $g) { ?>
                            <div><a href="<?php echo $g->full ?>" class="fancybox" rel="ship photos"><img
                                        src="<?php echo $g->small ?>"></a></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (!empty($ship_detail->youtube_id)) { ?>
                    <h3 class="title-main" <?php echo  (empty($ship_detail->gallery)) ? 'style="margin-top: 0;"' :''?>>Ship video</h3>
                    <iframe src="https://www.youtube.com/embed/<?php echo $ship_detail->youtube_id ?>"
                            frameborder="0" allowfullscreen=""
                            class="embed-responsive-item" style="width: 100%;min-height: 250px"></iframe>
                <?php } ?>

                <?php if (!empty($ship_detail->accomodation)) { ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <h3>Accommodation</h3>
                            <?php echo apply_filters('the_content', $ship_detail->accommodation) ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="row">

                    <?php if (!empty($ship_detail->wining_dining)) { ?>
                        <div class="col-xs-12 col-sm-12">
                            <h3>Wining & dining</h3>
                            <?php echo apply_filters('the_content', $ship_detail->wining_dining) ?>
                        </div>
                    <?php } ?>


                    <?php if (!empty($ship_detail->onboard_staff)) { ?>
                        <div class="col-xs-12 col-sm-12">
                            <h3>Onboard staff</h3>
                            <?php echo apply_filters('the_content', $ship_detail->onboard_staff) ?>
                        </div>
                    <?php } ?>

                </div>
                <div class="row">
                    <?php if (!empty($ship_detail->on_excursion)) { ?>
                        <div class="col-xs-12 col-sm-12">
                            <h3>On excursion</h3>
                            <?php echo apply_filters('the_content', $ship_detail->on_excursion) ?>
                        </div>
                    <?php } ?>

                    <?php if (!empty($ship_detail->safety_security)) { ?>
                        <div class="col-xs-12 col-sm-12">
                            <h3>Safety & Security</h3>
                            <?php echo apply_filters('the_content', $ship_detail->safety_security) ?>
                        </div>
                    <?php } ?>
                </div>

                <?php if (!empty($ship_detail->public_spaces)) { ?>
                    <h3>Public spaces</h3>
                    <?php echo apply_filters('the_content', $ship_detail->public_spaces);
                } ?>
            </div>
            <div class="col-xs-12 col-sm-8 content_ship">
                <?php if (!empty($ship_detail->decks)) { ?>
                    <h3 class="title-main" style="margin-top: 0; border-bottom: 1px solid #e4a611;
    padding-bottom: 20px;">DECKS</h3>
                    <?php $decks = unserialize($ship_detail->decks);
                    foreach ($decks as $k => $v) {
                        $deck = unserialize($v);

                        $img_full = wp_get_attachment_image_src($deck['img_id'], 'full');
                        if ($img_full) {
                            $img_full = array_shift($img_full);
                        }

                        $img = wp_get_attachment_image_src($deck['img_id'], 'widescreen');
                        if ($img) {
                            $img = array_shift($img);
                        }
                        ?>
                        <div class="box-deck">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="title">
                                        <?php echo $deck['title'] ?>
                                    </div>
                                    <div class="content">
                                        <?php echo apply_filters('the_content', $deck['content']) ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <a href="<?php echo $img_full ?>" class="fancybox"
                                       title="<?php echo $deck['title'] ?>">
                                        <img src="<?php echo $img ?>" alt="<?php echo $deck['title'] ?>" style="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } ?>

                <?php if (!empty($ship_detail->rooms_info)) { ?>
                    <h3 class="title-main" style="margin-top: 0; border-bottom: 1px solid #e4a611;
    padding-bottom: 20px;">rooms</h3>
                    <div class="desc" style="margin-bottom: 20px">
                        <?php echo apply_filters('the_content', $ship_detail->room_general_introduction) ?>
                    </div>
                    <?php $rooms = unserialize($ship_detail->rooms_info);
                    foreach ($rooms as $k => $v) {
                        $room = unserialize($v);
                        $img_full = wp_get_attachment_image_src($room['room_img_id'], 'full');
                        if ($img_full) {
                            $img_full = array_shift($img_full);
                        }

                        $img = wp_get_attachment_image_src($room['room_img_id'], 'widescreen');
                        if ($img) {
                            $img = array_shift($img);
                        }
                        ?>
                        <div class="box-deck box-room-2">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="title">
                                        <?php echo !empty($room['room_title']) ? $room['room_title'] : '' ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-8">
                                    <div class="desc">
                                        <?php echo apply_filters('the_content', $room['room_description']) ?>
                                    </div>
                                    <div class="content">
                                        <b>More info</b>
                                        <?php echo apply_filters('the_content', $room['room_content']) ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <a href="<?php echo $img_full ?>" class="fancybox"
                                       title="<?php echo $room['room_title'] ?>">
                                        <img src="<?php echo $img ?>" alt="<?php echo $room['room_title'] ?>" style="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } ?>


                <?php if(!empty($ship_detail->facilities)){ ?>
                    <h3 class="title-main" style="margin-top: 0; border-bottom: 1px solid #e4a611;
    padding-bottom: 20px;">Facilities & Services</h3>

                    <?php $facilities = unserialize($ship_detail->facilities);
                    foreach ($facilities as $k => $v){
                        $facility = unserialize($v);
                        $img_full= wp_get_attachment_image_src($facility['facility_img_id'],'full');
                        if($img_full) $img_full = array_shift($img_full);

                        $img= wp_get_attachment_image_src($facility['facility_img_id'],'widescreen');
                        if($img) $img = array_shift($img);
                        ?>
                        <div class="box-deck box-room-2">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="title">
                                        <?php echo !empty($facility['facility_title']) ? $facility['facility_title'] : '' ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-8">
                                    <div class="desc">
                                        <?php echo apply_filters('the_content',$facility['facility_description']) ?>
                                    </div>
                                    <div class="content">
                                        <b>More info</b>
                                        <?php echo apply_filters('the_content',$facility['facility_content']) ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <a href="<?php echo $img_full ?>" class="fancybox" title="<?php echo $facility['facility_title'] ?>">
                                        <img src="<?php echo $img ?>" alt="<?php echo $facility['facility_title'] ?>" style="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer() ?>

<script>
    var $ = jQuery.noConflict();
    $(document).ready(function () {
        $('.info-ship').mCustomScrollbar();
    });
</script>
