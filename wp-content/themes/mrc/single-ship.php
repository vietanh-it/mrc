<?php
global $post;
$ctrl = \RVN\Controllers\ShipController::init();
$ctrl->detailShip($post->ID);