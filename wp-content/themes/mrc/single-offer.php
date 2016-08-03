<?php
global  $post;
$ctrl = \RVN\Controllers\OfferController::init();
$ctrl->offerDetail($post->ID);