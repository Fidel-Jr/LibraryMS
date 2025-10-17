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

    // DASHBOARD JS FUNCTIONS
    const ctx = document.getElementById('checkoutChart').getContext('2d');

    const checkoutChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [ 
                {
                    label: 'Borrowed',
                    data: [2800, 4500, 3200, 3400, 2000, 3300, 3100],
                    borderColor: '#1a73e8', // You can change the background color here
                    backgroundColor: 'rgba(24, 74, 192, 0.1)', // You can change the fill color here
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Returned',
                    data: [1500, 2600, 4300, 4100, 3700, 2900, 2400],
                    borderColor: '#ef4444', // You can change the background color here
                    backgroundColor: 'rgba(239, 68, 68, 0.1)', // You can change the fill color here
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2, // Optional: controls width/height ratio
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value + 'K',
                    },
                    grid: { color: '#f1f1f1' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { usePointStyle: true, boxWidth: 8 }
                },
                tooltip: { mode: 'index', intersect: false }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    

        
}); 