
var modal = document.getElementById("myModal");

var span = document.getElementsByClassName("close")[0];

var myConfirm = document.getElementById("myConfirm");
var myCancel = document.getElementById("myCancel");

// Ouverture du popup
function affish() {
  modal.style.display = "flex";
  let valeur=this.innerHtml
  valeur.replace("€", "");
  valeur=parseInt(valeur);
  myConfirm.value = valeur;
}

// Fermeture de la page 
function stop() {
  modal.style.display = "none";
}

function conf(){

}



