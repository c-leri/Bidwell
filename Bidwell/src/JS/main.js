window.onload = initPage();

function showItems(ordre, cible) {

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById(cible).innerHTML = this.responseText;
  }
  xhttp.open("GET", "main-ajax.ctrl.php?ordre=" + ordre);
  xhttp.send();
}

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
  let username = getCookie("username");
  if (username != "") {
    stop();
    }
  }

function stop() {
  document.getElementById("fond-cookies").style.display = "none";
}

// Pour que le bouton "Valider votre choix" soit disabled ou pas
var okCookies = document.getElementById('okCookies');
var valider_cookies = document.getElementById('valider-cookies');
okCookies.onchange = function() {
  valider_cookies.disabled  = !this.checked;;
};

function actionValiderCookies(nomCookie, valeurCookie, joursAvExpiration) {
  setCookie(nomCookie, valeurCookie, joursAvExpiration);
  stop();
}

function initPage() {
  showItems("ASC", "new");
  showItems("DESC", "old");


  checkCookie();
}
