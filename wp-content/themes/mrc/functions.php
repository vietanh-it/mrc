<?php

define('THEME_URL', get_template_directory_uri());
if ( ! isset( $content_width ) ) {
    $content_width = 620;
}

function r_theme_setup() {
    add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'r_theme_setup');

function r_scripts_styles() {
    $version = '20150116_1200';

    wp_enqueue_style('bootstrap', THEME_URL . '/css/bootstrap.min.css', array(), '1.0');
    wp_enqueue_style('jquery-ui', THEME_URL . '/css/jquery-ui.min.css', array(), '1.0');
    wp_enqueue_style('awesome', THEME_URL . '/css/font-awesome.min.css', array(), '1.0');
    wp_enqueue_style('nivo-css', THEME_URL . '/css/owl.carousel.css', array(), '2.0');
    wp_enqueue_style('fancybox-css', THEME_URL . '/css/jquery.fancybox.css', array(), '2.0');
    wp_enqueue_style('sweet-alert-css', THEME_URL . '/css/sweet-alert.css', array(), '2.0');
    wp_enqueue_style('animate-css', THEME_URL . '/css/animate.css', array(), '2.0');

    wp_enqueue_style('em-style-ext', THEME_URL . '/style.css', array(), $version);


    wp_enqueue_script('jquery-ui', THEME_URL . '/js/jquery-ui.min.js', array(), '', true);
    wp_enqueue_script('bootstrap-custom.min.js', THEME_URL . '/js/bootstrap.min.js', array(), '3.0', true);
    wp_enqueue_script('jquery-wow', THEME_URL . '/js/wow.js', array(), '3.0', true);
    wp_enqueue_script('jquery-number', THEME_URL . '/js/jquery.number.min.js', array(), '3.0', true);
    wp_enqueue_script('validation', THEME_URL . '/js/jquery.validate.min.js', array('jquery'), '3.0', true);
    wp_enqueue_script('jquery-lazyload', THEME_URL . '/js/jquery.lazyload.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('jquery-cycle', THEME_URL . '/js/jquery.cycle.js', array('jquery'), '1.0', true);
    wp_enqueue_script('swal', THEME_URL . '/js/sweet-alert.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('nivo-js', THEME_URL . '/js/owl.carousel.js', array('jquery'), '1.0', true);
    wp_enqueue_script('fancybox', THEME_URL . '/js/jquery.fancybox.pack.js', array('jquery'), 1.0, true);
    wp_enqueue_script('my-app-ext', THEME_URL . '/js/main.js', array('jquery'), $version, true);
    wp_localize_script( 'my-app-ext', 'MyAjax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

}

add_action('wp_enqueue_scripts', 'r_scripts_styles');


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') and !current_user_can('editor')) {
        show_admin_bar(false);
    }
}