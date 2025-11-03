<?php
/*	-----------------------------------------------------------------------------------------------
    ENQUEUE ASSETS IN GUTENBERG EDITOR
--------------------------------------------------------------------------------------------------- */
add_action('after_setup_theme', 'bcwx_editor_styles');
function bcwx_editor_styles() {
    add_editor_style(get_stylesheet_directory_uri() . '/assets/css/theme.css');
}

add_action('enqueue_block_assets', 'bcwx_enqueue_gutenberg_editor_assets');
function bcwx_enqueue_gutenberg_editor_assets() {
    // Here you can add CSS files to gutenberg editor
}

add_action('enqueue_block_editor_assets', 'bcwx_enqueue_block_variations');
function bcwx_enqueue_block_variations() {
    // Here you can add JS files to gutenberg editor
}

/*	-----------------------------------------------------------------------------------------------
    ENQUEUE STYLESHEETS
--------------------------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', 'bcwx_enqueue_styles');
function bcwx_enqueue_styles() {
    wp_enqueue_style('bcwx', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('bcwx-theme', get_stylesheet_directory_uri() . '/assets/css/theme.css', ['bcwx'], wp_get_theme()->get('Version'));
}

/*	-----------------------------------------------------------------------------------------------
    ENQUEUE JAVASCRIPTS
--------------------------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', 'bcwx_enqueue_scripts');
function bcwx_enqueue_scripts() {
    wp_enqueue_script('bcwx-theme', get_stylesheet_directory_uri() . '/assets/js/theme.js', [], wp_get_theme()->get('Version'), true);
}