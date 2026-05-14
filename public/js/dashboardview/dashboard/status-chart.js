/**
 * Dashboard Status Chart Initialization
 */
function initStatusChart(statusCounts) {
    const ctx = document.getElementById('statusChart').getContext('2d');
    const labels = Object.keys(statusCounts).map(s => s.toUpperCase());
    const data = Object.values(statusCounts);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    getComputedStyle(document.documentElement).getPropertyValue('--warning').trim() || '#fbbf24',
                    getComputedStyle(document.documentElement).getPropertyValue('--info').trim() || '#60a5fa',
                    getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37',
                    getComputedStyle(document.documentElement).getPropertyValue('--success').trim() || '#4ade80',
                    getComputedStyle(document.documentElement).getPropertyValue('--danger').trim() || '#f43f5e'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
}
