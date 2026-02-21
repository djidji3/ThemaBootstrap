<?php

// Bootstrap Walker betöltése
require get_template_directory() . '/includes/class-bootstrap-navwalker.php';
global $wpdb; // Adatbázis eléréséhez, ha szükséges

// Hook használata
add_action('wp_enqueue_scripts', 'bootstrap_theme_enqueue_assets', 20); // 20-as prioritás, hogy a pluginok után töltődjön be

function bootstrap_theme_enqueue_assets()
{
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2', 'all');

    // Font Awesome
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');

    // Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true);

    // Saját JS
    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array('bootstrap-js'), '2.2.2', true);

    // **rekeszSettings lokalizálása a JS-nek**
    wp_localize_script('main-js', 'rekeszSettings', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('rekesz_mentes_nonce')
    ));

    // Saját stílus
    wp_enqueue_style('theme-style', get_stylesheet_uri(), array('bootstrap-css'), wp_get_theme()->get('Version'), 'all');
}


// Menü regisztrálása
function bootstrap_register_menus()
{
    register_nav_menus(array(
        'primary' => 'Főmenü'
    ));
}
add_action('after_setup_theme', 'bootstrap_register_menus');

// AJAX handler a mentéshez
add_action('wp_ajax_rekesz_mentes', 'rekesz_mentes_callback');
function rekesz_mentes_callback()
{

    check_ajax_referer('rekesz_mentes_nonce', 'security');

    global $wpdb;
    $termek_tabla = $wpdb->prefix . "termek";

    $adatok = json_decode(stripslashes($_POST['adatok']), true);

    if (!is_array($adatok)) {
        wp_send_json_error("Hibás adat.");
    }

    foreach ($adatok as $termek_id => $adat) {

        $wpdb->update(
            $termek_tabla,
            [
                'rekesz_id' => intval($adat['rekesz_id']),
                'mennyiseg' => floatval($adat['mennyiseg'])
            ],
            ['id' => intval($termek_id)],
            ['%d', '%f'],
            ['%d']
        );
    }

    wp_send_json_success("Automatikus mentés kész.");
}
