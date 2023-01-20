/*Récupération des éléments du DOM*/
const nom = document.getElementById("nom"),
optionnanchor =  document.getElementById("optionanchor"),
select = document.getElementById("categorieSelect"),
codePostal = document.getElementById("localisationInput"),
categorieSelect = document.getElementById("categorieSelect"),
optionanchorlocalisation =  document.getElementById("optionanchorlocalisation"),
datalist = document.getElementById("localisationDatalist"),
images=  document.getElementById("imagesInput"),
prixretrait = document.getElementById("prixretrait"),
prixbase = document.getElementById("prixbase"),
description = document.getElementById("description"),
errornom = document.getElementById("errornom"),
errorCategorie = document.getElementById("errorcategorie"),
errorCodePostal = document.getElementById("errorlocalisation"),
errorImgs= document.getElementById("errorimgs"),
errorretrait = document.getElementById("errorretrait"),
errordescription = document.getElementById("errordescription"),
errorprixbase = document.getElementById("errorprixbase");
window.onload= addCategoriesToSelectList;

function addCategoriesToSelectList(){
  //Recup les catégorie filles via requete ajax
  const requete = new XMLHttpRequest();
  requete.open("POST", "../Ajax/creation-categorie.ajax.php", true);
  requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  requete.onload = function() {
  const rep = JSON.parse(this.responseText);
    let option;
    for (let i = 0; i < rep.array.length; i++) {
      //Création option
      option = document.createElement("option");
      option.textContent = rep.array[i];
      option.value = rep.array[i];
      //Insertion sur la page
      select.insertBefore(option, optionnanchor);
    } 
    optionnanchor.remove();
}
  requete.send();
}
//Supprime toutes les options des suggestions de localisation, utile pour les update
function removeOptions(){
  let i = 0
  while (document.getElementById("o"+i)!=null) {
    document.getElementById("o"+i).remove();
    i++;
  }
}
//----------------------------------------------------------//
//Filtre les suggestions de localisation en fonction du texte entré dans l'input
function filter() {
  //on attend que l'utilisateur tape au moins 2 caractères afin d'éviter de recuperer trop de données
  if(codePostal.value.length>1){
  //Création des options contenant code postal+ ville en fonction de l'input utilisateur
      //1- Récupérer les infos via l'api
    let requete = new XMLHttpRequest();
      requete.open("POST", " https://vicopo.selfbuild.fr/search/"+codePostal.value, true);
      requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      requete.onload = function() {
        removeOptions();
        const rep = JSON.parse(this.responseText);
        //2-Créer les options
        let option;
        for (let i = 0; i < rep.cities.length; i++) {
          //Création option
          option = document.createElement("option");
          option.textContent = rep.cities[i].city + " (" + rep.cities[i].code + ")";
          option.value = option.textContent;
          option.id = "o" + i;
          //Insertion sur la page
          datalist.insertBefore(option, optionanchorlocalisation);
        } 
      }
      requete.send();
  }
  validateCodePostal();
}
//----------------------------------------------------------//
//--------------------------PARTIE GESTION ERREUR UTILISATEUR--------------------------------//
function validateNomAnnonce(){
  if(nom.value.length<4 || nom.value.length>60){
    errornom.innerHTML = "Veuillez entrer un nom d'annonce entre 4 et 60 caractères ("+nom.value.length+" actuellement)";
    document.getElementById("navbar-top").scrollIntoView();
    return false;
  }
  errornom.innerHTML = "";
  return true;
}

function validateCategories(){
  //Si la categorie selectionnée est une catégorie mère on l'indique à l'utilisateur
    if(categorieSelect.value.includes("------")) {
      errorCategorie.innerHTML = "Veuillez selectionner une sous catégorie";
      document.getElementById("navbar-top").scrollIntoView();
      return false;
    } else {
      errorCategorie.innerHTML = "";
        return true;
    }
}
function validateDescription(){
  if(description.value.length<30 || description.value.length>500){
    errordescription.innerHTML = "Veuillez entrer entre 50 caractères et 500 caractères ("+description.value.length+" actuellement)";
    return false;
  }else{
    errordescription.innerHTML = "";
    return true;
  }
}

function validatePrixRetrait(){
let ok = true;
  if(prixretrait.value.length==0 || parseFloat(prixretrait.value)<1 ||parseFloat(prixretrait.value)>99999){
    errorretrait.innerHTML = "Veuillez entrer un prix compris entre 1 et 999 999 €";
    ok=false;
  }else{
    errorretrait.innerHTML="";
  }
  
if(ok && parseFloat(prixbase.value)>1){
  if(parseFloat(prixbase.value)*0.9 >= parseFloat(prixretrait.value)) {
    errorretrait.innerHTML = "";
    return true;
  } else {
    errorretrait.innerHTML = "Veuillez entrer un prix inférieur à 90% du prix de base (soit un prix inférieur ou égal à "+ (parseFloat(prixbase.value)*0.9).toFixed(2)+")";
    prixbase.scrollIntoView();
    return false;
  }
}
return false;
}
function validatePrixBase(){
  let ok = true;
  if( prixbase.value.length==0|| parseFloat(prixbase.value)<1 ||parseFloat(prixbase.value)>99999){
    errorprixbase.innerHTML = "Veuillez entrer un prix compris entre 1 et 999 999 €";
    ok=false;
  }else{
    errorprixbase.innerHTML="";
  }
  if(ok && parseFloat(prixretrait.value)>1){
    if(parseFloat(prixbase.value)*0.9 >= parseFloat(prixretrait.value)) {
      errorprixbase.innerHTML = "";
      return true;
    } else {
      errorprixbase.innerHTML = "Veuillez entrer un prix 10% supérieur au prix de retrait (soit un prix supérieur ou égal à "+ (parseFloat(prixretrait.value)*1.1).toFixed(2)+")";
      prixbase.scrollIntoView();
      return false;
    }
  }
  return false;
}
function validateCheckBoxes(cb11,cb22,errorp1){
  let cb1 = document.getElementById(cb11),
  cb2 = document.getElementById(cb22),
  errorp = document.getElementById(errorp1);
  //Si au moins une case est cochée c'est ok, sinon on le signale à l'utilisateur
  if(cb1.checked || cb2.checked){
    errorp.innerHTML = "";
    return true;
  }
  errorp.innerHTML = "Veuillez cocher au moins une des deux cases";
  return false;
}
function validateCodePostal(){
  let numbers = codePostal.value.replace(/\D/g,"").slice(-5);
  var Reg = new RegExp(/^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/i);
 if(codePostal.value.replace(/\D/g,"").length!=5 || !Reg.test(numbers)){
    errorCodePostal.innerHTML = "Veuillez entrer un code postal valide à 5 chiffres";
    nom.scrollIntoView();
    return false;
  }else{
    errorCodePostal.innerHTML="";
    return true;
  }
}

let compteur = 0;

function removeImg(id){
 let output = document.getElementById("output"+id); 
 output.src = "../View/design/img/transparent.png";
 document.getElementById("p"+id).style.setProperty('display', 'block');
 compteur--;
 //Supprimer de tmp files
 for (let filename of tmp_files.keys()) {
  if(filename.at(0)===id){
    tmp_files.delete(filename);
    images.value ="";
    break;
  }
}
}

//----------------------------------------------------------//
//Affiche le fichier uploadé
const tmp_files = new Map();
const arrayIndexes = [];

function getFirstFreeSpot(){
  let i =0;
  let defaultImageURL = window.location.href.substring(0, window.location.href.lastIndexOf('/'));
  defaultImageURL = `${defaultImageURL.substring(0, defaultImageURL.lastIndexOf('/'))}/View/design/img/transparent.png`;
  while(i<6 && document.getElementById("output"+i).src !== defaultImageURL) {
    i++;
  }
  return i;
}
function loadFile(event) {

  //Si ya deja 6 images on empeche l'utilisateur de load un fichier
  let extension;
  if (compteur <= 6) {
    //Pour chaque fichier contenu dans l'input, on l'ajoute a tmp files si il y est pas déjà
    for (let i = 0; i < images.files.length; i++) {
      const filename = images.files[i]["name"];
      //Check que le fichier nest pas deja dans tmp files
      if (!tmp_files.has(filename)) {
        extension = filename.substring(images.files[i]["name"].lastIndexOf('.') + 1).toLowerCase();
        //check la taille du fichier pour pas faire crash le serveur on limite a 8méga
        if (images.files[i]["size"] > 8000000) {
          errorImgs.innerHTML = "La taille du fichier de ne doit pas excéder 8 Mo";
        } else if (extension !== 'jpg' && extension !== 'png' && extension !== "webp" && extension !== "jpeg") {
          errorImgs.innerHTML = "Les images doivent être au format png, jpg, jpeg ou webp";
        } else {
          errorImgs.innerHTML = "";

          //Affichage à l'utilisateur dans un tag img(output)
          let id = getFirstFreeSpot();
          tmp_files.set(id + filename, images.files[i]);
          let output = document.getElementById('output' + id);
          document.getElementById(`p${id}`).style.setProperty('display', 'none');
          compteur++;
          output.src = URL.createObjectURL(event.target.files[i]);
          output.onload = function () {
            URL.revokeObjectURL(output.src)
          }
        }
      }
    }
  }
}
function validateImages(){
  if(tmp_files.size!=0){
    errorImgs.innerHTML ="";
    return true;
  }else {
    errorImgs.innerHTML = "Veuillez ajouter au moins une image";
    select.scrollIntoView();
    return false;
  }
}
function validateInfos(event){
  event.preventDefault();
  let informationsEnvoieCheckBoxes = validateCheckBoxes("cbcolis","cbdirect","errorcbenvoie");
  let informationsContactCheckBoxes = validateCheckBoxes("cbemail","cbtel","errorcbcontact");
  let prixBase = validatePrixBase();
  let prixRetrait =validatePrixRetrait();
  let images = validateImages();
  let description = validateDescription()
  let codePostal = validateCodePostal();
  let categorie = validateCategories();
  let nom = validateNomAnnonce();
  let ok =nom && categorie && description && images && prixRetrait && prixBase && informationsEnvoieCheckBoxes && codePostal && informationsContactCheckBoxes;
  if (ok) {
    //Partie upload d'image, à faire en dernier
    const files = new FormData();
    var imgs;
    for (let i = 0; i < tmp_files.size; i++) {
        const file = tmp_files.get(Array.from(tmp_files.keys())[i]);
        files.append('file' + i, file, (Date.now() * (Math.floor(Math.random() * 7) + 1)) + file["name"].replace(/[^0-9a-zA-Z.]/g, ''));
      }
      //Vérifie que les images upload sont correctes
      let requete = new XMLHttpRequest();
      requete.open("POST", "../Ajax/upload.ajax.php", false);
      requete.onload = function () {
        const rep = JSON.parse(this.responseText);
        if (rep.success) {
          //Recup tableau urls image
          imgs = rep.imgs;
          images = true;
        } else {
          errorImgs.innerHTML = rep.errormsg;
          select.scrollIntoView();
          images = false;
        }
      }
      requete.send(files);
    }else{

      return false;
    }
    if (images) {
//les infos remplies sont valides : Création de l'enchère
      //première étape : récupérer toutes les données de formes et les envoyer a un controler php qui s'occupera de créer concretement l'enchere en base
      //deuxième étape : renvoyer l'utilisateur sur la page de consultation de son enchère créé
      let nom = document.getElementById("nom").value,
      prixbase = document.getElementById("prixbase").value,
      prixretrait = document.getElementById("prixretrait").value,
      categorie = document.getElementById("categorieSelect").value,
      description = document.getElementById("description").value,
      infosEnvoi = document.getElementById("cbcolis").checked + "," + document.getElementById("cbdirect").checked,
      infosContact = document.getElementById("cbemail").checked + "," + document.getElementById("cbtel").checked,
      codePostal = document.getElementById("localisationInput").value.replace(/\D/g, "").slice(-5);
      //Envoie php
      let requete = new XMLHttpRequest();
      requete.open("POST", "../Ajax/creation.ajax.php", true);
      requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      requete.onload = function () {
        const rep = JSON.parse(this.responseText);
        if (rep.sucess) {
          self.location = "main.ctrl.php";
        }
      }
      //Envoie la requête au serveur avec en paramètres les valeurs des inputs
      requete.send("nom=" + nom + "&prixbase=" + prixbase + "&prixretrait=" + prixretrait + "&imgs=" + imgs + "&categorie=" + categorie + "&description=" + description + "&infosEnvoi=" + infosEnvoi + "&infosContact=" + infosContact + "&codePostal=" + codePostal);
      return true;
    } else {
      errorImgs.innerHTML="Veuillez entrer une image valide";
      return false;
    }
  } 

