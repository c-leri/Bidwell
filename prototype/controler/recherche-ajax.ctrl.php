<?php
/*
1.? : Sélectionne les enchères des catégories sélectionnées 
2.? : 
*/
if ($_GET['type'] == 'Enchere') {

    if (isset($_GET["tri"])) {
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

        if (isset($_GET["categories"])) {

            $categories = implode(' OR ', $_GET["categories"]);

        }

        $sql = "SELECT libelle, cateogorie, dateDebut, prixDepart, loginCreateur, description
        FROM Enchere WHERE categorie = ? ORDER BY ? ASC LIMIT ?, 20";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $categories, $tri, $_GET['page'] * 20);

    } else {

        $sql = "SELECT libelle, cateogorie, dateDebut, prixDepart, loginCreateur, description
        FROM Enchere ORDER BY ? ASC LIMIT ?, 20";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $tri, $_GET['page'] * 20);
    }



} else {
    $sql = "SELECT login
    FROM Utilisateur ORDER BY login LIMIT ?, ?";

    $stmt = $labasededonnee->prepare($sql);
}

$stmt->execute();
$result = $labasededonnee.$query($sql);
$stmt->fetch();
$stmt->close();

$str = "";

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

echo $str;
?>