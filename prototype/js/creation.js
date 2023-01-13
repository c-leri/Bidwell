

//Affiche ou cache les options du dropdown des catégorie ou localisation
function showFunction(nomDropDown) {
  document.getElementById(nomDropDown+"Dropdown").classList.toggle("show");
}

//Met à jour le dropdown des localisations en contactant une api
function updateLocalisationDropdown(input){
  const localisationanchor =  document.getElementById("ajoutbuttonanchorlocalisation"),
  localisationDropDown = document.getElementById("localisationDropdown");
//Création des buttons contenant code postal+ ville en fonction de l'input utilisateur
    //1- Récupérer les infos via l'api
    var requete = new XMLHttpRequest();
    //Précise quel controleur php le client va contacter via ajax ainsi que la méthode utilisée
    requete.open("POST", " https://vicopo.selfbuild.fr/search/"+input.value, true); //True pour que l'exécution du script continue pendant le chargement, false pour attendre.
    //Header utile au bon fonctionnement de la requête
    requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    requete.onload = function() {
      const rep = JSON.parse(this.responseText);
      console.log(rep);
      console.log("ok");
      //2-Créer les boutons
      for (let i = 0; i<8 && i < rep.cities.length ; i++) {
        //Création bouton
        button = document.createElement("button");
        button.textContent= rep.cities[i].city + " ("+rep.cities[i].code+")";
        button.addEventListener('click',function(e){
          confirmFunction(e,'localisation');
        });
        button.id = "bl"+i;
        //Insertion sur la page
        localisationDropDown.insertBefore(button, localisationanchor);
      } 
     
    }
    requete.send();
}
function updateCategorieDropdown(input){
  const categorienanchor =  document.getElementById("ajoutbuttonanchorcategorie"),
  categorieDropDown = document.getElementById("categorieDropdown");
  //Recup les catégorie via requete ajax
  console.log("AAAAAA");
  var requete = new XMLHttpRequest();
//Précise quel controleur php le client va contacter via ajax ainsi que la méthode utilisée
requete.open("POST", "../controler/creationPart2.ctrl.php", true);//Header utile au bon fonctionnement de la requête
requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
requete.onload = function() {
  const rep = JSON.parse(this.responseText);
  console.log(rep);
}
requete.send();
}
//Supprime les boutons du dropdown
function removebuttons(name){
  i =0
    while (document.getElementById("b"+i)!=null) {
      document.getElementById("b"+name.substring(0,1)+i).remove();
      i++;
    }
}
//----------------------------------------------------------//
//Filtre les catégories du dropdown des catégories et localisation en fonction du texte entré dans l'input
function filterFunction(name) {
  removebuttons(name);
  var input, filter, buttons, i;
  input = document.getElementById(name+"Input");
  if(name=="localisation" && input.value.length>=2){
    //Gère l'input localisation uniquement pour contacter l'api etc
    updateLocalisationDropdown(input);
  }else if(name=="categorie"){
    console.log("kateguri");
    updateCategorieDropdown(input);
  }
  //Pour filtrer, met tout en majuscule pour que ce soit plus simple
  filter = input.value.toUpperCase();
  div = document.getElementById(name+"Dropdown");
  buttons = div.getElementsByTagName("button");

  //Pour chaque bouton concernés (dans la division nameDropdown), vérifie si son texte correspond au texte de l'input
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
function confirmFunction(event,name) {
  event.preventDefault();
    var source, output;
    //Cherche quel bouton est à l'origine de l'événement qui a lancé la fonction
    source = event.target || event.srcElement;
    output = document.getElementById(name+"Input");
    output.value = source.textContent || source.innerText;
    //Ferme la liste des catégories
    showFunction(name);
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


//--------------------------PARTIE GESTION ERREUR UTILISATEUR--------------------------------//





function validatePrices(){
  const prixretrait = document.getElementById("prixretrait"),
errorretrait = document.getElementById("errorretrait"),
prixbase = document.getElementById("prixbase");
//Si 90% du prix de base > prix retrait
    if(parseFloat(prixbase.value)*0.9 >= parseFloat(prixretrait.value)) {
      errorretrait.innerHTML = "";
      return true;
  } else {
    errorretrait.innerHTML = "Veuillez insérer un prix de retrait inférieur à 90% du prix de base (soit un prix inférieur ou égal à "+parseFloat(prixbase.value)*0.9+")";
    prixbase.scrollIntoView();
    return false;
  }
}

function validateCheckBoxes(){
  const cbdirect = document.getElementById("cbdirect"),
  cbcolis = document.getElementById("cbcolis"),
  errorcb = document.getElementById("errorcb");
  //Si au moins une case est cochée c'est ok, sinon on le signale à l'utilisateur
  if(cbdirect.checked || cbcolis.checked){
    errorcb.innerHTML = "";
  }else{
    errorcb.innerHTML = "Veuillez cocher au moins une des deux cases";
  }

}

function validateInfos(event){
  event.preventDefault();
  let prix = validatePrices();
  let informationsEnvoieCheckBoxes = validateCheckBoxes();
  return prix && informationsEnvoieCheckBoxes;
}
