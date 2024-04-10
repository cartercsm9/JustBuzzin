$(document).ready(function() {
    $.ajax({
        url: './ddl/siteStatsQuery.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            createChart('activeUsersChart', 'bar', data.activeUsers.map(a => a.user), data.activeUsers.map(a => a.posts_count), 'Posts Count');
            createChart('hotThreadsChart', 'bar', data.hotThreads.map(t => t.post_title), data.hotThreads.map(t => t.comments_count), 'Comments Count');
            createChart('userRegistrationChart', 'line', data.userRegistration.map(u => u.date), data.userRegistration.map(u => u.users_count), 'Users Count');
            createChart('popularCategoriesChart', 'bar', data.popularCategories.map(c => c.category_name), data.popularCategories.map(c => c.posts_count), 'Posts Count');
            createChart('siteActivityChart', 'line', data.siteActivity.map(a => a.activity_date), data.siteActivity.map(a => a.posts), 'Posts and Comments');
        }
    });
});

function createChart(chartId, type, labels, data, label) {
    var ctx = document.getElementById(chartId).getContext('2d');
    var chart = new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: 'rgba(237, 170, 65, 0.5)',
                borderColor: 'rgba(257, 250, 150, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}