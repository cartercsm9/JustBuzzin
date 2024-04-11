$(document).ready(function() {
    let currentPage;
    if(document.getElementById('categoryPopup')){
        currentPage = 'site';
    }
    if (currentPage === 'site') {
        $.ajax({
            url: './ddl/siteStatsQuery.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Common charts for all stats pages
                createChart('userRegistrationChart', 'line', data.userRegistration.map(u => u.date), data.userRegistration.map(u => u.users_count), 'Users Count');
                createChart('activeUsersChart', 'bar', data.activeUsers.map(a => a.user), data.activeUsers.map(a => a.posts_count), 'Posts Count');
                createChart('hotThreadsChart', 'bar', data.hotThreads.map(t => t.post_title), data.hotThreads.map(t => t.comments_count), 'Comments Count');
                createChart('popularCategoriesChart', 'bar', data.popularCategories.map(c => c.category_name), data.popularCategories.map(c => c.posts_count), 'Posts Count');
                createChart('siteActivityChart', 'line', data.siteActivity.map(a => a.activity_date), data.siteActivity.map(a => a.posts), 'Posts and Comments');
             
            }
        });
    } else{
        console.log('creating admins charts');
        $.ajax({
            url: './ddl/userStatsQuery.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('test');
                    createChart('userRegistrationChart', 'line', data.userRegistration.map(u => u.date), data.userRegistration.map(u => u.cumulative_users_count), 'Cumulative Users Count');
                    createChart(
                        'engagementStatsChart',
                        'bar',
                        ['Average Posts per User', 'Average Comments per User'],
                        [
                            parseFloat(data.engagementStats[0].avg_posts_per_user),
                            parseFloat(data.engagementStats[0].avg_comments_per_user)
                        ],
                        'Average Engagement Metrics'
                );
                createChart(
                    'mostActiveTimesChart', 
                    'bar', 
                    data.mostActiveTimes.map(t => {
                        let hour = parseInt(t.hour_of_day);
                        let ampm = hour >= 12 ? 'PM' : 'AM';
                        hour = hour % 12;
                        hour = hour ? hour : 12; 
                        return `${hour} ${ampm}`;
                    }), // x-axis labels, converting 24-hour time to 12-hour format
                    data.mostActiveTimes.map(t => parseInt(t.activity_count)), // y-axis data, converting string to int
                    'Activity Count'
                );
                createChart('contentGrowthChart', 'line', data.contentGrowth.map(g => g.date), data.contentGrowth.map(g => g.posts_count), 'Posts Count'); 
            }
        });
    }
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
                borderColor: 'rgba(190, 136, 52, 1)',
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