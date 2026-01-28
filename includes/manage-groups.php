<?php
if (!defined('ABSPATH')) exit;

// 🔹 Toggle Jitsi ON/OFF
add_action('init', function () {
    if (!isset($_GET['toggle_jitsi'], $_GET['group_id'])) return;
    if (!current_user_can('tutor') && !current_user_can('administrator')) return;

    $pid = intval($_GET['group_id']);

    // Toggle Jitsi
    $current = get_post_meta($pid, 'jitsi_enabled', true);
    $new = ($current === '1') ? '0' : '1';
    update_post_meta($pid, 'jitsi_enabled', $new);

    // Forcer le navigateur à ne pas utiliser le cache
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Redirect vers la page précédente
    $redirect = wp_get_referer() ?: site_url(); // fallback si wp_get_referer vide
    wp_safe_redirect($redirect);
    exit;
});


// 🔹 Manage Groups Table
add_shortcode('fl_manage_groups', function () {

    if (!current_user_can('tutor') && !current_user_can('administrator')) {
        return '<p>Accès interdit</p>';
    }

    // 🔹 Récupère dynamiquement tous les groupes
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
                    Manage schedules, days and Jitsi access for each group.
                </p>
            </div>

            <table class="fl-table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Times</th>
                        <th>Days</th>
                        <th>Period</th>
                        <th>Jitsi Link</th>
                        <th>Jitsi Access</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($groups as $g):
                    $page = get_page_by_path('page-classe-' . sanitize_title($g));
                    if (!$page) continue;

                    $schedule = get_post_meta($page->ID, 'class_schedule', true);
                    $days     = get_post_meta($page->ID, 'class_days', true);
                    $period = get_post_meta($page->ID, 'class_period', true);

                    // Transformation identique à $class_name
                    $name = preg_replace('/[\s\-]+([0-9]+)$/', ' Group $1', $g);


                    // Lien Jitsi
                    $jitsi = 'https://jitsi-01.csn.tu-chemnitz.de/meeting/classroom-' . sanitize_title($name);

                    // 🔹 Jitsi enabled ON/OFF
                    $jitsi_enabled = get_post_meta($page->ID, 'jitsi_enabled', true) === '1';
                ?>
                    <tr>
                        <td><?= esc_html($g) ?></td>
                        <td><?= esc_html($schedule ?: '—') ?></td>
                        <td><?= esc_html($days ?: '—') ?></td>
                        <td><?= esc_html($period ?: '—') ?> Months</td>
                        <td>
                            <a href="<?= esc_url($jitsi) ?>" target="_blank">Jitsi Link</a>
                        </td>
                        <td>
                            <!-- Toggle Jitsi Access -->
                            <a class="fl-action-btn"
                               href="<?= site_url('?toggle_jitsi=1&group_id=' . $page->ID) ?>"
                               style="color: <?= $jitsi_enabled ? '#28a745' /* vert */ : '#dc3545' /* rouge */ ?>;">
                                <?= $jitsi_enabled ? 'Open' : 'Closed' ?>
                            </a>
                        </td>
                        <td>
                            <!-- Edit Group -->
                            <a class="fl-action-btn"
                               href="<?= site_url('/edit-group/?group_id=' . $page->ID) ?>">
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
