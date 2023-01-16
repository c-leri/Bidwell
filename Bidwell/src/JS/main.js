window.onload = initPage();

function showItems(ordre, cible) {

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById(cible).innerHTML = this.responseText;
  }
  xhttp.open("GET", "main-ajax.ctrl.php?ordre=" + ordre);
  xhttp.send();
}

function setCookie(nomCookie, valeurCookie, joursAvExpiration) {
  const d = new Date();
  d.setTime(d.getTime() + (joursAvExpiration * 24 * 60 * 60 * 1000));
  let jours = "expires=" + d.toUTCString();
  document.cookie = nomCookie + "=" + valeurCookie + ";" + jours + ";path=/";
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
    document.getElementById("pop-up-cookies").classList.toggle("active"); //La classe est active de base, donc la désactive
    }
  }

function initPage() {
  showItems("ASC", "new");
  showItems("DESC", "old");


  checkCookie();
}