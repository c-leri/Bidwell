window.onload = showItems();


function showItems() {
        //Crée une nouvelle requête XMLHTTP à envoyer au serveur
        const xhttp = new XMLHttpRequest();
    
        //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
        xhttp.onload = function() {
            document.getElementById("vosEncheres").innerHTML = this.responseText;
        }
    
        createur = document.getElementById("login").innerText;
        console.log(createur);
        //Ouvre la requête au serveur avec pour informations le tri, le type, les catégories sélectionnées et le numéro de page
        xhttp.open("GET", "compte-ajax.ctrl.php?createur=" + createur);
    
        //Envoie la requête au serveur
        xhttp.send();
    }                                                                                  