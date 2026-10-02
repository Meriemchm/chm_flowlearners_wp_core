<?php 
if (!defined('ABSPATH')) exit;

/**
 * Shortcode [fl_edit_group]
 * Allows tutors and administrators to update class schedule, days, period,
 * and configure the Zoom meeting link for each class/group.
 */
add_shortcode('fl_edit_group', function () {

    if (!current_user_can('tutor') && !current_user_can('administrator')) {
        return '<p>Accès interdit</p>';
    }

    if (empty($_GET['group_id'])) {
        return '<p>Group not found</p>';
    }

    $pid = intval($_GET['group_id']);
    if (!$pid) {
        return '<p>Invalid group ID</p>';
    }

    $updated = false;

    // UPDATE HANDLER
    if (!empty($_POST['fl_update_group'])) {
        // Nonce Verification
        $nonce = isset($_POST['fl_edit_group_nonce']) ? sanitize_text_field(wp_unslash($_POST['fl_edit_group_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'fl_edit_group_action_' . $pid)) {
            return '<div class="fl-error">Security check failed. Please reload the page.</div>';
        }

        if (isset($_POST['class_schedule'])) {
            update_post_meta($pid, 'class_schedule', sanitize_text_field($_POST['class_schedule']));
        }

        if (isset($_POST['class_days'])) {
            update_post_meta($pid, 'class_days', sanitize_text_field($_POST['class_days']));
        }

        if (isset($_POST['class_period'])) {
            update_post_meta($pid, 'class_period', sanitize_text_field($_POST['class_period']));
        }

        if (isset($_POST['zoom_link'])) {
            $zoom_link = esc_url_raw(trim($_POST['zoom_link']));
            update_post_meta($pid, 'zoom_link', $zoom_link);
            // Clean up legacy Jitsi meta
            delete_post_meta($pid, 'jitsi_link');
        }

        $updated = true;
    }

    // Load current values
    $schedule  = get_post_meta($pid, 'class_schedule', true);
    $days      = get_post_meta($pid, 'class_days', true);
    $period    = get_post_meta($pid, 'class_period', true);
    $zoom_link = get_post_meta($pid, 'zoom_link', true);
    if (empty($zoom_link)) {
        $zoom_link = get_post_meta($pid, 'jitsi_link', true);
    }

    ob_start(); ?>
        <div class="fl-edit-group-card">
            <h3>Edit Group & Zoom Link</h3>
            <p class="fl-form-description">
                Update class schedule, days, period, and the Zoom meeting link.
            </p>

            <?php if ($updated): ?>
                <div class="fl-success">Group updated successfully ✔</div>
            <?php endif; ?>

            <form method="post" action="">
                <?php wp_nonce_field('fl_edit_group_action_' . $pid, 'fl_edit_group_nonce'); ?>

                <div class="fl-form-group">
                    <label for="fl_class_schedule">Times</label>
                    <input type="text" id="fl_class_schedule" name="class_schedule"
                        value="<?= esc_attr($schedule) ?>" placeholder="e.g. 18:00 - 20:00">
                </div>

                <div class="fl-form-group">
                    <label for="fl_class_days">Days</label>
                    <input type="text" id="fl_class_days" name="class_days"
                        value="<?= esc_attr($days) ?>" placeholder="e.g. Monday & Wednesday">
                </div>

                <div class="fl-form-group">
                    <label for="fl_class_period">Period (Months)</label>
                    <input type="text" id="fl_class_period" name="class_period"
                        value="<?= esc_attr($period) ?>" placeholder="e.g. 3">
                </div>

                <div class="fl-form-group">
                    <label for="fl_zoom_link">Zoom Meeting Link</label>
                    <input type="url" id="fl_zoom_link" name="zoom_link"
                        value="<?= esc_attr($zoom_link) ?>"
                        placeholder="https://us02web.zoom.us/j/1234567890?pwd=...">
                    <small style="color:#6B7280; font-size:12px; margin-top:4px;">
                        Paste the Zoom meeting invite link. Enrolled students will see this link as a "Join Class" button when the class is opened.
                    </small>
                </div>

                <input type="submit" name="fl_update_group" value="Update">
            </form>

            <a href="<?= esc_url(site_url('/manage-groups')) ?>">← Back to manage groups</a>
        </div>

    <?php

    return ob_get_clean();
});
