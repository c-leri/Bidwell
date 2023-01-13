<?php
//Inclusion de la base de donnée
require(__DIR__ . "/../model/Enchere.class.php");

$dao = DAO::get();

//Vérifie si le type sélectionné edst "enchère" ou "utilisateur"
if ($_GET['type'] == 'Enchere') {

    $tri = $_GET['tri'] !== 'NULL' ? $_GET["tri"] : 'date';
    
        //Si le type est "enchère", alors vérifie si des catégories ont étées sélectionnées ou non et les transforme en un seul string
        if (isset($_GET["categories"])) {

            $categories = implode(' OR ', $_GET["categories"]);

            //Exécute la requête SQL avec les informations nécessaires à l'affichage
            $result = Enchere::readLike($categories, "", $_GET['tri'], 'ASC', $_GET['page'], 20);

        } else {

            //Si aucune catégorie sélectionnée
            //Exécute la requête SQL avec les informations nécessaires à l'affichage
            $result = Enchere::readLike("", "", $_GET['tri'], 'ASC', $_GET['page'], 20);
      
        }

} else {
    //Si le type est "Utilisateur", prépare la requête SQL avec les informations nécessaires à l'affichage
    $sql = "SELECT login
    FROM Utilisateur ORDER BY login LIMIT ?, 20";

    //Execute la requête et place le résultat dans $resultat
    $result = $dao->query($sql, [($_GET['page']-1)* 20]); 
}

//Initialisation de variable qui sera renvoyée
$str = "";



//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
if ($_GET['type'] == 'Enchere') {
    for ($i = 0; $i < sizeof($result); $i++) {
        $str .= "<article>";
        $str .= '<a href="consultation.ctrl.php">';
        $str .= '<img src="../view/design/img/default_image.png" alt="">';
        $str .= "</a>";
        $str .= "<h1>" . $result[$i]->getLibelle() . "</h1>";
        $str .= '<div class="variablesAnnonce">';
        $str .= '<p class="categorie">' . $result[$i]->getCategorie()->getLibelle() . "</p>";
        $str .= '<p class="temps-restant">' . $result[$i]->getDateDebut()->format("Y-m-d") . "</p>";
        $str .= '<p class="prix-actuel">' . $result[$i]->getPrixDepart() . "</p>";
        $str .= '<p class="createur">' . $result[$i]->getCreateur()->getLogin(). "</p>";
        $str .= "<p> Description </p>";
        $str .= '<p class="description">' . $result[$i]->getDescription() . "</p>";
        $str .= "</div>";
        $str .= "</article>";
    }
} else {
    for ($i = 0; $i < sizeof($result); $i++) {
        $str .= "<article>";
            
        
        $str .= "</article>";
    }
}
//Renvoie le code à afficher
echo $str;
?>