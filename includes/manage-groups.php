<?php
if (!defined('ABSPATH')) exit;

/**
 * 🔹 Toggle Zoom Access ON/OFF
 * Toggles whether students can currently join the live Zoom session for this group.
 */
add_action('init', function () {
    $has_toggle = isset($_GET['toggle_zoom']) || isset($_GET['toggle_jitsi']);
    if (!$has_toggle || !isset($_GET['group_id'])) return;

    if (!current_user_can('tutor') && !current_user_can('administrator')) return;

    $pid = intval($_GET['group_id']);
    if (!$pid) return;

    // Nonce verification for security
    $nonce = isset($_GET['fl_zoom_nonce']) ? sanitize_text_field(wp_unslash($_GET['fl_zoom_nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'fl_toggle_zoom_' . $pid)) {
        wp_die(__('Security check failed. Please refresh the page and try again.', 'flowlearners'), 403);
    }

    // Toggle Zoom access state
    $current = get_post_meta($pid, 'zoom_enabled', true);
    if ($current === '') {
        $current = get_post_meta($pid, 'jitsi_enabled', true) ?: '0';
    }
    $new = ($current === '1') ? '0' : '1';
    update_post_meta($pid, 'zoom_enabled', $new);

    // Clean up legacy meta if present
    delete_post_meta($pid, 'jitsi_enabled');

    // Force browser not to cache response
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Safe redirect back
    $redirect = wp_get_referer() ?: site_url('/manage-groups');
    $redirect = remove_query_arg(['toggle_zoom', 'toggle_jitsi', 'group_id', 'fl_zoom_nonce'], $redirect);
    wp_safe_redirect($redirect);
    exit;
});


/**
 * 🔹 Manage Groups Table Shortcode [fl_manage_groups]
 */
add_shortcode('fl_manage_groups', function () {

    $user = wp_get_current_user();

    if (!array_intersect(['administrator', 'tutor'], $user->roles)) {
        return '<p>Accès interdit</p>';
    }

    // Récupère dynamiquement tous les groupes
    $all_groups = Groups_Group::get_groups();
    
    $groups = [];

    foreach ($all_groups as $group) {
        if (strtolower($group->name) === 'registered') continue; // ignore Registered
        $groups[] = $group->name;
    }

    ob_start(); ?>

    <!-- HTML -->
    <div class="fl-dashboard-content">

        <div class="fl-manage-groups-card">
            <div class="fl-card-header">
                <h4>Manage Groups</h4>
                <p class="fl-card-description">
                    Manage schedules, days, and Zoom virtual classroom access for each group.
                </p>
            </div>

            <table class="fl-table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Times</th>
                        <th>Days</th>
                        <th>Period</th>
                        <th>Zoom Link</th>
                        <th>Class Access</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($groups as $g):
                    $page = get_page_by_path('page-classe-' . sanitize_title($g));
                    if (!$page) continue;

                    $schedule = get_post_meta($page->ID, 'class_schedule', true);
                    $days     = get_post_meta($page->ID, 'class_days', true);
                    $period   = get_post_meta($page->ID, 'class_period', true);

                    // Zoom meeting link for this group
                    $zoom_link = get_post_meta($page->ID, 'zoom_link', true);
                    if (empty($zoom_link)) {
                        $zoom_link = get_post_meta($page->ID, 'jitsi_link', true);
                    }

                    // Zoom access status ON/OFF
                    $zoom_enabled = get_post_meta($page->ID, 'zoom_enabled', true);
                    if ($zoom_enabled === '') {
                        $zoom_enabled = get_post_meta($page->ID, 'jitsi_enabled', true) ?: '0';
                    }
                    $is_open = ($zoom_enabled === '1');

                    // Nonce URL for toggling
                    $toggle_url = wp_nonce_url(
                        site_url('?toggle_zoom=1&group_id=' . $page->ID),
                        'fl_toggle_zoom_' . $page->ID,
                        'fl_zoom_nonce'
                    );
                ?>
                    <tr>
                        <td><?= esc_html($g) ?></td>
                        <td><?= esc_html($schedule ?: '—') ?></td>
                        <td><?= esc_html($days ?: '—') ?></td>
                        <td><?= esc_html($period ?: '—') ?> Months</td>
                        <td>
                            <?php if (!empty($zoom_link)): ?>
                                <a href="<?= esc_url($zoom_link) ?>" target="_blank">Zoom Link</a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Toggle Zoom Access -->
                            <a class="fl-action-btn"
                               href="<?= esc_url($toggle_url) ?>"
                               style="color: <?= $is_open ? '#28a745' /* vert */ : '#dc3545' /* rouge */ ?>;">
                                <?= $is_open ? 'Open' : 'Closed' ?>
                            </a>
                        </td>
                        <td>
                            <!-- Edit Group (Group Actions) -->
                            <a class="fl-action-btn"
                               href="<?= esc_url(site_url('/edit-group/?group_id=' . $page->ID)) ?>"
                               title="Edit Group & Zoom Link">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </div>
    <?php
    return ob_get_clean();
});
