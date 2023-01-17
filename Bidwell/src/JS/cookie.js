window.onload = checkCookie();
//Pour réinitialiser vos cookies pour faciliter les tests
//window.onload = setCookie("infosPersonnelles", "");


function setCookie(nomCookie, valeurCookie) {
    const d = new Date().setFullYear(new Date().getFullYear+5);
    let jours = "expires=" + d.toString();
    document.cookie = nomCookie + "=" + valeurCookie + ";" + jours + ";path=/;SameSite=Strict";
  }
  
  function getCookie(nomCookie) {
    let nom = nomCookie + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let liste = decodedCookie.split(';');
    for (let i = 0; i < liste.length; i++) {
      let leCookie = liste[i];
      while (leCookie.charAt(0) == ' ') {
        leCookie = leCookie.substring(1);
      }
      if (leCookie.indexOf(nom) == 0) {
        return leCookie.substring(nom.length, leCookie.length);
      }
    }
    return "";
  }
  
  function checkCookie() {
    let infos = getCookie("infosPersonnelles");
    if (infos == "accepte") {
        stop();
      } else {
        start();
      }
    }
  
  function stop() {
    document.getElementById("fond-cookies").style.display = "none";
    //console.log("cookies acceptés");
  }
  function start() {
    document.getElementById("fond-cookies").style.display = "block";
    //console.log("cookies pas acceptés");
  }
  
  // Pour que le bouton "Valider votre choix" soit disabled ou pas
  var okCookies = document.getElementById('okCookies');
  var valider_cookies = document.getElementById('valider-cookies');
  okCookies.onchange = function() {
    valider_cookies.disabled  = !this.checked;;
  };
  
  function actionValiderCookies(nomCookie, valeurCookie) {
    setCookie(nomCookie, valeurCookie);
    stop();
  }