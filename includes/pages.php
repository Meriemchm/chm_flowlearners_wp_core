<?php
if (!defined('ABSPATH')) exit;

add_action('init', function() {
$groups = Groups_Group::get_groups();

foreach ($groups as $group) {

    $group_name = $group->name;

    // garder uniquement A1, A2, B1, B2, C1, C2 (et futurs D1, etc.)
    // Accepte A1, B2, C1… et Explorer1, Communicator2
    if (!preg_match('/^([A-Z][0-9]|[A-Za-z]+[0-9]+)$/', $group_name)) {
        continue;
    }


    $slug = 'page-classe-' . strtolower($group_name);

    if (!get_page_by_path($slug)) {
        wp_insert_post([
            'post_title'   => 'Classe ' . $group_name,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => 'Bienvenue dans la classe ' . $group_name . '! Le lien Jitsi sera affiché ici.'
        ]);
    }
}

    if (!get_page_by_path('dashboard')) {
        wp_insert_post([
            'post_title' => 'Dashboard',
            'post_name' => 'dashboard',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => '[fl_dashboard]'
        ]);
    }
});
