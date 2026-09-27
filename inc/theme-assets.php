<?php
/**
 * Front-end and editor assets.
 *
 * Everything here is built by `@wordpress/scripts` from `assets/src/` into
 * `assets/build/`, which is committed. The bundle's dependency list and content
 * hash come from the generated `index.asset.php`, so neither is guessed here.
 *
 * @package Basecoat
 */

/**
 * Register the block editor stylesheet.
 *
 * @return void
 */
function basecoat_editor_styles() {
	add_editor_style( get_stylesheet_directory_uri() . '/assets/build/index.css' );
}
add_action( 'after_setup_theme', 'basecoat_editor_styles' );

/**
 * Enqueue the front-end stylesheets.
 *
 * @return void
 */
function basecoat_enqueue_styles() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'basecoat', get_stylesheet_uri(), array(), $version );
	wp_enqueue_style(
		'basecoat-theme',
		get_stylesheet_directory_uri() . '/assets/build/index.css',
		array( 'basecoat' ),
		$version
	);
}
add_action( 'wp_enqueue_scripts', 'basecoat_enqueue_styles' );

/**
 * Enqueue the front-end script.
 *
 * `index.asset.php` carries the dependencies webpack resolved and a hash of the
 * bundle, so WordPress can cache-bust without a version constant to maintain.
 *
 * @return void
 */
function basecoat_enqueue_scripts() {
	$asset_file = get_stylesheet_directory() . '/assets/build/index.asset.php';

	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = require $asset_file;

	wp_enqueue_script(
		'basecoat-theme',
		get_stylesheet_directory_uri() . '/assets/build/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);
}
add_action( 'wp_enqueue_scripts', 'basecoat_enqueue_scripts' );
