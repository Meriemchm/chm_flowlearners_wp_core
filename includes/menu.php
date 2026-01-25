<?php
if (!defined('ABSPATH')) exit;

/**
 * 1️⃣ Définir la visibilité
 */
add_action('init', function() {
    if (is_user_logged_in()) {
        define('VISIBILITY', 'logged_in');
    } else {
        define('VISIBILITY', 'guest');
    }
});

/**
 * 2️⃣ Ajouter une classe body selon la visibilité
 */
add_filter('body_class', function($classes) {
    if(defined('VISIBILITY') && VISIBILITY === 'logged_in') {
        $classes[] = 'user-logged-in';
    }
    return $classes;
});

/**
 * 3️⃣ Afficher la sidebar uniquement pour les utilisateurs connectés
 */
add_action('wp_body_open', function () {
    if(defined('VISIBILITY') && VISIBILITY === 'logged_in') {
        require_once plugin_dir_path(__FILE__) . 'sidebar.php';
    }
});

/**
 * 4️⃣ Charger CSS et icônes uniquement pour les utilisateurs connectés
 */
add_action('wp_enqueue_scripts', function () {
    if(!defined('VISIBILITY') || VISIBILITY !== 'logged_in') return;

    wp_enqueue_style(
        'fa',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'
    );

    wp_enqueue_style(
        'flow-app',
        plugin_dir_url(__FILE__) . '../assets/css/app.css',
        [],
        '1.0'
    );
});

/**
 * 5️⃣ Injecter CSS pour cacher tout le contenu du header et styliser le bouton profil
 */
add_action('wp_head', function() {
    if(!defined('VISIBILITY') || VISIBILITY !== 'logged_in') return;
    ?>
    <style>
        /* Cacher tout le contenu de la navbar */
        body.user-logged-in .site-header,
        body.user-logged-in .main-navigation,
        body.user-logged-in .site-title,
        body.user-logged-in .site-logo,
        body.user-logged-in .navbar-button {
            display: none !important;
        }

        /* Décalage du contenu pour la sidebar */
        body.user-logged-in {
            margin-left: 260px; /* largeur de la sidebar */
        }
        /* RESPONSIVE */
        @media (max-width: 900px){
            body.user-logged-in {
                    margin-left: 0; /* largeur de la sidebar */
    }
        }


        /* Bouton profil */
        .custom-profile-button {
            position: absolute;
            top: 10px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            color: #ffffff;
            z-index: 9999;
        }

        .custom-profile-button img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .custom-profile-name:hover {
            color: #cccccc !important;
        }
    </style>
    <?php
});

/**
 * 6️⃣ Ajouter le bouton profil à droite, seul dans la navbar
 */
add_action('wp_body_open', function() {
    if(!defined('VISIBILITY') || VISIBILITY !== 'logged_in') return;

    $current_user = wp_get_current_user();
    $avatar = get_avatar_url($current_user->ID, ['size' => 32]);
    $display_name = $current_user->display_name;
    $profile_url = esc_url(get_edit_profile_url($current_user->ID));

    echo '<a href="' . $profile_url . '" class="custom-profile-button">';
    echo '<img src="' . esc_url($avatar) . '" alt="Avatar">';
    echo '<span class="custom-profile-name">' . esc_html($display_name) . '</span>';
    echo '</a>';
});
