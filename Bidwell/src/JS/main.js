window.onload = showItems("ASC", "new");
window.onload = showItems("DESC", "old");

function showItems(ordre, cible) {

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById(cible).innerHTML = this.responseText;
  }
  xhttp.open("GET", "main-ajax.ctrl.php?ordre=" + ordre);
  xhttp.send();
}

// Fermeture de la page 
function stop() {
  document.getElementById("fond-cookies").style.display = "none";
}

// pop up de cookies
window.onload = function () {
  // si cookies deja acceptés
  if (document.cookie.indexOf("cookies-acceptes=") = true) {
    document.getElementById("pop-up-cookies").toggle(active);
  }

};
  // accepter cookies
  document.getElementsByClassName("accept-cookies").on("click", function () {
    //   document.cookie = "cookies-acceptes=true;";
    document.getElementById("pop-up-cookies").toggle(active);
  });

  // refuser cookies
  document.getElementsByClassName("refuser-cookies").addEventListener("click", event => {
    document.getElementById("pop-up-cookies").toggle(active);
  });

  // configurer cookies
  document.getElementsByClassName("configurer-cookies").on("click", function () {
    document.getElementById("pop-up-cookies").toggle(active);
  });

