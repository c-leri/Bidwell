//Fonction utilisée lorsque le type change (utilisateur/enchère), que les catégories sélectionnées changent(ajout/retrait)
// ou que le tri change (nom/date/prix)

//Fonction permettant de chercher un paramètredans l'URL de la page
//Puis, initialise l'AJAX de la page

//En gros, fait que le JavaScript peut récupérer le $_GETs 
window.onload = function () {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });

    initPage(params.categories);
}

function showItems() {


    //Récupère les informations des éléments de tri/filtre/type de la page

//Tri : Quel type de tri est appliqué (par nom, date, prix, ...)
    let tri = document.getElementById("tri").value;

    //Type : Quel type d'élément est à afficher (enchères ou utilisateur)
    let type = document.querySelector('input[name="typeSelected"]:checked').value;

//Catégories : Quelle(s) catégorie(s) a(ont) été sélectionnée(s) (De 0 à n catégories)
    let categories = [];
    if (document.querySelectorAll('#checkboxes input:checked').length > 0){
        document.querySelectorAll('#checkboxes input:checked').forEach((element) => {
            categories.push(element.getAttribute('id'));
        });
    }

    //Prix : Quel rayon de prix a été sélectionné (Moins de 10€, de 10 à 20, ...)
    let prix = document.querySelector('input[name="prixSelected"]:checked').value;

    //Le numéro de la page à afficher (= Décalage des annonces affichées)

    //Crée une nouvelle requête XMLHTTP à envoyer au serveur
    const xhttp = new XMLHttpRequest();

    //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
    xhttp.onload = function() {
        document.getElementById("annonces").innerHTML = this.responseText;
    }

    //Ouvre la requête au serveur avec pour informations le tri, le type, les catégories sélectionnées et le numéro de page
    xhttp.open("GET", "../Ajax/recherche.ajax.php?categories=" + categories + "&tri=" + tri + "&type=" + type + "&prix=" + prix + "&numPage=" + 1);

    //Envoie la requête au serveur
    xhttp.send();
}





//Fonction utilisée lorsque le numéro de page change (= même liste, articles suivants ou précédents)
//Fonctionne de la même manière que la fonction précédente
function changePage(numPage) {

    //Tri : Quel type de tri est appliqué (par nom, date, prix, ...)
    let tri = document.getElementById("tri").value;

    //Type : Quel type d'élément est à afficher (enchères ou utilisateur)
    let type = document.querySelector('input[name="typeSelected"]:checked').value;

//Catégories : Quelle(s) catégorie(s) a(ont) été sélectionnée(s) (De 0 à n catégories)
    let categories = [];
    if (document.querySelectorAll('#checkboxes input:checked').length > 0){
        document.querySelectorAll('#checkboxes input:checked').forEach((element) => {
            categories.push(element.getAttribute('id'));
        });
    }

    //Prix : Quel rayon de prix a été sélectionné (Moins de 10€, de 10 à 20, ...)
    let prix = document.querySelector('input[name="prixSelected"]:checked').value;  
    

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById("annonces").innerHTML = this.responseText;
        window.scrollTo(0, 0);
    }
    xhttp.open("GET", "../Ajax/recherche.ajax.php?categories=" + categories + "&tri=" + tri + "&type=" + type + "&prix=" + prix + "&numPage=" + numPage);
    xhttp.send();
}


//Fonction utilisée lorsque la fenêtre est chargée afin de mettre à jour automatiquement la liste de catégories
//Une catégorie peut être entrée en paramètre afin de permettre un tri dès le début (par exemple lorsqu'on clique sur une catégorie sur la page main)
//une fois les catégories mises à jour, affiche les enchères
function initPage(categorie){

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementsByClassName("smallContainer")[0].innerHTML = this.responseText;
        showItems();
    }

    xhttp.open("GET", "../Ajax/recherche-aside.ajax.php?categories=" + categorie);
    xhttp.send();


    
}


function showCategory(numero){

    let list = document.getElementsByClassName('categoryDropdown');
    list[numero].lastChild.classList.toggle("active");
}