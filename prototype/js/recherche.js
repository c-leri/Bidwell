//Fonction utilisée lorsque le type change (utilisateur/enchère), que les catégories sélectionnées changent(ajout/retrait)
// ou que le tri change (nom/date/prix)

//      //\\
//     //  \\
//    // |  \\
//   //  .   \\
//  //////\\\\\\  Filtre par catégorie pas encore implanté
function showItems() {


//Récupère les informations des élément sde tri/filtre/type de la page
    let tri = document.getElementById("tri").innerText;
    let type = document.querySelector('input[name="typeSelected"]:checked').value;
    let catégories;
    let numPage = 1;

    //Crée une nouvelle requête XMLHTTP à envoyer au serveur
    const xhttp = new XMLHttpRequest();

    //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
    xhttp.onload = function() {
      document.getElementsByClassName("annonces").innerHTML = this.responseText;
    }

    //Ouvre la requête au serveur avec pour informations le tri, le type, les catégories sélectionnées et le numéro de page
    xhttp.open("GET", "recherche-ajax.ctrl.php?tri=" + tri + "&type=" + type + "&page=" + numPage);

    //Envoie la requête au serveur
    xhttp.send();
  }

  //Fonction utilisée lorsque le numéro de page change (= même liste, articles suivant ou précédents)
  //Fonctionne de la même manière que la fonction précédente
  function changePage(numPage) {

    let tri = document.getElementById("tri").innerText;
    let type = document.querySelector('input[name="typeSelected"]:checked').value;
    let categories;

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      document.getElementsByClassName("annonces").innerHTML = this.responseText;
    }
    xhttp.open("GET", "recherche-ajax.ctrl.php?tri=" + tri + "&type=" + type + "&page=" + numPage);
    xhttp.send();
  }