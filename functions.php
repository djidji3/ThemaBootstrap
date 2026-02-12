<?php

// Bootstrap Walker betöltése
require get_template_directory() . '/inc/class-bootstrap-navwalker.php';

// Bootstrap CSS és JS betöltése
function bootstrap_theme_enqueue_assets(){
	// Bootstrap CSS betöltése CDN-ről
	wp_enqueue_style(
		'bootstrap-css',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
		array(),
		'5.3.2',
		'all'
	);

	// Saját stílus betöltése
	wp_enqueue_style(
		'theme-style',
		get_stylesheet_uri(),
		array('bootstrap-css'),
		'1.0',
		'all'
	);

	// Bootstrap JS bundle betöltése
	wp_enqueue_script(
		'bootstrap-js',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.2',
		true
	);
}

// Menü regisztrálása
function bootstrap_register_menus(){
    register_nav_menus(array(
        'primary' => 'Főmenü'
    ));
}

// Hook használata
add_action('wp_enqueue_scripts', 'bootstrap_theme_enqueue_assets');

// Menü hozzáadása
add_action('after_setup_theme', 'bootstrap_register_menus');

