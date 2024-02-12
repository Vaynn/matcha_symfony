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


        // Ajoutez ici le code pour mettre à jour l'interface utilisateur en fonction des données reçues
        // par exemple, modifier la classe de l'icône en fonction de 'status'
    });

    crushButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const crushId = event.currentTarget.dataset.userId;
            const icon = button.querySelector('i');

            if (icon.classList.contains('matchaRose')) {
                const message = {
                    type: 'updateCrushStatus',
                    crushId : crushId,
                    userId: userId,
                    status: 'disliked',
                };
                icon.classList.remove('matchaRose');
                console.log(JSON.stringify(message));
                socket.send(JSON.stringify(message));
                const favorite = document.querySelector('.crush-page-' + crushId);
                if (favorite){
                    favorite.remove();
                }
            } else {
                const message = {
                    type: 'updateCrushStatus',
                    crushId: crushId,
                    userId: userId,
                    status: 'liked',
                };
                icon.classList.add('matchaRose');
                socket.send(JSON.stringify(message));
            }
        });
    });
