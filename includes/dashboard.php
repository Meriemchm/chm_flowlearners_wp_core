<?php
if (!defined('ABSPATH')) exit;

add_shortcode('fl_dashboard', function () {
    $user = wp_get_current_user();

    ob_start();
    ?>

    <div class="fl-dashboard-card">
        <div class="fl-left">
            <p class="fl-title">Dashboard</p>
            <h2>Hi, <?php echo esc_html($user->display_name); ?>!</h2>
            <p class="fl-subtitle">What are we doing today?</p>
        </div>

        <div class="fl-right">
            <img src="<?php echo FL_URL . 'assets/img/dashboard-img.svg'; ?>" alt="Mascot">
        </div>
    </div>

    <?php
    return ob_get_clean();
});
