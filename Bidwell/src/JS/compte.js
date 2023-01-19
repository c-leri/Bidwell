window.onload = showItems;
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

function affichage(){
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

function suppressionenchere() {
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
    
