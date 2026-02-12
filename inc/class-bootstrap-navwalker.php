<?php

class Bootstrap_5_Nav_Walker extends Walker_Nav_Menu {

    // Almenü indítása
    function start_lvl( &$output, $depth = 0, $args = null ) {

        $output .= '<ul class="dropdown-menu">';
    }

    // Menüelem indítása
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $has_children = in_array('menu-item-has-children', $classes);

        if ($has_children && $depth === 0) {

            $output .= '<li class="nav-item dropdown">';

            $output .= '<a class="nav-link dropdown-toggle" href="' . esc_url($item->url) . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';

            $output .= esc_html($item->title);

            $output .= '</a>';

        } else {

            if ($depth === 0) {
                $output .= '<li class="nav-item">';
                $output .= '<a class="nav-link" href="' . esc_url($item->url) . '">';
            } else {
                $output .= '<li>';
                $output .= '<a class="dropdown-item" href="' . esc_url($item->url) . '">';
            }

            $output .= esc_html($item->title);
            $output .= '</a>';
        }
    }

    function end_el( &$output, $item, $depth = 0, $args = null ) {

        $output .= '</li>';
    }
}

