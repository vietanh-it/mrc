<?php
$m_destination = \RVN\Models\Destinations::init();

$list_destination = $m_destination->getDestinationHaveJourney();

$destination = !empty($_GET['_destination']) ? $_GET['_destination'] : '';
$ship = !empty($_GET['_ship']) ? $_GET['_ship'] : '';
$port = !empty($_GET['_port']) ? $_GET['_port'] : '';
$month = !empty($_GET['_month']) ? $_GET['_month'] : '';

$m_post = \RVN\Models\Posts::init();

?>
<div class="quick-search-journey">
    <form method="get" class="quick-search-journey-form">
        <div class="container container-big">
            <div class="row">
                <h3 class="col-xs-12 col-sm-12">Find your journey</h3>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select id="journey_destinations" name="_destination" class="form-control select-2">
                                    <option value="">Destinations</option>
                                    <?php if ($list_destination) {
                                        foreach ($list_destination as $v) { ?>
                                            <option
                                                value="<?php echo $v->post_name ?>" <?php echo $v->post_name == $destination ? 'selected' : '' ?>> <?php echo $v->post_title ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <span class="icon-n icon-location"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <!--<input type="text" name="_month" class="form-control month-year-input" placeholder="Choose sail month" value="<?php /*echo $month */ ?>">-->
                                <select id="journey_months" name="_month" class="form-control select-2">
                                    <option value="">All months</option>
                                    <?php if (!empty($month)) { ?>
                                        <option value="<?php echo $month ?>" selected><?php echo $month ?></option>
                                    <?php } ?>
                                </select>
                                <span class="icon-n icon-date"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select id="journey_ports" name="_port" class="form-control select-2">
                                    <option value="">Departure/Arrival City</option>
                                    <?php if (!empty($port)) {
                                        $selected_port = $m_post->getPostBySlug($port, ['port', 'excursion']) ?>
                                        <option value="<?php echo $port ?>" selected>
                                            <?php echo $selected_port->post_title ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <span class="icon-n icon-port"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select id="journey_ships" name="_ship" class="form-control select-2">
                                    <option value="">Ships</option>
                                    <?php if (!empty($ship)) {
                                        $selected_ship = $m_post->getPostBySlug($ship, 'ship') ?>
                                        <option value="<?php echo $ship ?>" selected>
                                            <?php echo $selected_ship->post_title ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <span class="icon-n icon-ship"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2">
                    <div class="text-right">
                        <button type="submit" style="display: inline-flex; white-space: nowrap;">
                            <img src="<?php echo VIEW_URL . '/images/icon-search-yellow.png?v=1'; ?>"
                                 style="width: 20px; vertical-align: top; padding-top: 4px; margin-right: 5px;"> Find
                            now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    var $ = jQuery.noConflict();
    $(document).ready(function () {

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