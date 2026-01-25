<?php 
if (!defined('ABSPATH')) exit;

add_shortcode('fl_edit_group', function () {

    if (!current_user_can('tutor') && !current_user_can('administrator')) {
        return '<p>Accès interdit</p>';
    }

    if (empty($_GET['group_id'])) {
        return '<p>Group not found</p>';
    }

    $pid = intval($_GET['group_id']);

    // Données actuelles
    $schedule = get_post_meta($pid, 'class_schedule', true);
    $days     = get_post_meta($pid, 'class_days', true);
    $period   = get_post_meta($pid, 'class_period', true);
    $jitsi    = get_post_meta($pid, 'jitsi_link', true);

    // UPDATE
    if (!empty($_POST['fl_update_group'])) {

        if (isset($_POST['class_schedule'])) {
            update_post_meta($pid, 'class_schedule', sanitize_text_field($_POST['class_schedule']));
        }

        if (isset($_POST['class_days'])) {
            update_post_meta($pid, 'class_days', sanitize_text_field($_POST['class_days']));
        }

        if (isset($_POST['class_period'])) {
            update_post_meta($pid, 'class_period', sanitize_text_field($_POST['class_period']));
        }

        // if (isset($_POST['jitsi_link'])) {
        //     update_post_meta($pid, 'jitsi_link', esc_url($_POST['jitsi_link']));
        // }

        echo '<p style="color:green;">Group updated successfully ✔</p>';

        // Recharger valeurs
        $schedule = get_post_meta($pid, 'class_schedule', true);
        $days     = get_post_meta($pid, 'class_days', true);
        $period   = get_post_meta($pid, 'class_period', true);
        // $jitsi    = get_post_meta($pid, 'jitsi_link', true);
    }

    ob_start(); ?>
        <div class="fl-edit-group-card">
            <h3>Edit Group</h3>
            <p class="fl-form-description">
                Update group schedule, days or period.
            </p>

            <?php if (!empty($_POST['fl_update_group'])): ?>
                <div class="fl-success">Group updated successfully ✔</div>
            <?php endif; ?>

            <form method="post">

                <div class="fl-form-group">
                    <label>Times</label>
                    <input type="text" name="class_schedule"
                        value="<?= esc_attr($schedule) ?>">
                </div>

                <div class="fl-form-group">
                    <label>Days</label>
                    <input type="text" name="class_days"
                        value="<?= esc_attr($days) ?>">
                </div>

                <div class="fl-form-group">
                    <label>Period</label>
                    <input type="text" name="class_period"
                        value="<?= esc_attr($period) ?>">
                </div>

                <!-- <div class="fl-form-group">
                    <label>Jitsi Link</label>
                    <input type="text" name="jitsi_link"
                        value="<?= esc_attr($jitsi) ?>">
                </div> -->

                <input type="submit" name="fl_update_group" value="Update">
            </form>

            <a href="<?= site_url('/manage-groups') ?>">← Back to manage groups</a>
        </div>

    <?php

    return ob_get_clean();
});
