

const password = document.getElementById("password"),
confirm_password = document.getElementById("confirm_password"),
tel = document.getElementById("tel"),
username = document.getElementById("username"),
email = document.getElementById("mail"),
form= document.getElementById("signin-form"),
errorusername =  document.getElementById("errorusername"),
erroremail =  document.getElementById("erroremail"),
errortel =  document.getElementById("errornumtel");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Les mots de passe sont différents");
  } else {
    confirm_password.setCustomValidity('');
  }
}
function validatePhoneNumber(){
	if(isNaN(tel.value)) {
	  tel.setCustomValidity("Le numéro de téléphone doit être uniquement composé de chiffres");
	}else if(tel.value.length!=10){
	  tel.setCustomValidity("Le numéro de téléphone doit être composé de 10 chiffres ("+tel.value.length+" actuellement)");

  } else {
	  tel.setCustomValidity('');
	}
  }
  function validateInfos(event){
    //Empêche le form de reload la page et niquer la requête ajax
    event.preventDefault();
    var requete = new XMLHttpRequest();
    //Précise quel controleur php le client va contacter via ajax ainsi que la méthode utilisée
    requete.open("POST", "../controler/signupPart2.ctrl.php", true); //True pour que l'exécution du script continue pendant le chargement, false pour attendre.
    //Header utile au bon fonctionnement de la requête
    requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    requete.onload = function() {
      //Si l'utilisateur est déjà dans la base le serveur répond le message d'erreur correspondant
        const rep = JSON.parse(this.responseText);
        //Si tout se passe bien on connecte l'utilisateur
        if(rep.sucess){
          console.log("utilisateur inséré dans la base");
          self.location = "../controler/connect.ctrl.php";
        }else{
          //Sinon
           //Si nom d'utilisateur déjà dans la base
          if(rep.username){
            errorusername.innerHTML = rep.usernameerrormsg;
          }else{
            errorusername.innerHTML = "";
          }
          //Si email d'utilisateur déjà dans la base
          if(rep.email){
            erroremail.innerHTML = rep.emailerrormsg;
          }else{
            erroremail.innerHTML = "";
          }
           //Si numéro de téléphone d'utilisateur déjà dans la base
           if(rep.tel){
            errortel.innerHTML = rep.telerrormsg;
          }else{
            errortel.innerHTML = "";
          }
        }
      }
      //Envoie la requête au serveur avec en paramètres les valeurs des inputs
    requete.send("login="+username.value+"&password="+password.value+"&email="+email.value+"&phone="+tel.value);
  }
password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
tel.onchange = validatePhoneNumber;
form.onsubmit = validateInfos;