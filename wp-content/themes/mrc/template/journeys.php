<?php
/**
 * Template name: Journey List
 */

if(empty($_GET)){
    $journey_ctrl = \RVN\Controllers\JourneyTypeController::init();
    $journey_ctrl->journeyTypeList();
}else{
    $journey_ctrl = \RVN\Controllers\JourneyController::init();
    $journey_ctrl->journeyList($_GET);
}
