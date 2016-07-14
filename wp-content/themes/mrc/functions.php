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

    wp_enqueue_style('plugin-css', THEME_URL . '/css/plugin.css', array(), $version);
    wp_enqueue_style('em-style-ext', THEME_URL . '/style.css', array(), $version);

    wp_enqueue_script('plugins-js', THEME_URL . '/js/plugins.js', array('jquery'), $version, true);
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