    document.getElementById('show-more').addEventListener('click', function() {
    const button = this;
    const page = button.getAttribute('data-page');

    fetch(`/load-more-tricks?page=${page}`)
    .then(response => response.json())
    .then(data => {
    const tricksList = document.getElementById('tricks-list');
    tricksList.insertAdjacentHTML('beforeend', data.tricks);

    // Update the button with the next page
    button.setAttribute('data-page', data.nextPage);

    // Hide button if no more tricks
    if (!data.tricks || data.tricks.length === 0) {
        button.style.display = 'none';
    }

})
    .catch(error => console.error('Error loading more tricks:', error));
});
