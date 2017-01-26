<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 7/14/2016
 * Time: 7:54 PM
 */


// Response from failed payment
use RVN\Models\JourneyType;

if (!empty($_GET['resp'])) {
    $m_bank = \RVN\Models\Bank::init();
    $response = $m_bank->getResponseDescription($_GET['resp']);
}

// header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="manifest.xlsx";');
// header('Cache-Control: max-age=0');
//
// // Do your stuff here
// $objReader = PHPExcel_IOFactory::createReader('Excel2007');
// $objPHPExcel = $objReader->load(PATH_VIEW . '/_assets/test.xlsx');
//
// $active_sheet = $objPHPExcel->getActiveSheet();
//
// $active_sheet->insertNewRowBefore(7,2);
//
// $obj = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// $obj->save('php://output');
// exit();

get_header();
?>
<div class="home-slider" id="home-slider">
    <div class="ctn-slide hide-on-med-and-up">
        <div class="owl-carousel">
            <?php if (!empty($home_page_info) && !empty($home_page_info->gallery)) {
                foreach ($home_page_info->gallery as $img) { ?>
                    <div><img src="<?php echo $img->full ?>" alt=""></div>
                <?php }
            } else { ?>
                <div><img src="<?php echo VIEW_URL . '/images/bn1.jpg' ?>" alt=""></div>
                <div><img src="<?php echo VIEW_URL . '/images/bn2.jpg' ?>" alt=""></div>
                <div><img src="<?php echo VIEW_URL . '/images/bn3.jpg' ?>" alt=""></div>
                <div><img src="<?php echo VIEW_URL . '/images/bn4.jpg' ?>" alt=""></div>
            <?php } ?>
        </div>
    </div>
    <div class="container">
        <div class="form-find-journey">
            <form method="get" action="<?php echo WP_SITEURL . '/journeys' ?>" class="quick-search-journey-form">
                <h3>FIND YOUR JOURNEY</h3>
                <div class="form-group">
                    <select id="journey_destinations" name="_destination" class="form-control select-2">
                        <option value="">Destinations</option>
                        <?php if (!empty($list_destination)) {
                            foreach ($list_destination as $v) { ?>
                                <option value="<?php echo $v->post_name ?>"> <?php echo $v->post_title ?></option>
                            <?php }
                        } ?>
                    </select>
                    <span class="icon-n icon-location"></span>
                </div>
                <div class="form-group">
                    <!-- <input type="text" name="_month" class="form-control month-year-input" placeholder="Choose sail month">-->
                    <select id="journey_months" name="_month" class="form-control select-2">
                        <option value="">All months</option>
                        <?php //if (!empty($list_month)) {
                        // foreach ($list_month as $v) { ?>
                        <!--    <option value="--><?php //echo $v->month ?><!--"> -->
                        <?php //echo $v->month ?><!--</option>-->
                        <?php //}
                        // } ?>
                    </select>
                    <span class="icon-n icon-date"></span>
                </div>
                <div class="form-group">
                    <select id="journey_ports" name="_port" class="form-control select-2">
                        <option value="">Departure/Arrival City</option>
                        <?php if (!empty($list_port)) {
                            foreach ($list_port as $v) {
                                if (!empty($v->post_name) && !empty($v->post_title)) { ?>
                                    <option value="<?php echo $v->post_name ?>"> <?php echo $v->post_title ?></option>
                                <?php }
                            }
                        } ?>
                    </select>
                    <span class="icon-n icon-port"></span>
                </div>

                <div class="form-group">
                    <select id="journey_ships" name="_ship" class="form-control select-2">
                        <option value="">Ships</option>
                        <?php if (!empty($list_ship)) {
                            foreach ($list_ship as $v) { ?>
                                <option value="<?php echo $v->post_name ?>"> <?php echo $v->post_title ?></option>
                            <?php }
                        } ?>
                    </select>
                    <span class="icon-n icon-ship"></span>
                </div>

                <div class="text-center">
                    <button type="submit">
                        <img src="<?php echo VIEW_URL . '/images/icon-search.png?v=1'; ?>"
                             style="width: 20px; vertical-align: top; padding-top: 4px; margin-right: 5px;"> Find now
                    </button>
                </div>
            </form>
        </div>
        <!--   </div>
       </div>-->
    </div>
    <div class="ctn-slide hide-on-med-and-down">
        <div class="owl-carousel">
            <?php if (!empty($home_page_info) && !empty($home_page_info->gallery)) {
                foreach ($home_page_info->gallery as $img) { ?>
                    <div><img src="<?php echo $img->full ?>" alt=""></div>
                <?php }
            } else { ?>
                <div><img src="<?php echo VIEW_URL . '/images/bn1.jpg' ?>" alt=""></div>
                <div><img src="<?php echo VIEW_URL . '/images/bn2.jpg' ?>" alt=""></div>
                <div><img src="<?php echo VIEW_URL . '/images/bn3.jpg' ?>" alt=""></div>
                <div><img src="<?php echo VIEW_URL . '/images/bn4.jpg' ?>" alt=""></div>
            <?php } ?>
        </div>
    </div>

</div>

<?php view('blocks/introduction', ['intro_type' => 'home_introduction']); ?>

<div class="why-us">
    <div class="container ">
        <div class="row">
            <h2 class="col-xs-12 col-sm-12 tile-main white"><a href="<?php echo WP_SITEURL . '/why-us/' ?>">Why us</a>
                <br> <img src="<?php echo VIEW_URL . '/images/line-white.png?v=1' ?>" style="width: 110px">
            </h2>
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row list-slide-mb">
                    <?php
                    if (!empty($list_whyus['data'])) {
                        foreach ($list_whyus['data'] as $k => $p) {
                            $i = $k + 1;
                            if ($i > 3) {
                                $i = 3;
                            }
                            ?>
                            <div class="col-xs-12 col-sm-12">
                                <div class="box-why">
                                    <div class="image">
                                        <a href="<?php echo $p->permalink ?>"> <img
                                                src="<?php echo $p->images->featured ?>" alt="" class="img-main"></a>
                                        <img src="<?php echo VIEW_URL . '/images/why-' . $i . '.png?v=2' ?>"
                                             class="img-icon">
                                    </div>
                                    <div class="desc">
                                        <p class="title"><a
                                                href="<?php echo $p->permalink ?>"> <?php echo $p->post_title ?></a></p>
                                        <p><?php echo cut_string_by_char(strip_tags($p->post_content), 150) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="journey-home">
    <div class="container ">
        <div class="row">
            <h2 class="col-xs-12 col-sm-12 tile-main"><a href="<?php echo WP_SITEURL . '/journeys/' ?>">Journey</a>
                <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
            </h2>
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row list-slide-mb">
                    <?php if (!empty($list_journey_type['data'])) {
                        $m_jt = JourneyType::init();
                        foreach ($list_journey_type['data'] as $v) {
                            $min_price = $m_jt->getJourneyTypeMinPrice($v->ID);
                            ?>
                            <div class="col-xs-12 col-sm-12 ">
                                <div class="box-journey">
                                    <div class="image">
                                        <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                            <img src="<?php echo $v->images->featured ?>"
                                                 alt="<?php echo $v->post_title ?>" class="lazy">
                                        </a>
                                        <?php if (!empty($min_price)) { ?>
                                            <div class="price">
                                                $<?php echo number_format($min_price) ?> <br/>
                                                <?php echo number_format($min_price * CURRENCY_RATE); ?> VND
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="desc">
                                        <div class="jt-title-wrapper">
                                            <a href="<?php echo $v->permalink ?>" class="title"
                                               title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                        </div>

                                        <ul>
                                            <li>
                                                <b><?php echo $v->starting_point . ' - ' . $v->destination_info->post_title; ?></b>
                                            </li>
                                            <li><b><?php echo $v->duration ?></b></li>
                                        </ul>

                                        <p><?php echo cut_string_by_char(($v->post_excerpt), 150) ?></p>
                                        <a href="<?php echo $v->permalink ?>" class="explore" title="">Explore</a>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <img src="<?php echo VIEW_URL . '/images/icon-trong-dong.png' ?>" alt="" class="bg-a">
</div>

<?php if (!empty($list_offer)) { ?>
    <div class="offer-home">
        <div class="container ">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div class="tile-main" style="position: relative">
                        <a href="javascript:void(0)">Latest offer</a>
                        <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">

                        <a class="btn-kep-offer" href="#form-kep-offer"><img
                                src="<?php echo VIEW_URL . '/images/icon-email-2.png?v2' ?>"
                                style="padding-right: 10px;width: 30px">
                            Keep in touch with best offer</a>

                        <form id="form-kep-offer" style="display: none" class="form-facybox">
                            <div class="form-group">
                                <label for="c_email">Your email:</label>
                                <input type="email" value="" name="c_email"
                                       placeholder="" class="form-control">
                            </div>
                            <div class="form-group text-center">
                                <input type="hidden" name="action" value="ajax_handler_account">
                                <input type="hidden" name="method" value="ConnectEmail">
                                <button type="submit" class="btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div class="row">
                        <div class="owl-carousel-2">
                            <?php
                            $JourneyType = \RVN\Controllers\JourneyTypeController::init();
                            $m_journey = \RVN\Models\Journey::init();

                            foreach ($list_offer as $v) {
                                $journey_info = $m_journey->getInfo($v->journey_id);

                                $permalink = $journey_info->journey_type_info->permalink . '#j=' . $journey_info->ID;

                                // Journey min price
                                $journey_min_price = $m_journey->getJourneyMinPrice($v->journey_id, true); ?>

                                <div class="col-xs-12 col-sm-12">
                                    <div class="box-journey box-white">

                                        <div class="image" style="position: relative">
                                            <a href="<?php echo $permalink ?>" title="<?php echo $v->post_title ?>">
                                                <img src="<?php echo $v->images->small ?>"
                                                     alt="<?php echo $v->post_title ?>" class="lazy">
                                            </a>
                                            <div class="price">
                                                $<?php echo number_format($journey_min_price->min_price_offer) ?> <br/>
                                                <?php echo number_format($journey_min_price->min_price_offer * CURRENCY_RATE) ?>
                                                VND
                                            </div>
                                        </div>

                                        <div class="desc">
                                            <a href="<?php echo $permalink ?>" class="title"
                                               title="<?php echo $v->post_title ?>">
                                                <?php echo $v->post_title ?>
                                            </a>

                                            <p>
                                                <?php echo cut_string_by_char(strip_tags($v->post_content), 150) ?>
                                            </p>

                                            <p>
                                                <b>Departure date:</b>
                                                <?php echo date("j F Y", strtotime($journey_info->departure)); ?>

                                                <a href="<?php echo $permalink; ?>" class="read-more" title="read more">
                                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="star">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (!empty($list_room_items['data']) or !empty($list_room_items_featured['data'])) {
    if (!empty($list_room_items_featured['data'])) {
        $big_room = array_shift($list_room_items_featured['data']);
    } else {
        if (!empty($list_room_items['data'])) {
            $big_room = array_shift($list_room_items['data']);
        }
    }
    if (!empty($big_room)) {
        ?>
        <div class="room-home">
            <div class="container ">
                <div class="row">
                    <h2 class="col-xs-12 col-sm-12 tile-main">Featured Room Item
                        <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
                    </h2>
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                        <div class="row">

                            <?php //var_dump($big_room);
                            if ($big_room->gallery) {
                                ?>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="box-room">
                                        <?php foreach ($big_room->gallery as $kg => $img) { ?>
                                            <a <?php echo $kg != 0 ? "style ='display:none'" : '' ?>
                                                href="<?php echo $img->full ?>" title="<?php echo $img->caption ?>"
                                                class="fancybox" rel="big_rom">
                                                <img src="<?php echo $img->featured ?>" alt="" class="lazy ">
                                            </a>
                                        <?php } ?>
                                        <a href="#" title="" class="title"><?php echo $big_room->post_title ?></a>
                                    </div>
                                </div>
                            <?php }
                            ?>

                            <?php if (!empty($list_room_items['data'])) {
                                foreach ($list_room_items['data'] as $room) {
                                    if ($room->gallery) { ?>
                                        <div class="col-xs-6 col-sm-3">
                                            <div class="box-room">
                                                <?php foreach ($room->gallery as $kg => $img) { ?>
                                                    <a <?php echo $kg != 0 ? "style ='display:none'" : '' ?>
                                                        href="<?php echo $img->full ?>"
                                                        title="<?php echo $img->caption ?>" class="fancybox"
                                                        rel="list_room_<?php echo $room->ID ?>">
                                                        <img src="<?php echo $img->featured ?>" alt="" class="lazy">
                                                    </a>
                                                <?php } ?>
                                                <a href="#" title="" class="title"><?php echo $room->post_title ?></a>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
} ?>



<?php get_footer() ?>

<script>
    var $ = jQuery.noConflict();
    $(document).ready(function () {
        <?php if(!empty($response)) { ?>
        swal({
            title: 'Payment Failed',
            text: '<?php echo $response; ?>',
            confirmButtonColor: '#e4a611',
            type: 'error'
        }, function () {
            history.replaceState({}, 'popup done', '/');
        });
        <?php } ?>
        // var jssor_1_options = {
        //     $AutoPlay: true,
        //     $SlideWidth: 600,
        //     $Cols: 2,
        //     $Align: 100,
        //     $ArrowNavigatorOptions: {
        //         // $Class: $JssorArrowNavigator$
        //     },
        //     $BulletNavigatorOptions: {
        //         // $Class: $JssorBulletNavigator$
        //     }
        // };

        //slider mobile
        // var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

        // function ScaleSlider() {
        //     var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
        //     if (refSize) {
        //         refSize = Math.min(refSize, 800);
        //         jssor_1_slider.$ScaleWidth(refSize);
        //     }
        //     else {
        //         window.setTimeout(ScaleSlider, 30);
        //     }
        // }
        //
        // ScaleSlider();
        // $(window).bind("load", ScaleSlider);
        // $(window).bind("resize", ScaleSlider);
        // $(window).bind("orientationchange", ScaleSlider);

        $('#journey_destinations').on('change', function () {
            var dest = $(this).val();
            if (dest) {
                // Add to cart ajax
                $.ajax({
                    url: MyAjax.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'ajax_handler_jt',
                        method: 'GetMonths',
                        destination: dest
                    },
                    beforeSend: function () {
                        switch_loading(true);
                    },
                    success: function (data) {
                        switch_loading(false);

                        if (data.status == 'success') {
                            $('#journey_months option:gt(0)').remove();
                            $('#journey_ports option:gt(0)').remove();
                            $('#journey_ships option:gt(0)').remove();

                            var options = [];
                            $.each(data.data, function (k, v) {
                                var item = new Option(k, v);
                                options.push(item);
                            });
                            $('#journey_months').append(options);
                        }
                        else {
                            var html_msg = '<div>';
                            if (data.message) {
                                $.each(data.message, function (k_msg, msg) {
                                    html_msg += msg + "<br/>";
                                });
                            } else if (data.data) {
                                $.each(data.data, function (k_msg, msg) {
                                    html_msg += msg + "<br/>";
                                });
                            }
                            html_msg += "</div>";
                            swal({"title": "Error", "text": html_msg, "type": "error", html: true});
                        }

                    }
                }); // end ajax
            }
        });


        $('#journey_months').on('change', function () {
            var dest = $('#journey_destinations').val();
            var month = $(this).val();
            if (dest && month) {
                // Add to cart ajax
                $.ajax({
                    url: MyAjax.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'ajax_handler_jt',
                        method: 'GetPorts',
                        month: month,
                        destination: dest
                    },
                    beforeSend: function () {
                        switch_loading(true);
                    },
                    success: function (data) {
                        switch_loading(false);

                        if (data.status == 'success') {
                            $('#journey_ports option:gt(0)').remove();

                            var options = [];
                            $.each(data.data, function (k, v) {
                                var item = new Option(v, k);
                                options.push(item);
                            });
                            $('#journey_ports').append(options);
                        }
                        else {
                            var html_msg = '<div>';
                            if (data.message) {
                                $.each(data.message, function (k_msg, msg) {
                                    html_msg += msg + "<br/>";
                                });
                            } else if (data.data) {
                                $.each(data.data, function (k_msg, msg) {
                                    html_msg += msg + "<br/>";
                                });
                            }
                            html_msg += "</div>";
                            swal({"title": "Error", "text": html_msg, "type": "error", html: true});
                        }

                    }
                }); // end ajax
            }
        });


        $('#journey_ports').on('change', function () {
            var dest = $('#journey_destinations').val();
            var month = $('#journey_months').val();
            var port = $('#journey_ports').val();
            if (dest && month && port) {
                // Add to cart ajax
                $.ajax({
                    url: MyAjax.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'ajax_handler_jt',
                        method: 'GetShips',
                        dest: dest,
                        month: month,
                        port: port
                    },
                    beforeSend: function () {
                        switch_loading(true);
                    },
                    success: function (data) {
                        switch_loading(false);

                        if (data.status == 'success') {
                            $('#journey_ships option:gt(0)').remove();

                            var options = [];
                            $.each(data.data, function (k, v) {
                                var item = new Option(v, k);
                                options.push(item);
                            });
                            $('#journey_ships').append(options);
                        }
                        else {
                            var html_msg = '<div>';
                            if (data.message) {
                                $.each(data.message, function (k_msg, msg) {
                                    html_msg += msg + "<br/>";
                                });
                            } else if (data.data) {
                                $.each(data.data, function (k_msg, msg) {
                                    html_msg += msg + "<br/>";
                                });
                            }
                            html_msg += "</div>";
                            swal({"title": "Error", "text": html_msg, "type": "error", html: true});
                        }

                    }
                }); // end ajax
            }
        });

    });
</script>