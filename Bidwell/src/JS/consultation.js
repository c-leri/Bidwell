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

  // L'enchère n'est pas terminée, le compteur tourne
  if (maintenant < instantFin)  {
    let date = new Date(0, 0, 0, parseInt(dates[0]), parseInt(dates[1]), parseInt(dates[2]) - 1);

    document.getElementById('temps').innerHTML = `${twoDigits(date.getHours())}:${twoDigits(date.getMinutes())}:${twoDigits(date.getSeconds())}`;

    // L'enchère est en cours
    if (maintenant >= dateDebut && maintenant < instantFin) {
      // Début de l'enchère, on ajoute le script de websocket et on met à jour le contenu de la page
      if (document.title !== 'Enchère en cours') {
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
      }

      document.getElementById('act').innerHTML = `${valeur.toFixed(2)}€`;

      affichage = (74 - ((valeur-mini)/(maxi-mini)) * 74).toFixed(2);

      document.getElementById('circle-container__progress').style.setProperty('stroke-dashoffset', affichage);
    }
  // Fin de l'enchère, on retire le script de websocket et met à jour le contenu de la page
  } else if (document.title !== 'Enchère Terminée') {
    let websocketScript = document.getElementById('websocketScript');
    if (websocketScript !== null)
      document.body.removeChild(websocketScript);
    document.title = 'Enchère Terminée';

    // On envoie une requête ajax pour mettre à jour le contenu de la page

    //Création de la requête
    const xhttp = new XMLHttpRequest();

    //Indication de la fonction à réaliser une fois la requête effectuée
    //Ici, la réponse remplacera le HTML de l'élément "container"
    xhttp.onload = function() {
      document.getElementById("container").innerHTML = this.responseText;
    }
    //Ouverture de la requête, et inclusion des informations à envoyer
    //Ici, c'est une requête sous GET qui envoie au fichier "consultation.ajax.php" la variable "params.id" définie précédemment
    xhttp.open("GET", `../Ajax/consultation.ajax.php?id=${params.id}`);

    //Envoie de la requête.
    xhttp.send();

    //Note: La ligne 77 s'exécute après la ligne 84
  }
}, 1000);

// ouvre la fenêtre modale
function open(nomModal) {
  document.getElementById(nomModal).style.display = "block";
}

function enchereImpossible(message) {
  document.getElementById('messageModal').innerText = message;
  open('enchereImpossible');
}

// Ferme la fenêtre modale
function stop(nomModal) {
  document.getElementById(nomModal).style.display = "none";
}