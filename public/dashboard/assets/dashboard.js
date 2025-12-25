// Dashboard JS for Ai-cms Observatory

// Load project status and create init chart
fetch('data/project-status.json')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('initChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending'],
                datasets: [{
                    data: [4, 0], // ritual, specs, structure, audit all true
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Initialization Checklist'
                    }
                }
            }
        });
    });

// Load health check and create health chart
fetch('data/health-check.json')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('healthChart').getContext('2d');
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Docs Sync', 'Dashboard Current', 'Backups', 'Tests'],
                datasets: [{
                    label: 'Health Status',
                    data: [data.docs_sync ? 1 : 0, data.dashboard_current ? 1 : 0, data.backups ? 1 : 0, data.tests === 'Not Applicable' ? 0.5 : 0],
                    fill: true,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    pointBackgroundColor: 'rgba(40, 167, 69, 1)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 1
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Project Health Metrics'
                    }
                }
            }
        });
    });

// Load environment status
fetch('data/environment-status.json')
    .then(response => response.json())
    .then(data => {
        document.getElementById('php-ver').textContent = data.php_version;
        document.getElementById('ci4-status').textContent = data.ci4_installed ? 'Yes' : 'No';
        document.getElementById('db-status').textContent = data.database_connected ? 'Yes' : 'No';
    });

// Load version info
fetch('data/version-info.json')
    .then(response => response.json())
    .then(data => {
        document.getElementById('version').textContent = data.version;
        document.getElementById('ruleset').textContent = data.ruleset;
    });