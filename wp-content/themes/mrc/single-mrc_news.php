<?php
global $post;
$ctr = \RVN\Controllers\NewsController::init();
$ctr->detail($post->ID);