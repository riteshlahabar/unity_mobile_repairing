<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let technicianChart, deviceChart, problemChart, conditionChart, waterPhysicalDamageChart, customerFlowChart, revenueChart;

// Fetch real data then render charts
function loadCharts(period = 'day') {
    const from = document.getElementById('filter_from').value;
    const to = document.getElementById('filter_to').value;
    fetch(`/reports/data?from=${from}&to=${to}&period=${period}`)
    .then(r => r.json())
    .then(data => {
        technicianChart && technicianChart.destroy();
        deviceChart && deviceChart.destroy();
        problemChart && problemChart.destroy();
        conditionChart && conditionChart.destroy();
        waterPhysicalDamageChart && waterPhysicalDamageChart.destroy();
        customerFlowChart && customerFlowChart.destroy();
        revenueChart && revenueChart.destroy();

        technicianChart = createBarChart(
            document.getElementById('technicianChart').getContext('2d'),
            Object.keys(data.technician), Object.values(data.technician), '#556ee6', 'Jobs Completed'
        );
        deviceChart = createBarChart(
            document.getElementById('deviceChart').getContext('2d'),
            Object.keys(data.devices), Object.values(data.devices), '#34c38f', 'Devices'
        );
        problemChart = createDoughnutChart(
            document.getElementById('problemChart').getContext('2d'),
            Object.keys(data.problems), Object.values(data.problems),
            ['#f46a6a', '#f8b425', '#50a5f1', '#47bd9a', '#f06548']
        );
        conditionChart = createDoughnutChart(
            document.getElementById('conditionChart').getContext('2d'),
            Object.keys(data.conditions), Object.values(data.conditions),
            ['#556ee6', '#34c38f', '#f46a6a']
        );
        waterPhysicalDamageChart = createDamageComparisonChart(
            document.getElementById('waterPhysicalDamageChart').getContext('2d'),
            data.damageLabels, data.waterDamage, data.physicalDamage
        );
        customerFlowChart = createLineChart(
            document.getElementById('customerFlowChart').getContext('2d'),
            data.customerFlowLabels, data.customerFlowData, '#556ee6'
        );
        revenueChart = createLineChart(
            document.getElementById('revenueChart').getContext('2d'),
            data.revenueLabels, data.revenueData, '#34c38f'
        );
    });
}

// Helpers: unchanged! (copy from your posted code)
function createBarChart(ctx, labels, data, color, label) {
    return new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets: [{ label, backgroundColor: color, data, maxBarThickness: 22, borderRadius: 5 }] },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false }},
            scales: { 
                y: { beginAtZero: true, ticks: { font: { size: 11 } }, grid: { color: '#eef2f5' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });
}
function createDoughnutChart(ctx, labels, data, colors) {
    return new Chart(ctx, {
        type: 'doughnut',
        data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 0 }] },
        options: {
            responsive: true,
            cutout: '75%',
            plugins: { legend: { position: 'bottom', labels: { font: { size: 12 } } }, tooltip: { mode: 'index' } }
        }
    });
}
function createLineChart(ctx, labels, data, color) {
    return new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: [{
            data, borderColor: color, backgroundColor: color.replace(')', ',0.11)'),
            fill: true, tension: 0.41, pointRadius: 2.7, pointHoverRadius: 4.5, pointBackgroundColor: color
        }]},
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, ticks: { font: { size: 11 } }, grid: { color: '#eef2f5' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });
}
function createDamageComparisonChart(ctx, labels, waterData, physicalData) {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Water Damage', backgroundColor: '#50a5f1', data: waterData, maxBarThickness: 20, borderRadius: 4 },
                { label: 'Physical Damage', backgroundColor: '#f06548', data: physicalData, maxBarThickness: 20, borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { 
                y: { beginAtZero: true, ticks: { font: { size: 11 } }, grid: { color: '#eef2f5' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            },
            barPercentage: 0.54,
            categoryPercentage: 0.60
        }
    });
}

// Auto-load on page
let period = 'day';
window.onload = function() { loadCharts(period); };
document.getElementById('filter_period').addEventListener('change', function() {
    period = this.value;
    loadCharts(period);
});
document.getElementById('applyFiltersBtn').addEventListener('click', function() {
    loadCharts(period);
});
</script>
