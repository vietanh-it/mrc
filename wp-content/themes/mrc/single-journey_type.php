<?php
global  $post;
$ctrl = \RVN\Controllers\JourneyTypeController::init();
$ctrl->journeyTypeDetail($post->ID);