<?php

define('THEME_URL', get_template_directory_uri());
if (!isset($content_width)) {
    $content_width = 620;
}

function r_theme_setup()
{

    add_image_size('featured', 500, 500, true);
    add_image_size('small', 485, 360, true);
    add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'r_theme_setup');

function r_scripts_styles()
{
    $version = '20160116_1200';

    wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
    wp_enqueue_style('awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css');
    wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
    wp_enqueue_style('sweet-alert-css', 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css');
    wp_enqueue_style('fancybox-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css');
    wp_enqueue_style('carousel-css', 'https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css');


    wp_enqueue_style('animate-css', VIEW_URL . '/css/animate.css', array(), '2.0');
    wp_enqueue_style('MonthPicker-css', VIEW_URL . '/css/MonthPicker.min.css', array(), '2.0');

    wp_enqueue_style('em-main', VIEW_URL . '/css/main.css', array(), $version);
    wp_enqueue_style('em-style-ext', THEME_URL . '/style.css', array(), $version);


    wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js', array(), false, true);
    wp_enqueue_script('bootstrap-custom.min.js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', array(), false, true);
    wp_enqueue_script('jquery-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',  array(), false, true);
    wp_enqueue_script('swal', 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js' , array(), false, true);
    wp_enqueue_script('fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js', array(), false, true);
    wp_enqueue_script('carousel-js', "https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js", array(), false, true);
    wp_enqueue_script('jquery-number', 'https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js', array(), false, true);
    wp_enqueue_script('jquery-lazyload', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js', array(), false, true);
    wp_enqueue_script('validation', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js', array(), false, true);
    //wp_enqueue_script('jquery-cycle', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle/3.0.3/jquery.cycle.all.min.js', array(), false, true);
    wp_enqueue_script('jquery-MonthPicker', VIEW_URL . '/js/MonthPicker.min.js', array(), '3.0', true);

    wp_enqueue_script('my-app-ext', VIEW_URL . '/js/main.js', array('jquery'), $version, true);
    wp_localize_script('my-app-ext', 'MyAjax', array('ajax_url' => admin_url('admin-ajax.php') ,'app_fb_id' => FACEBOOK_APP_ID ));

}

add_action('wp_enqueue_scripts', 'r_scripts_styles');


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    if (!current_user_can('administrator') and !current_user_can('editor')) {
        show_admin_bar(false);
    }
}