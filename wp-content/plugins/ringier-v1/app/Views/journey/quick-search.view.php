<?php
$objShip = \RVN\Controllers\ShipController::init();
$objDestination = \RVN\Controllers\DestinationController::init();
$objPort = \RVN\Controllers\PortController::init();
$objJourney = \RVN\Controllers\JourneyController::init();
$m_destination = \RVN\Models\Destinations::init();
$m_port = \RVN\Models\Ports::init();

$list_destination = $m_destination->getDestinationHaveJourney();
$list_port = $m_port->getPortHaveJourney();
$list_ship = $objShip->getSipList();
$list_month = $objJourney->getMonth();


$destination = !empty($_GET['_destination']) ? $_GET['_destination'] : '';
$ship = !empty($_GET['_ship']) ? $_GET['_ship'] : '';
$port = !empty($_GET['_port']) ? $_GET['_port'] : '';
$month = !empty($_GET['_month']) ? $_GET['_month'] : '';

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
                                <select name="_destination" class="form-control select-2">
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
                                <select name="_month" class="form-control select-2">
                                    <option value="">All months</option>
                                    <?php if ($list_month) {
                                        foreach ($list_month as $v) { ?>
                                            <option
                                                value="<?php echo $v->month ?>" <?php echo $v->month == $month ? 'selected' : '' ?>> <?php echo $v->month ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <span class="icon-n icon-date"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select name="_port" class="form-control select-2">
                                    <option value="">Departure/Arrival City</option>
                                    <?php if ($list_port) {
                                        foreach ($list_port as $v) {
                                            if (!empty($v->post_name) && !empty($v->post_title)) { ?>
                                                <option
                                                    value="<?php echo $v->post_name ?>" <?php echo $v->post_name == $port ? 'selected' : '' ?>> <?php echo $v->post_title ?></option>
                                            <?php }
                                        }
                                    } ?>
                                </select>
                                <span class="icon-n icon-port"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select name="_ship" class="form-control select-2">
                                    <option value="">Ships</option>
                                    <?php if ($list_ship['data']) {
                                        foreach ($list_ship['data'] as $v) { ?>
                                            <option
                                                value="<?php echo $v->post_name ?>" <?php echo $v->post_name == $ship ? 'selected' : '' ?>> <?php echo $v->post_title ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <span class="icon-n icon-ship"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2">
                    <div class="text-right">
                        <button type="submit" style="display: inline-flex; white-space: nowrap;">
                            <img src="<?php echo VIEW_URL . '/images/icon-search-yellow.png?v=1'; ?>" style="width: 20px; vertical-align: top; padding-top: 4px; margin-right: 5px;"> Find now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
