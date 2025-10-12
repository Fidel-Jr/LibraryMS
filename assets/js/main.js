document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebarMenuItems = document.querySelectorAll('.sidebar-menu a');

    // Toggle sidebar on button click
    sidebarCollapse.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
            // Add overlay class to content
            if (sidebar.classList.contains('active')) {
                content.classList.add('overlay-active');
            } else {
                content.classList.remove('overlay-active');
            }
        } else {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
            // Change icon based on sidebar state
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-arrow-right');
            } else {
                icon.classList.remove('fa-arrow-right');
                icon.classList.add('fa-bars');
            }
        }
    });

    // Handle sidebar menu item clicks
    sidebarMenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            sidebarMenuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                content.classList.remove('overlay-active');
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            content.classList.remove('overlay-active');
            content.classList.remove('active');
            // Reset the toggle icon
            const icon = sidebarCollapse.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-arrow-right');
            } else {
                icon.classList.remove('fa-arrow-right');
                icon.classList.add('fa-bars');
            }
        } else {
            sidebar.classList.remove('collapsed');
            content.classList.remove('expanded');
            // Reset the toggle icon
            const icon = sidebarCollapse.querySelector('i');
            icon.classList.remove('fa-arrow-right');
            icon.classList.add('fa-bars');
        }
    });

    // Close sidebar when clicking on overlay (mobile only)
    content.addEventListener('click', function(e) {
        if (
            window.innerWidth <= 768 &&
            sidebar.classList.contains('active') &&
            e.target === content // Only close if clicking on the overlay, not on content
        ) {
            sidebar.classList.remove('active');
            content.classList.remove('overlay-active');
        }
    });
        
}); 