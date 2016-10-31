<?php
global $post;
$ctr = \RVN\Controllers\PortController::init();
$ctr->detail($post->ID);