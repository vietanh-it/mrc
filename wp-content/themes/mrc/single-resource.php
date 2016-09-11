<?php
global $post;
$ctr = \RVN\Controllers\ResourceController::init();
$ctr->detail($post->ID);