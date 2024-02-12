const crushButtons = document.querySelectorAll(".crush-button");

crushButtons.forEach(button => {
    button.addEventListener('click', (event) => {
        const userId = event.currentTarget.dataset.userId;
        const icon = button.querySelector('i');
        if (icon.classList.contains('matchaRose')){
            fetch('/delete-crush', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({userId})
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    icon.classList.remove('matchaRose');
                })
                .catch(error => {
                    console.error('Error updating crush status:', error);
                });
        } else {
            fetch('/new-crush', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({userId})
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    console.log('la réponse est :' . response);
                    return response.json();
                })
                .then(data => {
                    console.log(data)
                    icon.classList.add('matchaRose');
                })
                .catch(error => {
                    console.error('Error updating crush status:', error);
                });
        }
    });
});