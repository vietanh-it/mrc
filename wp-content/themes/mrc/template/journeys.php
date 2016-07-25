<?php
/**
 * Template name: Journey List
 */

$journey_ctrl = \RVN\Controllers\JourneyController::init();
$journey_ctrl->journeyList($_GET);