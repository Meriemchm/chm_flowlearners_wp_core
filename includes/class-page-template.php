<?php
if (!defined('ABSPATH')) exit;

add_filter('the_content', function($content){
    if(!is_page()) return $content;

    $page_slug = get_post_field('post_name', get_the_ID());

    if(str_starts_with($page_slug, 'page-classe-')){

        $page_slug = get_post_field('post_name', get_the_ID());

        // enlever "page-classe-"
        $name = str_replace('page-classe-', '', $page_slug);

        // remplace le dernier espace ou tiret avant le dernier chiffre par " Group "
        $name = preg_replace('/[\s\-]+([0-9]+)$/', ' Group $1', $name);

        $class_name = get_post_meta(get_the_ID(), 'class_name', true)
            ?: 'Classroom ' . ucwords($name);

        $teacher        = get_post_meta(get_the_ID(), 'teacher_name', true) ?: 'Mansouri Bouchra';
        $banner         = get_post_meta(get_the_ID(), 'class_banner', true);
        $period = get_post_meta(get_the_ID(), 'class_period', true) ?: 'Not defined';

        ob_start();
        ?>
        <div class="fl-class-page">

        <div class="fl-class-cards">

            <!-- Card 1 -->
            <div class="fl-class-card fl-class-info-card">
                <?php if($banner): ?>
                    <img src="<?= $banner ?>" alt="<?= $class_name ?>" class="fl-class-banner">
                <?php endif; ?>

                <h3 class="fl-card-title"><?= $class_name ?></h3>

                <div class="fl-schedule-item">
                    <span class="fl-label">Tutor</span>
                    <span><?= $teacher ?></span>
                </div>
                <div class="fl-schedule-item">
                    <span class="fl-label">Period</span>
                    <span><?= $period ?> Months</span>
                </div>

            </div>

            <!-- Card 2 -->
            <div class="fl-class-card fl-class-calendar-card">
                <h3 class="fl-card-title">Schedule</h3>

                 <div class="fl-schedule-item">
                    <span class="fl-label">Days</span>
                    <span><?= get_post_meta(get_the_ID(), 'class_days', true) ?: 'Not defined' ?></span>
                </div>
                <br>

                <div class="fl-schedule-item">
                    <span class="fl-label">Times</span>
                    <span><?= get_post_meta(get_the_ID(), 'class_schedule', true) ?: 'Not defined' ?></span>
                </div>
               
            </div>

        </div>


                    <!-- FlexMeeting Iframe -->
                            <?php
                $jitsi_enabled = get_post_meta(get_the_ID(), 'jitsi_enabled', true);
                ?>

                <?php if ($jitsi_enabled === '1'): ?>

                    <?php
                        $room = sanitize_title($class_name);
                    ?>
                    <div class="fl-class-jitsi">
                        <?= apply_shortcodes(
                            '[jitsi-meet-wp name="' . esc_attr($room) . '" width="1080" height="720"]'
                        ); ?>
                    </div>


                <?php else: ?>

                    <div class="fl-class-jitsi-closed" style="padding:20px; background:#f4f5f6; border:1px solid #ffeeba; border-radius:6px; text-align:center;">
                    <p style="color:#856404; font-weight:bold;">
                        ⏳ The class has not started yet. Please refresh the page or revisit this page at the scheduled time to join the class.
                    </p>
                    </div>


                <?php endif; ?>



        </div>
        <?php
        return ob_get_clean();
    }

    return $content;
});
