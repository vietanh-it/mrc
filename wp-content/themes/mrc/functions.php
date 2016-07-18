<?php

define('THEME_URL', get_template_directory_uri());
if (!isset($content_width)) {
    $content_width = 620;
}

function r_theme_setup()
{

    add_image_size('featured', 600, 600, true);
    add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'r_theme_setup');

function r_scripts_styles()
{
    $version = '20150116_1200';

    wp_enqueue_style('bootstrap', VIEW_URL . '/css/bootstrap.min.css', array(), '1.0');
    wp_enqueue_style('jquery-ui', VIEW_URL . '/css/jquery-ui.min.css', array(), '1.0');
    wp_enqueue_style('awesome', VIEW_URL . '/css/font-awesome.min.css', array(), '1.0');
    wp_enqueue_style('nivo-css', VIEW_URL . '/css/owl.carousel.css', array(), '2.0');
    wp_enqueue_style('fancybox-css', VIEW_URL . '/css/jquery.fancybox.css', array(), '2.0');
    wp_enqueue_style('sweet-alert-css', VIEW_URL . '/css/sweet-alert.css', array(), '2.0');
    wp_enqueue_style('animate-css', VIEW_URL . '/css/animate.css', array(), '2.0');

    wp_enqueue_style('em-main', VIEW_URL . '/css/main.css', array(), $version);
    wp_enqueue_style('em-style-ext', THEME_URL . '/style.css', array(), $version);


    wp_enqueue_script('jquery-ui', VIEW_URL . '/js/jquery-ui.min.js', array(), '', true);
    wp_enqueue_script('bootstrap-custom.min.js', VIEW_URL . '/js/bootstrap.min.js', array(), '3.0', true);
    wp_enqueue_script('jquery-wow', VIEW_URL . '/js/wow.js', array(), '3.0', true);
    wp_enqueue_script('jquery-number', VIEW_URL . '/js/jquery.number.min.js', array(), '3.0', true);
    wp_enqueue_script('validation', VIEW_URL . '/js/jquery.validate.min.js', array('jquery'), '3.0', true);
    wp_enqueue_script('jquery-lazyload', VIEW_URL . '/js/jquery.lazyload.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('jquery-cycle', VIEW_URL . '/js/jquery.cycle.js', array('jquery'), '1.0', true);
    wp_enqueue_script('swal', VIEW_URL . '/js/sweet-alert.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('nivo-js', VIEW_URL . '/js/owl.carousel.js', array('jquery'), '1.0', true);
    wp_enqueue_script('fancybox', VIEW_URL . '/js/jquery.fancybox.pack.js', array('jquery'), 1.0, true);
    wp_enqueue_script('my-app-ext', VIEW_URL . '/js/main.js', array('jquery'), $version, true);
    wp_localize_script('my-app-ext', 'MyAjax', array('ajax_url' => admin_url('admin-ajax.php')));

}

add_action('wp_enqueue_scripts', 'r_scripts_styles');


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    if (!current_user_can('administrator') and !current_user_can('editor')) {
        show_admin_bar(false);
    }
}