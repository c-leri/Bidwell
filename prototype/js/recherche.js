function showItems() {

    let tri = document.getElementById("tri").innerText;
    let type = document.querySelector('input[name="typeSelected"]:checked').value;
    let categories  = "test";
    let numPage = 1;

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      document.getElementsByClassName("annonces").innerHTML = this.responseText;
    }
    xhttp.open("GET", "recherche-ajax.ctrl.php?tri=" + tri + "&type=" + type + "&page=" + numPage);
    xhttp.send();
  }

  function changePage(numPage) {

    let tri = document.getElementById("tri").innerText;
    let type = document.querySelector('input[name="typeSelected"]:checked').value;
    let categories = "test";

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      document.getElementsByClassName("annonces").innerHTML = this.responseText;
    }
    xhttp.open("GET", "recherche-ajax.ctrl.php?tri=" + tri + "&type=" + type + "&page=" + numPage);
    xhttp.send();
  }