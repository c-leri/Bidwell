//Affiche ou cache les options du dropdown des catégorie
function showFunction() {
  document.getElementById("categorieDropdown").classList.toggle("show");
}


//----------------------------------------------------------//


//Filtre les catégories du dropdown des catégories en fonction du texte entré dans l'input
function filterFunction() {
  var input, filter, button, i;
  input = document.getElementById("categorieInput");

  //Pour filtrer, met tout en majuscule pour que ce soit plus simple
  filter = input.value.toUpperCase();
  div = document.getElementById("categorieDropdown");
  buttons = div.getElementsByTagName("button");

  //Pour chaque bouton concernés (dans la division catégorieDropdown), vérifie si son texte correspond au texte de l'input
  //Si oui, lelaisse affiché, sinon le cache
  for (i = 0; i < buttons.length; i++) {
    txtValue = buttons[i].textContent || buttons[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      buttons[i].style.display = "";
    } else {
      buttons[i].style.display = "none";
    }
  }
}


//----------------------------------------------------------//


//Renvoie le texte du bouton sélectionné dans l'input des catégories
function confirmFunction() {
    var source, output;

    //Cherche quel bouton est à l'origine de l'événement qui a lancé la fonction
    source = event.target || event.srcElement;

    output = document.getElementById("categorieInput");
    output.value = source.textContent || source.innerText;


    //Ferme la liste des catégories
    showFunction();
}


//----------------------------------------------------------//

var compteur = 1;
var loadFile = function(event) {
  if (compteur < 9){

var output = document.getElementById('output' + compteur);
document.getElementById("p"+compteur).style = "display:none;";
compteur++;

output.src = URL.createObjectURL(event.target.files[0]);
output.onload = function() {
    URL.revokeObjectURL(output.src) // free memory
       }
     }
  };


//----------------------------------------------------------//