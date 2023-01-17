window.onload = initPage();

function showItems(ordre, cible) {

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById(cible).innerHTML = this.responseText;
  }
  xhttp.open("GET", "main-ajax.ctrl.php?ordre=" + ordre);
  xhttp.send();
}



function initPage() {
  showItems("ASC", "new");
  showItems("DESC", "old");
}
