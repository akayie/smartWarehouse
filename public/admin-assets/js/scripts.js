/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    //
// Scripts
//

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});
 // Main Switcher for Admin Panel (admin.html)
function switchMainPanel(panelId, btn) {
    // If the user selects the public view, redirect to user.html
    if (panelId === 'public-panel') {
        window.location.href = 'user.html';
        return;
    }

    // Otherwise, handle internal admin panel view switching dynamically
    document.querySelectorAll('.panel-view').forEach(panel => {
        panel.classList.remove('active');
    });

    document.querySelectorAll('.switcher-btns button').forEach(b => {
        b.classList.remove('active');
    });

    const targetPanel = document.getElementById(panelId);
    if (targetPanel) {
        targetPanel.classList.add('active');
    }

    if (btn) {
        btn.classList.add('active');
    }
}
        // Sidebar Navigation Switcher for Admin Sub-pages
        function switchAdminPage(pageId) {
            // Hide all sub-pages
            document.querySelectorAll('.admin-main .sub-page').forEach(page => {
                page.classList.remove('active');
            });

            // Deactivate all sidebar nav links
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.classList.remove('active');
            });

            // Activate target page and link
            const targetPage = document.getElementById(pageId);
            if (targetPage) {
                targetPage.classList.add('active');
            }

            const targetNav = document.getElementById('nav-' + pageId);
            if (targetNav) {
                targetNav.classList.add('active');

                // Update top header title
                const pageTitleText = targetNav.querySelector('span') ? targetNav.querySelector('span').innerText : 'Dashboard';
                document.getElementById('admin-page-title').innerText = pageTitleText;
            }
        }

        // Toast Notification System
        function showToast(message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `<i class="fa-solid fa-circle-info" style="color: #38bdf8;"></i> ${message}`;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(10px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
