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


    wp_enqueue_script('my-app-ext', THEME_URL . '/js/app-ext.js', array('jquery'), $version, true);
    wp_localize_script( 'my-app-ext', 'MyAjax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

}

add_action('wp_enqueue_scripts', 'r_scripts_styles');

function r_widgets_init() {
    register_sidebar( array(
        'name'          => 'Block meta tag',
        'id'            => 'block-meta-tag',
        'description'   => 'Block các thẻ meta tại sidebar',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ) );
}
add_action( 'widgets_init', 'r_widgets_init' );


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') and !current_user_can('editor')) {
        show_admin_bar(false);
    }
}