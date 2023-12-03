<?php
use Bidwell\Model\Enchere;

require_once __DIR__.'/../../vendor/autoload.php';

$createur = $_GET['createur'];
$str = "";



//Exécute la requête SQL avec les informations nécessaires à l'affichage
if (isset($_GET['suppr'])) {
    $supprime = Enchere::read($_GET['suppr']);
    //On ne peut pas supprimer d'enchère qui a commencé
    $maintenant = new DateTime();
    if ($supprime->getDateDebut() > $maintenant || $supprime->getInstantFin() < $maintenant) {
        $supprime->delete();
        $result = Enchere::readFromCreateurString($createur);
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
                        <button class='btnmodal' id='suppressionenchere' onclick=supprenchere('" . $result[$i]->getId() . "')>Supprimer</button>
                    </div>
                    </article>";
        }
        $str .= "|%|OK";
    } else {
        $result = Enchere::readFromCreateurString($createur);
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
                        <button class='btnmodal' id='suppressionenchere' onclick=supprenchere('" . $result[$i]->getId() . "')>Supprimer</button>
                    </div>
                </article>";
        }
        $str .='<div id="myMessageErreur" class="modal" style="display : block;">
                    <div class="modal-content">
                        <span class="close" onclick="stop()">&times;</span>
                        <p>Vous ne pouvez pas supprimer une enchère en cours</p>
                    </div>
                </div>';
    }
} else {
    $result = Enchere::readFromCreateurString($createur);
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
                    <button class='btnmodal' id='suppressionenchere' onclick=supprenchere('" . $result[$i]->getId() . "')>Supprimer</button>
                </div>
                </article>
                ";
    }
}
echo $str;
