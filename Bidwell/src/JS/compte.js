window.onload = function () {
    var parts = window.location.search.substr(1).split("&");
    var $_GET = {};
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

initPage($_GET['compte']);
}

function showItems(compte) {
        //Crée une nouvelle requête XMLHTTP à envoyer au serveur
        const xhttp = new XMLHttpRequest();
    
        //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
        xhttp.onload = function() {
            document.getElementById("vosEncheres").innerHTML = this.responseText;
        }
    
        //Ouvre la requête au serveur avec pour informations le tri, le type, les catégories sélectionnées et le numéro de page
        xhttp.open("GET", "compte-ajax.ctrl.php?compte=" + compte);
    
        //Envoie la requête au serveur
        xhttp.send();
    }