window.onload = showItems("ASC", "new");
window.onload = showItems("DESC", "old");

function showItems(ordre, cible) {

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      document.getElementById(cible).innerHTML = this.responseText;
    }
    xhttp.open("GET", "main-ajax.ctrl.php?ordre=" + ordre);
    xhttp.send();
  }