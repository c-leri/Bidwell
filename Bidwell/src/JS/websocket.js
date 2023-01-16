const conn = new WebSocket('ws://localhost:8080');

conn.onopen = function (e) {
    console.log('Connected to server');
}

conn.onclose = function (e) {
    console.log('Error: Could not connect to server');
}

conn.onclose = function (e) {
    console.log('Connection closed');
}

conn.onmessage = function (e) {
    // on décode le message envoyé par le seveur
    const message = JSON.parse(e.data);

    // si le message est de type login,
    // on renvoie au serveur un message avec le code qu'il a envoyé et en y ajoutant le login de l'utilisateur
    if (message.type = 'login') {
        const login = document.getElementById('login').value;
        conn.send(JSON.stringify({type: "login", code: message.code, text: login}));
    } else {
        console.log('message', message);
    }
}

function encherir(e) {
    const notification = {

    };

    conn.send(JSON.stringify(notification));
}