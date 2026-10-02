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


            <!-- Zoom Virtual Classroom Section -->
            <?php
            $zoom_enabled = get_post_meta(get_the_ID(), 'zoom_enabled', true);
            if ($zoom_enabled === '') {
                $zoom_enabled = get_post_meta(get_the_ID(), 'jitsi_enabled', true) ?: '0';
            }
            $zoom_link = get_post_meta(get_the_ID(), 'zoom_link', true);
            if (empty($zoom_link)) {
                $zoom_link = get_post_meta(get_the_ID(), 'jitsi_link', true);
            }
            ?>

            <?php if ($zoom_enabled === '1'): ?>

                <div class="fl-class-zoom-open" style="padding:20px; background:#f4f5f6; border:1px solid #c3e6cb; border-radius:6px; text-align:center;">
                    <p style="color:#155724; font-weight:bold; margin:0 0 14px 0;">
                        The class has started! Click below to join the live session.
                    </p>
                    <?php if (!empty($zoom_link)): ?>
                        <a href="<?= esc_url($zoom_link); ?>" target="_blank" rel="noopener noreferrer" class="fl-zoom-btn" style="display:inline-block; background:#203955; color:#ffffff !important; padding:12px 28px; border-radius:6px; font-size:15px; font-weight:600; text-decoration:none !important;">
                            Join Zoom Classroom
                        </a>
                    <?php else: ?>
                        <p style="color:#856404; font-weight:bold; margin:0;">
                            The class session is open, but no Zoom link has been added yet.
                        </p>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <div class="fl-class-zoom-closed" style="padding:20px; background:#f4f5f6; border:1px solid #ffeeba; border-radius:6px; text-align:center;">
                    <p style="color:#856404; font-weight:bold; margin:0;">
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
