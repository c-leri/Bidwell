//Pour réinitialiser vos cookies pour faciliter les tests
//window.onload = setCookie("infosPersonnelles", "");


function setCookie(nomCookie, valeurCookie) {
  const d = (valeurCookie === 'accepte') ? new Date().setFullYear(new Date().getFullYear + 5) : new Date();
  const jours = "expires=" + d.toString();
  document.cookie = nomCookie + "=" + valeurCookie + ";" + jours + ";path=/;SameSite=Strict";
}

function getCookie(nomCookie) {
  let nom = nomCookie + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let liste = decodedCookie.split(';');
  for (let i = 0; i < liste.length; i++) {
    let leCookie = liste[i];
    while (leCookie.charAt(0) === ' ') {
      leCookie = leCookie.substring(1);
    }
    if (leCookie.indexOf(nom) === 0) {
      return leCookie.substring(nom.length, leCookie.length);
    }
  }
  return "";
}

function checkCookie(nomCookie) {
  let infos = getCookie(nomCookie);
  if (infos !== "accepte") {
    openPopup();
  }
}

function closePopup() {
  document.getElementById("fond-cookies").style.display = "none";
}
function openPopup() {
  document.getElementById("fond-cookies").style.display = "block";
}

// Pour que le bouton "Valider votre choix" soit disabled ou pas
const okCookies = document.getElementById('okCookies');
const valider_cookies = document.getElementById('valider-cookies');
okCookies.onchange = function () {
  valider_cookies.disabled = !this.checked;
};

function actionValiderCookies(nomCookie) {
  setCookie(nomCookie, 'accepte');
  closePopup();
}

function actionRefuserCookies(nomCookie) {
  setCookie(nomCookie, 'refuse');
  closePopup();
  self.location = 'main.ctrl.php';
}