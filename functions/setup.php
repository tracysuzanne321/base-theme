<?php
function my_theme_setup() {
    add_theme_support('custom-logo');
    register_nav_menus([
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu',
    ]);
}
add_action('after_setup_theme', 'my_theme_setup');

function my_theme_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
    // Enqueue Font Awesome for icons
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
}
add_action('wp_enqueue_scripts', 'my_theme_styles');