window.onload = showItems();
window.onload = showItemsWon();
const modal = document.getElementById("myModal");
const modeul = document.getElementById("myModeul");
var id;

function showItems() {
    //Crée une nouvelle requête XMLHTTP à envoyer au serveur
    const xhttp = new XMLHttpRequest();

    //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
    xhttp.onload = function() {
        document.getElementById("vosEncheres").innerHTML = this.responseText;
    }

    let createur = document.getElementById("login").innerText;
    //Ouvre la requête au serveur avec pour informations le tri, le type, les catégories sélectionnées et le numéro de page
    xhttp.open("GET", "../Ajax/compte.ajax.php?createur=" + createur);

    //Envoie la requête au serveur
    xhttp.send();
}  

function affichageConfirmation(){
  modal.style.display="flex";
}

// Fermeture de la page 
function stop() {
    modal.style.display = "none";
    modeul.style.display="none";
  }
   
function supprenchere($id){
  modeul.style.display="flex";
  id=$id;
}

function suppressionEnchere() {
  //Crée une nouvelle requête XMLHTTP à envoyer au serveur
  const xhttp = new XMLHttpRequest();

  //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
  xhttp.onload = function() {
      document.getElementById("vosEncheres").innerHTML = "";
      document.getElementById("vosEncheres").innerHTML = this.responseText;
      modeul.style.display="none";
  }

  let createur = document.getElementById("login").innerText;
  //Ouvre la requête au serveur avec pour informations le tri, le type, les catégories sélectionnées et le numéro de page
  xhttp.open("GET", "../Ajax/compte.ajax.php?createur=" + createur+"&suppr="+id);

  //Envoie la requête au serveur
  xhttp.send();
}

function suppressionCompte($login) {
  //Crée une nouvelle requête XMLHTTP à envoyer au serveur
  const xhttp = new XMLHttpRequest();

  //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
  xhttp.onload = function() {
    const delay = ms => new Promise(res => setTimeout(res, ms));
    if (this.responseText != "OK"){
      document.getElementById("erreur").innerHTML = "Erreur: Vous ne pouvez pas supprimer votre compte tant qu'une de vos enchère est en cours.";
      modal.style.display="none";
    } else {
      window.location.replace("main.ctrl.php");
    }
  }

  let createur = document.getElementById("login").innerText;
  //Ouvre la requête au serveur avec pour informations le tri, le type, les catégories sélectionnées et le numéro de page

  xhttp.open("GET", "../Ajax/compte-delete.ajax.php?login=" + $login);

  //Envoie la requête au serveur
  xhttp.send();
}


function showItemsWon() {

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("won").innerHTML = this.responseText;
    
  }

  let login = document.getElementById("login").innerText;
  if (login != "") {

    xhttp.open("GET", "../Ajax/main.ajax.php?login=" + login);
    xhttp.send();
  }
  else {
    document.getElementById('hrWon').style = "display:none;";
    document.getElementById('titleWon').style = "display:none;";
    }

  }
