<?php
//Inclusion de la base de donnée
$dao = DAO::get();

//Vérifie si le type sélectionné edst "enchère" ou "utilisateur"
if ($_GET['type'] == 'Enchere') {

    //Si le type est "enchère", alors vérifie quel type de tri est sélectionné
    if (isset($_GET["tri"])) {

        //
        switch ($_GET["tri"]) {
            case "nom":

                $tri = "libelle";
                break;

            case "prix":
                $tri = "prixDepart";
                break;

            case "creation":
                $tri = "dateDebut";
                break;

            default:
                $tri = "libelle";
                break;
        }

        //Si le type est "enchère", alors vérifie si des catégories ont étées sélectionnées ou non et les transforme en un seul string
        if (isset($_GET["categories"])) {

            $categories = implode(' OR ', $_GET["categories"]);

        }

        //Prépare la requête SQL avec les informations nécessaires à l'affichage
        $sql = "SELECT libelle, cateogorie, dateDebut, prixDepart, loginCreateur, description
        FROM Enchere WHERE categorie = ? ORDER BY ? ASC LIMIT ?, 20";

        //Execute la requête et place le résultat dans $resultat
        $result = $dao->execute($sql, [$categories, $tri, $_GET['page'] * 20]);
        

    } else {

        //Si aucune catégorie sélectionnée
        //Prépare la requête SQL avec les informations nécessaires à l'affichage
        $sql = "SELECT libelle, cateogorie, dateDebut, prixDepart, loginCreateur, description
        FROM Enchere ORDER BY ? ASC LIMIT ?, 20";

        //Execute la requête et place le résultat dans $resultat
        $result = $dao->execute($sql, [$tri, $_GET['page'] * 20]);
  
    }

} else {
    //Si le type est "Utilisateur", prépare la requête SQL avec les informations nécessaires à l'affichage
    $sql = "SELECT login
    FROM Utilisateur ORDER BY login LIMIT ?, 20";

    //Execute la requête et place le résultat dans $resultat
    $answer = $dao->execute($sql, [$_GET['page'] * 20]);
    $result = $answer->fetchAll();
}

//Initialisation de variable qui sera renvoyée
$str = "";



//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
for ($i = 0; $i < sizeof($result); $i++) {
    $str .= "<article>";
    $str .= '<a href="consultation.ctrl.php">';
    $str .= '<img src="../view/design/img/default_image.png" alt="">';
    $str .= "</a>";
    $str .= "<h1>" . $result[$i][0] . "</h1>";
    $str .= '<div class="variablesAnnonce">';
    $str .= '<p class="categorie">' . $result[$i][1] . "</p>";
    $str .= '<p class="temps-restant">' . $result[$i][2] . "</p>";
    $str .= '<p class="prix-actuel">' . $result[$i][3] . "</p>";
    $str .= '<p class="createur">' . $result[$i][4] . "</p>";
    $str .= "<p> Description </p>";
    $str .= '<p class="description">' . $result[$i][5] . "</p>";
    $str .= "</div>";
    $str .= "</article>";
}

//Renvoie le code à afficher
echo $str;
?>