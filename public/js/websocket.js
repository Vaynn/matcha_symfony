    const socket = new WebSocket('ws://localhost:8080');
    const crushButtons = document.querySelectorAll(".crush-button");
    const userId = document.currentScript.getAttribute('data-user-id');

    socket.addEventListener('open', (event) => {
        console.log('WebSocket connection opened');
        if (userId !== null){
            const message = {
                type: 'setUserId',
                userId: userId,
            };
            socket.send(JSON.stringify(message));
        } else {
            console.error('User ID not found')
        }
    });

    socket.addEventListener('error', (event) => {
        console.error('WebSocket error:', event);
    });

    socket.addEventListener('close', (event) => {
        console.log('WebSocket closed:', event);
    });

    socket.addEventListener('message', (event) => {
        console.log('toto');
        console.log('Message received:', event.data);
        const data = JSON.parse(event.data);
        let crushIcon;
        if (data['type'] && data['type'] === 'updateCrushStatus') {
            if (data['status'] === 'like') {
                crushIcon = document.getElementById('crushMenu');
                crushIcon.classList.add('yellow-icon')
            }
        }
        if (data['type'] && data['type'] === 'addFavoriteRow'){
            const favoriteRow = document.getElementById('crushPageRow');
            const newProfile = document.createElement('div');
            newProfile.classList.add('col-md-4', 'crush-page-' + data['id']);
            newProfile.innerHTML = `
                               <div class="card border-primary mb-3" style="max-width: 20rem;">
                            <div class="card-header">${data['name']} - ${data['age']} yo</div>
                            <div class="card-body">
                                        <div class="card-body d-flex justify-content-center align-items-center">
                                            <img class="img-fluid" alt="${ data['imageName']}" style="height: 200px;" src="${data['imagePath']}">
                                        </div>            
                                <div class="btn-group-sm d-flex justify-content-center" role="group" aria-label="Basic example">
                                    <a href="/profil/${data['id']}">
                                        <button type="button" class="btn btn-outline-light btn-sm">Visit Profile</button>
                                    </a>
                                    <button type="button" class="btn btn-outline-light">
                                        <i class="fa-regular fa-comments"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-light crush-button" data-user-id="${data['id']}">
                                        <i class="fa-solid fa-bolt matchaRose"></i>
                                    </button>

                                </div>
                            </div>
                        </div>`;
            favoriteRow.appendChild(newProfile);
        }
    });

    crushButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const crushId = event.currentTarget.dataset.userId;
            const icon = button.querySelector('i');

            if (icon.classList.contains('matchaRose')) {
                const message = {
                    type: 'updateCrushStatus',
                    crushId: crushId,
                    userId: userId,
                    status: 'disliked',
                };
                icon.classList.remove('matchaRose');
                console.log(JSON.stringify(message));
                socket.send(JSON.stringify(message));
                const favorite = document.querySelector('.crush-page-' + crushId);
                if (favorite) {
                    favorite.remove();
                }
            } else {
                const message = {
                    type: 'updateCrushStatus',
                    crushId: crushId,
                    userId: userId,
                    status: 'liked',
                    addFavoriteRow: false,
                };
                if (button.classList.contains('interested')) {
                    console.log('interested');
                    if (document.querySelector('.no-favorites')) {
                        console.log('no-favorite');
                        document.querySelector('.no-favorites').remove();
                    }
                    const favoriteRow = document.getElementById('crushPageRow');
                    console.log(favoriteRow);
                    if (favoriteRow) {
                        console.log("new row");
                        message['addFavoriteRow'] = true;
                    }
                }
                icon.classList.add('matchaRose');
                socket.send(JSON.stringify(message));
            }
        })
    });
