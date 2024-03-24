document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.upvote-button, .downvote-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const vote = this.classList.contains('upvote-button') ? 1 : -1;
            fetch('./ddl/vote.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}&vote=${vote}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const votesCountSpan = document.getElementById(`votes-count-${postId}`);
                    votesCountSpan.textContent = data.newTotalVotes;
                } else {
                    alert('There was an error processing your vote.');
                }
            });
        });
    });
});