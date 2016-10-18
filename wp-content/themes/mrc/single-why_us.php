<?php
global $post;
$ctr = \RVN\Controllers\WhyUsController::init();
$ctr->detail($post->ID);