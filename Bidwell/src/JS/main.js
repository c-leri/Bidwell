window.onload = showItems("ASC", "new");
window.onload = showItems("DESC", "old");

function showItems(ordre, cible) {

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function() {
      console.log(this.responseText);
      document.getElementById(cible).innerHTML = this.responseText;
    }
    xhttp.open("GET", "main-ajax.ctrl.php?ordre=" + ordre);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhttp.send();
  }