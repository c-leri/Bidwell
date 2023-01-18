<?php
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

$createur = $_GET['createur'];

//Exécute la requête SQL avec les informations nécessaires à l'affichage
$result = Enchere::readFromCreateurString($createur);

if(isset($_GET['suppr'])){
    $supprime=Enchere::read($_GET['suppr']);
    $supprime->delete();
}
//Initialisation de variable qui sera renvoyée
$str = "";

//Pour chaque ligne de résultat, prépare son affichage en l'ajoutant à la variable renvoyée
for ($i = 0; $i < sizeof($result); $i++) {
    $str .= "
        <article>
            <a href='consultation.ctrl.php?id={$result[$i]->getId()}'>
                <img src='{$result[$i]->getImageURL(0)}' alt='{$result[$i]->getLibelle()}' />
            </a>
            <div class='variablesEnchere'>
                <h1>{$result[$i]->getLibelle()}</h1>
                <ul>
                    <li>{$result[$i]->getCategorie()->getLibelle()}</li>
                    <li>{$result[$i]->getPrixDepart()}€</li>   
                    <li>{$result[$i]->getDateDebut()->format('Y-m-d')}</li>
                    <li>{$result[$i]->getCreateur()->getLogin()}</li>
                </ul>
                <button id='suppressionenchere' onclick='supprenchere('.$result[$i]->getId().')>Supprimer</button>
            </div>
        </article>
    ";
}

//Renvoie le code à afficher
echo $str;