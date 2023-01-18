const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

function twoDigits(num) {
  return `${num}`.length < 2 ? `0${num}` : num;
}

function getRatioTempsActuel(dateDebut, instantFin, instantDerniereEnchere) {
  let maintenant = new Date();
  if (maintenant > dateDebut) {
      let differenceMaintenantFin = instantFin - maintenant;
      return differenceMaintenantFin / (instantFin - instantDerniereEnchere);
  } else {
      return 1;
  }
}

window.setInterval(function () {
  let mini = parseFloat(document.getElementById('min').innerHTML);
  let maxi = parseFloat(document.getElementById('max').innerHTML);
  let dates = document.getElementById('temps').innerText.split(":");
  let instantDerniereEnchere = new Date(parseInt(document.getElementById('instantDerniereEnchere').value) * 1000);
  let instantFin = new Date(parseInt(document.getElementById('instantFin').value) * 1000);
  let dateDebut = new Date(parseInt(document.getElementById('dateDebut').value) * 1000);

  const valeur = mini + getRatioTempsActuel(dateDebut, instantFin, instantDerniereEnchere) * (maxi - mini);

  let maintenant = new Date();

  // Début de l'enchère, on ajoute le script de websocket et on recharge la page
  if (maintenant >= dateDebut && maintenant < instantFin && document.title !== 'Enchère en cours') {

    const websocketScript = document.createElement('script');
    websocketScript.id = 'websocketScript';
    websocketScript.setAttribute('src', '../JS/websocket.js');
    document.body.appendChild(websocketScript);
    document.title = 'Enchère en cours';

    // On envoie une requête ajax pour mettre à jour le contenu de la page
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      document.getElementById("container").innerHTML = this.responseText;
    }
    xhttp.open("GET", `../Ajax/consultation.ajax.php?id=${params.id}`);
    xhttp.send();

  // Fin de l'enchère on retire le script de websocket et on recharge la page
  } else if (maintenant >= instantFin && document.title !== 'Enchère Terminée') {

    let websocketScript = document.getElementById('websocketScript');
    if (websocketScript !== null)
      document.body.removeChild(websocketScript);
    document.title = 'Enchère Terminée';

    // On envoie une requête ajax pour mettre à jour le contenu de la page
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      document.getElementById("container").innerHTML = this.responseText;
    }
    xhttp.open("GET", `../Ajax/consultation.ajax.php?id=${params.id}`);
    xhttp.send();

  } else {

    let date = new Date(0, 0, 0, parseInt(dates[0]), parseInt(dates[1]), parseInt(dates[2]) - 1);

    document.getElementById('temps').innerHTML = `${twoDigits(date.getHours())}:${twoDigits(date.getMinutes())}:${twoDigits(date.getSeconds())}`;

    if (mini < valeur) {
      document.getElementById('act').innerHTML = `${valeur.toFixed(2)}€`;

      affichage = (74 - ((valeur-mini)/(maxi-mini)) * 74).toFixed(2);

      document.getElementById('circle-container__progress').style.setProperty('stroke-dashoffset', affichage);
    }

  }

}, 1000);
