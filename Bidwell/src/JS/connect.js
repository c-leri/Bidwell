
let login = document.getElementById("login"),
errorlogin =  document.getElementById("errorlogin"),
password = document.getElementById("password"),
errorpassword =  document.getElementById("errorpassword");

function validateConnection(event){
    let ok = true;
    //Empêche le formulaire de reload la page et niquer la requête ajax
    event.preventDefault();
    const requete = new XMLHttpRequest();
    //Précise quel controleur php le client va contacter via ajax ainsi que la méthode utilisée
    requete.open("POST", "../Ajax/connect.ajax.php", true); //True pour que l'exécution du script continue pendant le chargement, false pour attendre.
    //Header utile au bon fonctionnement de la requête
    requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    requete.onload = function() {
        const rep = JSON.parse(this.responseText);
        //Si tout se passe bien on connecte l'utilisateur
        if(rep.sucess){
          self.location = "connect.ctrl.php";
        }else{
          //Si login inexistant
          if(rep.loginerror){
            errorlogin.innerHTML = rep.loginerrormsg;
            ok=false;
          }else{
            errorlogin.innerHTML = "";
          }
          //Si mot de passe incorrect
          if(rep.passworderror){
            errorpassword.innerHTML = rep.passworderrormsg;
            ok=false;
          }else{
            errorpassword.innerHTML = "";
          }
           
        }
      }
      //Envoie la requête au serveur avec en paramètres les valeurs des inputs
    requete.send("login="+login.value+"&password="+password.value);
    return ok;
}
