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
                    '#fbbf24', '#60a5fa', '#a855f7', '#4ade80', '#f43f5e'
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
