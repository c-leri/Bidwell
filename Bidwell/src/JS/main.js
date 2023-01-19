window.onload = initPage;

function showItems(ordre, cible) {

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById(cible).innerHTML = this.responseText;
  }
  xhttp.open("GET", "../Ajax/main.ajax.php?ordre=" + ordre);
  xhttp.send();
}



function initPage() {
  if(performance.navigation.type == 2){
    location.reload(true);
 }

  showItems("ASC", "new");
  showItems("DESC", "old");
}
