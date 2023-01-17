
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
    for (let i = 0; i < rep.array.length ; i++) {
      //Création option
      option = document.createElement("option");
      option.textContent= rep.array[i];
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
  //Création des options contenant code postal+ ville en fonction de l'input utilisateur
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

function validateCategories(){
  const categorieSelect = document.getElementById("categorieSelect"),
  errorCategorie = document.getElementById("errorcategorie"),
  nom = document.getElementById("nom");
  //Si la categorie selectionnée est une catégorie mère on l'indique à l'utilisateur
      if(categorieSelect.value.includes("-")) {
        errorCategorie.innerHTML = "Veuillez selectionner une sous catégorie";
      nom.scrollIntoView();
      return false;
    } else {
      errorCategorie.innerHTML = "";
        return true;
    }
}
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

function validateCheckBoxes(cb11,cb22,errorp1){
  const cb1 = document.getElementById(cb11),
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
var compteur = 0;
const imgInput=  document.getElementById("imagesInput");

function removeImg(id){
 let output = document.getElementById("output"+id); 
 output.src = "../View/design/img/default_image.png";
 document.getElementById("p"+id).style = "display: block;";
 compteur--;
 //Supprimer de tmp files
 for (let filename of tmp_files.keys()) {
  if(filename.at(0)==id){
    tmp_files.delete(filename);
    imgInput.value ="";
    break;
  }
}
}

//----------------------------------------------------------//
//Affiche le fichier uploadé
var tmp_files = new Map();
var arrayIndexes = new Array();
const errorImgs= document.getElementById("errorimgs");

function getFirstFreeSpot(){
  let i =0;
  while(i<8 && document.getElementById("output"+i).src != "http://localhost:3000/Bidwell/src/View/design/img/default_image.png" && document.getElementById("output"+i).src != "http://localhost:3000/src/View/design/img/default_image.png"){
    console.log(i<8 && document.getElementById("output"+i).src);
    console.log("../View/design/img/default_image.png");  

    i++;
  }
  return i;
}
function loadFile(event) {

  //Si ya deja 8 images on empeche l'utilisateur de load un fichier
  if (compteur <= 8){
    //Pour chaque fichier contenu dans l'input, on l'ajoute a tmp files si il y est pas déjà
    for (let i = 0; i < imgInput.files.length ; i++) {
      var filename = imgInput.files[i]["name"];
      //Check que le fichier nest pas deja dans tmp files
      if(!tmp_files.has(filename)){
        extension = filename.substr(imgInput.files[i]["name"].lastIndexOf('.')+1).toLowerCase();
        //check la taille du fichier pour pas faire crash le serveur on limite a 8méga
        if(imgInput.files[i]["size"]>800000){
          errorImgs.innerHTML = "La taille du fichier de ne doit pas excéder 8 Mo";
        }else if(extension!='jpg' && extension!='png' && extension!="webp" && extension!="jpeg"){
            errorImgs.innerHTML = "Les images doivent être au format png, jpg, jpeg ou webp";
        }else{
          errorImgs.innerHTML = "";
          
          //Affichage à l'utilisateur dans un tag img(output)
          let id =getFirstFreeSpot();
          console.log("nouveau fichier");
          tmp_files.set(id+filename,imgInput.files[i]);
          console.log(tmp_files);
          let output = document.getElementById('output' + id);
          document.getElementById("p"+id).style = "display:none;";
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
  let informationsEnvoieCheckBoxes = validateCheckBoxes("cbcolis","cbdirect","errorcbenvoie");
  let informationsContactCheckBoxes = validateCheckBoxes("cbemail","cbtel","errorcbcontact");
  let localisation = validateLocalisation();
  var images = false;//Retourne array avec les urls des images et booleen a la fin qui indique la reussite ou non
  let categorie = validateCategories();
  const errorImgs= document.getElementById("errorimgs"),
  select = document.getElementById("categorieSelect");//Ancre pour remonter dans la page en cas d'erreur

  // Create form data
  var files = new FormData();
  if(tmp_files.size!=0){
    
    for (let i = 0; i < tmp_files.size ; i++) {
      var file = tmp_files.get(Array.from(tmp_files.keys())[i]);
      files.append('file'+i,file, (Date.now()*(Math.floor(Math.random() * 7)+1))+file["name"].replace(/[^0-9a-zA-Z.]/g, ''));

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
  }else{
    //Aucune image upload: on le signale à l'utilisateur
    errorImgs.innerHTML = "Veuillez ajouter au moins une image";
    select.scrollIntoView();
    images = false;
  }
 /*
  console.log(images);
  console.log(prix);
  console.log(informationsContactCheckBoxes);
  console.log(localisation);
  console.log(informationsContactCheckBoxes);
      */
  let ok =categorie && images && prix &&  informationsEnvoieCheckBoxes && localisation && informationsContactCheckBoxes;
  if(ok){
    console.log("forme valide");
    //les infos remplies sont valides : Création de l'enchère
    //première étape : récupérer toutes les données de formes et les envoyer a un controler php qui s'occupera de créer concretement l'enchere en base
    //deuxième étape : renvoyer l'utilisateur sur la page de consultation de son enchère créé
    let nom = document.getElementById("nom").value,
    prixbase = document.getElementById("prixbase").value,
    prixretrait = document.getElementById("prixretrait").value;
    categorie = document.getElementById("categorieSelect").value;    
    description = document.getElementById("description").value;
    infosEnvoie = document.getElementById("cbcolis").checked+","+document.getElementById("cbdirect").checked;
    infosContact = document.getElementById("cbemail").checked+","+document.getElementById("cbtel").checked;
    localisation = document.getElementById("localisationInput").value;
    //Envoie php
    let requete = new XMLHttpRequest();
    requete.open("POST", "creationPart3.ctrl.php", true);
    requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    requete.onload = function() {
      console.log("rep : "+this.responseText);
      console.log(this.responseText);
        const rep = JSON.parse(this.responseText);
        
        if(rep.sucess){
           self.location = "main.ctrl.php";
        }
    }
    //Envoie la requête au serveur avec en paramètres les valeurs des inputs
    requete.send("nom="+nom+"&prixbase="+prixbase+"&prixretrait="+prixretrait+"&imgs="+urls+"&categorie="+categorie+"&description="+description+"&infosEnvoie="+infosEnvoie+"&infosContact="+infosContact+"&localisation="+localisation);
    return true;
  }else{
    console.log("nope form pas valide");
    return false;
  }
}
