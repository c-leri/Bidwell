const conn = new WebSocket('ws://localhost:8080');

const login = document.getElementById('login').value;

conn.onopen = function (e) {
    console.log('Connected to server');
}

conn.onerror = function (e) {
    console.log('Error: Could not connect to server');
}

conn.onclose = function (e) {
    console.log('Connection closed');
}

conn.onmessage = function (e) {
    // on décode le message envoyé par le seveur
    const message = JSON.parse(e.data);

    // si le message est de type isConnected, on envoyé un booleen indiquant si l'utilisateur est connecté
    if (message.type === 'isConnected') {
        conn.send(JSON.stringify({type: "isConnected", value: login != ''}));
    // si le message est de type login,
    // on renvoie au serveur un message avec le code qu'il a envoyé et en y ajoutant le login de l'utilisateur
    } else if (message.type === 'login') {
        conn.send(JSON.stringify({type: "login", code: message.code, value: login}));
    }
}

function encherir(e) {
    
}