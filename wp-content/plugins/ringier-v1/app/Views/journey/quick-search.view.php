<?php
$objShip = \RVN\Controllers\ShipController::init();
$objDestination = \RVN\Controllers\DestinationController::init();
$objPort = \RVN\Controllers\PortController::init();

$list_destination = $objDestination->getDestinationList();
$list_port = $objPort->getPortList();
$list_ship = $objShip->getSipList();

$destination = $_GET['_destination'] ? $_GET['_destination'] : '';
$ship = $_GET['_ship'] ? $_GET['_ship'] : '';
$port = $_GET['_port'] ? $_GET['_port'] : '';
$month = $_GET['_month'] ? $_GET['_month'] : '';

?>
<div class="quick-search-journey">
    <form method="get">
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
                                    <option value="">Choose your destination</option>
                                    <?php if($list_destination['data']){
                                        foreach ($list_destination['data'] as $v){ ?>
                                            <option value="<?php echo $v->post_name ?>" <?php echo $v->post_name == $destination ? 'selected': '' ?>> <?php echo $v->post_title ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <span class="icon-n icon-location"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <input type="text" name="_month" class="form-control month-year-input" placeholder="Choose sail month" value="<?php echo $month ?>">
                                <span class="icon-n icon-date"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select name="_port" class="form-control select-2">
                                    <option value="">Choose port of departure</option>
                                    <?php if($list_port['data']){
                                        foreach ($list_port['data'] as $v){ ?>
                                            <option value="<?php echo $v->post_name ?>" <?php echo $v->post_name == $port ? 'selected': '' ?>> <?php echo $v->post_title ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <span class="icon-n icon-port"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select name="_ship" class="form-control select-2">
                                    <option value="">Choose your ship</option>
                                    <?php if($list_ship['data']){
                                        foreach ($list_ship['data'] as $v){ ?>
                                            <option value="<?php echo $v->post_name ?>" <?php echo $v->post_name == $ship ? 'selected': '' ?>> <?php echo $v->post_title ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <span class="icon-n icon-ship"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2">
                    <div class="text-center">
                        <button type="submit"> <i class="fa fa-search" aria-hidden="true"></i> Find now</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
