<?php
if (!defined('ABSPATH')) exit;
$user = wp_get_current_user();
$current_id = get_queried_object_id();

?>

<!-- TOGGLE MOBILE -->
<button class="flow-toggle">
    <i class="fa-solid fa-bars"></i>
</button>

<!-- SIDEBAR -->
<div class="flow-sidebar">

    <div class="sidebar-header">
        <img src="<?php echo FL_URL; ?>assets/img/logo.png" alt="Flowlearners" class="sidebar-logo">
        <span class="logo-text">Flowlearners</span>
    </div>


    <ul class="menu">

        <li>             
            <a href="<?= get_permalink(get_page_by_path('dashboard')); ?>"
            class="<?= get_queried_object_id() === get_page_by_path('dashboard')->ID ? 'active' : ''; ?>">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

<?php
if (in_array('student', $user->roles)) {

    $page_to_show = null;

    $groups_user = new Groups_User($user->ID);
    $group_ids = $groups_user->group_ids_deep;

    foreach ($group_ids as $group_id) {

        $group = Groups_Group::read($group_id);

        if (!$group) {
            continue;
        }

        // On ignore le groupe "Registered"
        if (strtolower($group->name) === 'registered') {
            continue;
        }

        // Accepte : A1, B2, C1, D1, Explorer1, Communicator2, etc.
        if (!preg_match('/^[A-Za-z]+[0-9]+$/', $group->name)) {
            continue;
        }


        $slug = 'page-classe-' . strtolower($group->name);
        $page_to_show = get_page_by_path($slug);

        if ($page_to_show) {
            break; // on prend le premier groupe correspondant
        }
    }

    if ($page_to_show) {
        ?>
        <li>
            <a href="<?= esc_url(get_permalink($page_to_show->ID)); ?>"
               class="<?= ($current_id === $page_to_show->ID) ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i>
                <span>Classroom</span>
            </a>
        </li>
        <?php
    }
}






        if (array_intersect(['tutor', 'administrator'], $user->roles)) {
            ?>
            <li>

               <a href="<?= get_permalink(get_page_by_path('manage-groups')); ?>"
   class="<?= get_queried_object_id() === get_page_by_path('manage-groups')->ID ? 'active' : ''; ?>">

                    <i class="fa-solid fa-layer-group"></i>
                    <span>Manage Groups</span>
                </a>

            </li>
            <?php
        }

        $account_slugs = ['account','profile','user','my-account','membership-account'];
        foreach ($account_slugs as $slug) {
            $p = get_page_by_path($slug);
            if ($p) {
                ?>
                <li>
                    <a href="<?= get_permalink($p); ?>"
                    class="<?= ($current_id === $p->ID) ? 'active' : ''; ?>">
                        <i class="fa-solid fa-user-gear"></i>
                        <span>Account Settings</span>
                    </a>
                </li>
                <?php
                break;
            }
        }
        ?>
    </ul>

    <div class="sidebar-footer">
        <a href="<?= wp_logout_url(home_url()); ?>">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.flow-toggle');
    const sidebar = document.querySelector('.flow-sidebar');

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
});
</script>
