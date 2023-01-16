  var modal = document.getElementById("myModal");
  var nbr;
// Ouverture du popup
function affish(jeton) {
  let myConfirm = document.getElementById("myConfirm");
  modal.style.display="flex";
  nbr = jeton;
}

function conf(){
//Crée une nouvelle requête XMLHTTP à envoyer au serveur
  const xhttp = new XMLHttpRequest();

  //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
  xhttp.onload = function() {
    modal.style.display ="none"
  }

  //Ouvre la requête au serveur avec pour informations le nombre de jetons à donner
  xhttp.open("GET", "shopAchat.ctrl.php?valeur=" + nbr);

  //Envoie la requête au serveur
  xhttp.send();
}

// Fermeture de la page 
function stop() {
  modal.style.display = "none";
}




