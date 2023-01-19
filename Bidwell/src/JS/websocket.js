const conn = new WebSocket(`ws://${location.hostname}:8080`);

const login = document.getElementById('login').value;

conn.onopen = function () {
    console.log('Connected to server');
}

conn.onerror = function () {
    console.log('Error: Could not connect to server');
}

conn.onclose = function () {
    console.log('Connection closed');
}

conn.onmessage = function (e) {
    // on décode le message envoyé par le seveur
    const message = JSON.parse(e.data);

    switch (message.type) {
        // si le message est de type isConnected, on envoyé un booleen indiquant si l'utilisateur est connecté
        case 'isConnected':
            conn.send(JSON.stringify({type: "isConnected", value: login !== ''}));
            break;
        // si le message est de type login,
        // on renvoie au serveur un message avec le code qu'il a envoyé et en y ajoutant le login de l'utilisateur
        case 'login':
            conn.send(JSON.stringify({type: "login", code: message.code, value: login}));
            break;
        // si le message est de type enchere,
        // on envoie une requête ajax pour recharger le contenu de la page
        case 'enchere':
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById("container").innerHTML = this.responseText;
            }
            xhttp.open("GET", `../Ajax/consultation.ajax.php?id=${message.value}`);
            xhttp.send();
            break;
        // si le message est de type demandeJetons,
        // on ouvre une fenêtre modale pour demander à l'utilisateur de payer en jetons
        case 'demandeJetons':
            open('demandeJetons');
            break;
        case 'pasAssezJetons':
            open('pasAssezJetons');
            break;
    }
}

function encherir() {
    // si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
    if (login === '') {
        self.location = 'connect.ctrl.php';
    } else {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });

        conn.send(JSON.stringify({type: "enchere", value: params.id}));
    }
}

function encherirPourJetons() {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });

    conn.send(JSON.stringify({type: "encherePourJetons", value: params.id}))

    stop('demandeJetons');
}
