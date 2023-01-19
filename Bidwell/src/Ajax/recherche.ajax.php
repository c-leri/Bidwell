<?php
// Inclusion du modèle
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

//var_dump($_GET['prix']);

$page = $_GET['numPage'] ?? 1;

$type = $_GET['type'] ?? 'Enchere';

//Vérifie si le type sélectionné edst "enchère" ou "utilisateur"
if ($type == 'Enchere') {

    $tri = $_GET['tri'];
    $ordre = "ASC";

    switch($tri){
        case "dateAsc":
            $tri = "date";
            break;
        case "dateDesc";
            $tri = "date";
            $ordre = "DESC";
            break;
        case "prixAsc":
            $tri = "prix";
            break;
        case "prixDesc";
            $tri = "prix";
            $ordre = "DESC";
            break;
        case "nomAsc":
            $tri = "nom";
            break;
        case "nomDesc";
            $tri = "nom";
            $ordre = "DESC";
            break;
        default:
            $tri = 'date';
            $ordre = 'ASC';
            break;
    }

    $prix = $_GET['prix'] !== 'NULL' ? $_GET["prix"] : 0;

    $recherche = isset($_GET['recherche']) ? $_GET['recherche'] : 'null';


    //Initialisation de variable qui sera renvoyée
    $str = "";

    //Si le type est "enchère", alors vérifie si des catégories ont étées sélectionnées ou non et les transforme en un seul string
    if (isset($_GET["categories"]) && $recherche != 'null') {

        $categories = explode(',', $_GET['categories']);
        $result = Enchere::readLike($categories, $_GET['recherche'], $tri, $prix, $ordre, $page, 10);
        
    
    } else  {

        $categories = explode(',', $_GET['categories']);
        //Exécute la requête SQL avec les informations nécessaires à l'affichage
        $result = Enchere::readLike($categories, "", $tri, $prix, $ordre, $page, 10);

    }  

} else {
    //Si le type est "Utilisateur",
    //Exécute la requête et place le résultat dans $resultat
    $result = Utilisateur::readLike('', $page, 20);

}



//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
if ($type == 'Enchere') {
    $maintenant = new DateTime();
    if (sizeof($result) > 0) {
        for ($i = 0; $i < sizeof($result); $i++) {
            if ($result[$i]->getInstantFin() > $maintenant) {
                $str .= "
                <article>
                    <a href='consultation.ctrl.php?id={$result[$i]->getId()}'>
                        <img src='{$result[$i]->getImageURL(0)}' alt='{$result[$i]->getLibelle()}'>
                    </a>
                    <div class='left'>
                        <h1>{$result[$i]->getLibelle()}</h1>
                        <h3>{$result[$i]->getCategorie()->getLibelle()}</h3>
                        <ul>
                            <li>{$result[$i]->getDateDebut()->format('Y-m-d')}</li>
                            <li>{$result[$i]->getPrixDepart()}€</li>
                            <li>{$result[$i]->getCreateur()->getLogin()}</li>
                        </ul>
                    </div>
                    <p class='description'>{$result[$i]->getDescription()}</p>
                </article>
            ";
            }
        }
    }else {
        $str .= "<p> Aucun article ne correspond à votre recherche. </p>";
    }

    $str .= "
        <div class='numPage'>
            <button id='previous' value='".max(1, $page-1)."' onclick='changePage(".max(1, $page-1).")'><</button>
            <button id='first' value='".max(1, $page-2)."' onclick='changePage(".max(1, $page-2).")'>".max(1, $page-2)."</button>
            <button id='second' value='".max(2, $page-1)."' onclick='changePage(".max(2, $page-1).")'>".max(2, $page-1)."</button>
            <button id='third' value='".max(3, $page)."' onclick='changePage(".max(3, $page).")'>".max(3, $page)."</button>
            <button id='fourth' value='".max(4, $page+1)."' onclick='changePage(".max(4, $page+1).")'>".max(4, $page+1)."</button>
            <button id='fifth' value='".max(5, $page+2)."' onclick='changePage(".max(5, $page+2).")'>".max(5, $page+2)."</button>
            <button id='next' value='".max(1, $page+1)."' onclick='changePage(".max(1, $page+1).")'>></button>
        </div>
    ";

} else {

    for ($i = 0; $i < sizeof($result); $i++) {
        $str .= "
            <article>
                <h1>{$result[$i]->getLogin()}</h1>
            </article>
        ";
    }

}

//Renvoie le code à afficher
echo $str;