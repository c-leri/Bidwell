//Retourne true si une erreur survient, false sinon
function validateLogin(){
  const login = document.getElementById("username"),
  errorlogin = document.getElementById("errorusername");
  const usernameRegex = /^[a-zA-Z0-9_.]+$/
  if(usernameRegex.test(login.value)){
    //La regex trouve le bon pattern, le login est valide
    errorlogin.innerHTML="";
    return true;
  }
  errorlogin.innerHTML = "Veuillez entrer un nom d'utilisateur valide contenant uniquement des lettres, des chiffres, des underscore ou des points";
  return false;
}
//Retourne true si une erreur survient, false sinon
function validatePassword(){
  const password = document.getElementById("password"),
  confirm_password = document.getElementById("confirm_password")
  errorpassword = document.getElementById("errorpassword");
  if(password.value !== confirm_password.value) {
    errorpassword.innerHTML = "Les mots de passe sont différents";
    return false;
  }
  errorpassword.innerHTML = "";
  return true;
}
//Retourne true si une erreur survient, false sinon
function validatePhoneNumber(){
  const tel = document.getElementById("tel"),
  errortel =  document.getElementById("errornumtel");
	if(isNaN(tel.value)) {
    errortel.innerHTML= "Le numéro de téléphone doit être uniquement composé de chiffres";
    return false;
	}else if(tel.value.length!==10){
    errortel.innerHTML= "Le numéro de téléphone doit être composé de 10 chiffres ("+tel.value.length+" actuellement)";
    return false;
  } else {
	  errortel.innerHTML="";
    return true;
	}
}
//Retourne true si une erreur survient, false sinon
function validateAlreadyInBaseOrNot(){
    //On récupère l'accès aux différents éléments html
    const username = document.getElementById("username"),
    errorusername =  document.getElementById("errorusername"),
    email = document.getElementById("mail"),
    erroremail =  document.getElementById("erroremail"),
    tel = document.getElementById("tel"),
    errortel =  document.getElementById("errornumtel");
    let ok = true;
    let requete = new XMLHttpRequest();
    //Précise quel controleur php le client va contacter via ajax ainsi que la méthode utilisée
    requete.open("POST", "../Ajax/signup.ajax.php", true); //True pour que l'exécution du script continue pendant le chargement, false pour attendre.
    //Header utile au bon fonctionnement de la requête
    requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    requete.onload = function() {
      ok = true;
      //Si l'utilisateur est déjà dans la base le serveur répond le message d'erreur correspondant
      const rep = JSON.parse(this.responseText);
      //Si tout se passe bien on connecte l'utilisateur
      if(rep.sucess) {
          self.location = "connect.ctrl.php";
      } else {
          //Sinon
          //Si nom d'utilisateur déjà dans la base
          if(rep.username){
              errorusername.innerHTML = rep.usernameerrormsg;
              ok=false;
          }else{
              errorusername.innerHTML = "";
          }
          //Si email d'utilisateur déjà dans la base
          if(rep.email){
              erroremail.innerHTML = rep.emailerrormsg;
              ok=false;
          }else{
              erroremail.innerHTML = "";
          }
          //Si numéro de téléphone d'utilisateur déjà dans la base
          if(rep.tel){
              errortel.innerHTML = rep.telerrormsg;
              ok=false;
          }else{
              errortel.innerHTML = "";
          }
      }
    }
    //Envoie la requête au serveur avec en paramètres les valeurs des inputs
    requete.send("login="+username.value+"&password="+password.value+"&email="+email.value+"&phone="+tel.value);
    return ok;
}
  function validateInfos(event){
    //Empêche le form de reload la page et niquer la requête ajax
    event.preventDefault();
    //On vérifie les différents inputs
    let login = validateLogin();
    let passwd = validatePassword();
    let phone =validatePhoneNumber();
    if(!(login&&passwd&&phone)){
      return false;
    }else{
      return validateAlreadyInBaseOrNot();
    }
    
}
