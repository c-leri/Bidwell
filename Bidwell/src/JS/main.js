window.onload = initPage;

function showItems(ordre, cible) {
  const xhttp = new XMLHttpRequest();

  xhttp.onload = function () {
    document.getElementById(cible).innerHTML = this.responseText;
  }
  xhttp.open("GET", "../Ajax/main.ajax.php?ordre=" + ordre);
  xhttp.send();
}


function showItemsWon() {

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("won").innerHTML = this.responseText;
    
  }

  let login = document.getElementById("login").value;
  if (login != "") {

    xhttp.open("GET", "../Ajax/main.ajax.php?login=" + login);
    xhttp.send();
  }
  else {
    document.getElementById('hrWon').style = "display:none;";
    document.getElementById('titleWon').style = "display:none;";
    }
}


function initPage() {
  if(performance.navigation.type == 2){
    location.reload(true);
 }

  showItems("ASC", "new");
  showItems("DESC", "old");
  showItemsWon();
}
