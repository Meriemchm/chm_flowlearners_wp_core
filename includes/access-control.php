<?php

// Prevent direct access for student classroom pages
if (!defined('ABSPATH')) exit;

add_action('template_redirect', function () {

    $page_id = get_queried_object_id();
    if (!$page_id) return;

    $slug = get_post_field('post_name', $page_id);

    // Prevent caching engines from storing dynamic dashboard and classroom pages
    if ($slug === 'manage-groups' || $slug === 'edit-group' || ($slug && str_starts_with($slug, 'page-classe-'))) {
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        nocache_headers();
    }

    if (!is_user_logged_in()) return;

    $user = wp_get_current_user();

    // Admins and tutors always have access (using robust current_user_can)
    if (current_user_can('administrator') || current_user_can('tutor') || array_intersect(['administrator', 'tutor'], (array) $user->roles)) {
        return;
    }

    // Accepte tous les slugs générés dynamiquement (lettres, chiffres, tirets)
    if (!preg_match('/^page-classe-([a-z0-9\-]+)/i', $slug, $matches)) {
        return;
    }


    // Nom du groupe attendu (ex: A1, Explorer1, etc.)
    $expected_group = sanitize_title($matches[1]);
// on ne force plus strtoupper, on compare en lowercase

    $groups_user = new Groups_User($user->ID);
    $user_group_ids = $groups_user->group_ids_deep;

    $has_access = false;

    foreach ($user_group_ids as $group_id) {
        $group = Groups_Group::read($group_id);

        if (!$group) continue;

        // On ignore "Registered"
        if (strtolower($group->name) === 'registered') continue;

        if (sanitize_title($group->name) === $expected_group) {
            $has_access = true;
            break;
        }

    }

    if (!$has_access) {
        wp_redirect(home_url());
        exit;
    }
});
