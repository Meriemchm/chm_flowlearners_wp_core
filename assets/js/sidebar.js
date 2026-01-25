document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector(".flow-sidebar");
    const btn = document.querySelector(".flow-toggle");

    if (!sidebar || !btn) return;

    btn.addEventListener("click", () => {
        if (window.innerWidth <= 900) {
            sidebar.classList.toggle("active"); // mobile
        } else {
            sidebar.classList.toggle("collapsed"); // desktop
        }
    });
});

toggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    document.body.classList.toggle('sidebar-open');
});

