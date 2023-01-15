
window.onload= addCategoriesToSelectList;

function addCategoriesToSelectList(){
  const optionnanchor =  document.getElementById("optionanchor"),
  select = document.getElementById("categorieSelect");
  //Recup les catégorie filles via requete ajax
  var requete = new XMLHttpRequest();
  requete.open("POST", "creationPart2.ctrl.php", true);
  requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  requete.onload = function() {
    const rep = JSON.parse(this.responseText);
    for (let i = 0; i < rep.arrayCategories.length ; i++) {
      //Création option
      option = document.createElement("option");
      option.textContent= rep.arrayCategories[i]["libelle"];
      option.value = rep.arrayCategories[i]["libelle"];
      //Insertion sur la page
      select.insertBefore(option, optionnanchor);
    } 
    optionnanchor.remove();
}
  requete.send();
}

//Supprime toutes les options des suggestions de localisation, utile pour les update
function removeOptions(){
  i =0
  while (document.getElementById("o"+i)!=null) {
    document.getElementById("o"+i).remove();
    i++;
  }
}

//----------------------------------------------------------//
//Filtre les suggestions de localisation en fonction du texte entré dans l'input
function filter() {
  var input = document.getElementById("localisationInput");
  //on attend que l'utilisateur tape au moins 2 caractères afin d'éviter de recuperer trop de données
  if(input.value.length>=2){
    const optionanchorlocalisation =  document.getElementById("optionanchorlocalisation"),
    datalist = document.getElementById("localisationDatalist");
  //Création des buttons contenant code postal+ ville en fonction de l'input utilisateur
      //1- Récupérer les infos via l'api
    let requete = new XMLHttpRequest();
      requete.open("POST", " https://vicopo.selfbuild.fr/search/"+input.value, true);
      requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      requete.onload = function() {
        removeOptions();
        const rep = JSON.parse(this.responseText);
        //2-Créer les option
        for (let i = 0; i < rep.cities.length ; i++) {
           //Création option
          option = document.createElement("option");
          option.textContent= rep.cities[i].city + " ("+rep.cities[i].code+")";
          option.value = option.textContent;
          option.id ="o"+i;
          //Insertion sur la page
          datalist.insertBefore(option, optionanchorlocalisation);
        } 
       
      }
      requete.send();
  }
 
  
}
//----------------------------------------------------------//
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
    return true;
  }
  errorcb.innerHTML = "Veuillez cocher au moins une des deux cases";
  return false;
}
function validateLocalisation(){
  const errorlocalisation = document.getElementById("errorlocalisation");
  let input = document.getElementById("localisationInput").value;
  let numbers = input.replace(/\D/g,"");//regex pour garder que le nombre
  if(numbers.length!=5){
    errorlocalisation.innerHTML = "Veuillez entrez un code postal valide";
    return false;
  }
  errorlocalisation.innerHTML="";
  return true;
}


//----------------------------------------------------------//
//Affiche le fichier uploadé
var compteur = 1;
var tmp_files = new Map();
const errorImgs= document.getElementById("errorimgs");
function loadFile(event) {

  //Si ya deja 8 images on empeche l'utilisateur de load un fichier
  if (compteur <= 8){
    //Pour chaque fichier contenu dans l'input, on l'ajoute a tmp files si il y est pas déjà
    const imgInput=  document.getElementById("imagesInput");
    for (let i = 0; i < imgInput.files.length ; i++) {
      if(!tmp_files.has(imgInput.files[i]["name"])){
        extension = imgInput.files[i]["name"].substr(imgInput.files[i]["name"].lastIndexOf('.')+1).toLowerCase();
        //check la taille du fichier pour pas faire crash le serveur on limite a 8méga
        if(imgInput.files[i]["size"]>800000){
          errorImgs.innerHTML = "La taille du fichier de ne doit pas excéder 8 Mo";
        }else if(extension!='jpg' && extension!='png' && extension!="webp" && extension!="jpeg"){
            errorImgs.innerHTML = "Les images doivent être au format png, jpg, jpeg ou webp";
        }else{
          errorImgs.innerHTML = "";
          console.log("nouveau fichier");/*
          Object.defineProperty(imgInput.files[i], 'name', {
            writable: true,
            value: imgInput.files[i]+Date.now()
          });*/
          tmp_files.set(imgInput.files[i]["name"],imgInput.files[i]);
          console.log(tmp_files);
  
          //Affichage à l'utilisateur dans un tag img(output)
          let output = document.getElementById('output' + compteur);
          document.getElementById("p"+compteur).style = "display:none;";
          compteur++;
          output.src = URL.createObjectURL(event.target.files[i]);
          output.onload = function() {URL.revokeObjectURL(output.src)}
        }
       
      }else{
        console.log("fichier déjà dans tmp files");
        console.log(tmp_files);
      }

    }
  }
}

function validateInfos(event){
  event.preventDefault();
  let prix = validatePrices();
  let informationsEnvoieCheckBoxes = validateCheckBoxes();
  let localisation = validateLocalisation();
  var images = false;//Retourne array avec les urls des images et booleen a la fin qui indique la reussite ou non
  const errorImgs= document.getElementById("errorimgs"),
  select = document.getElementById("categorieSelect");//Ancre pour remonter dans la page en cas d'erreur

  // Create form data
  var files = new FormData();
  for (let i = 0; i < tmp_files.size ; i++) {
    let file = tmp_files.get(Array.from(tmp_files.keys())[i]);
    files.append('file'+i,file, (Date.now()*(Math.floor(Math.random() * 7)+1))+file["name"]);
  }

  //Vérifie que les images upload sont correctes
  let requete = new XMLHttpRequest();
      requete.open("POST", "upload.ctrl.php", false);
      requete.onload = function() {
      const rep = JSON.parse(this.responseText);   
      if(rep.success){
        //Recup tableau urls image 
        urls = rep.imgsurls;
        images = true;
      }else{
        errorImgs.innerHTML = rep.errormsg;
        select.scrollIntoView();
        images = false;
      }
      }
      requete.send(files);
      
  let ok =images && prix &&  informationsEnvoieCheckBoxes && localisation;
  if(ok){
    //les infos remplies sont valides : Création de l'enchère
    //première étape : récupérer toutes les données de formes et les envoyer a un controler php qui s'occupera de créer concretement l'enchere en base
    //deuxième étape : renvoyer l'utilisateur sur la page de consultation de son enchère créé
    let nom = document.getElementById("nom").value,
    prixbase = document.getElementById("prixbase").value,
    prixretrait = document.getElementById("prixretrait").value;
    categorie = document.getElementById("categorieSelect").value;    
    description = document.getElementById("description").value;    
    //Envoie php
    let requete = new XMLHttpRequest();
    requete.open("POST", "creationPart3.ctrl.php", true);
    requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    requete.onload = function() {
        const rep = JSON.parse(this.responseText);
        if(rep.sucess){
           self.location = "main.ctrl.php";
        }
    }
    //Envoie la requête au serveur avec en paramètres les valeurs des inputs
    requete.send("nom="+nom+"&prixbase="+prixbase+"&prixretrait="+prixretrait+"&imgs="+urls+"&categorie="+categorie+"&description="+description);
    return true;
  }else{
    console.log("nope form pas valide");
    return false;
  }
}
