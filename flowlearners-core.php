<?php
/*
Plugin Name: FlowLearners Core
Description: Core features for FlowLearners platform learning
Version: 1.5.0
Author: Meriem
*/


if (!defined('ABSPATH')) exit;

define('FL_PATH', plugin_dir_path(__FILE__));
define('FL_URL', plugin_dir_url(__FILE__));
register_activation_hook(__FILE__, 'fl_create_pages');

/* Includes */
require_once FL_PATH . 'includes/pages.php';
require_once FL_PATH . 'includes/access-control.php';
require_once FL_PATH . 'includes/manage-groups.php';
require_once FL_PATH . 'includes/dashboard.php';
require_once FL_PATH . 'includes/menu.php';
require_once FL_PATH . 'includes/class-page-template.php';
require_once FL_PATH . 'includes/edit-group.php';



add_action('wp_enqueue_scripts', function () {
    // CSS principal
    wp_enqueue_style('fl-style', FL_URL . 'assets/css/app.css');

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'
    );

    // JS principal (app.js) — dépend de AOS
    wp_enqueue_script(
        'fl-app',
        FL_URL . 'assets/js/app.js',
        array('aos-js'), // assure que AOS est chargé avant
        '1.0',
        true
    );
});

