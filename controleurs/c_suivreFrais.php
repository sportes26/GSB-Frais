<?php
/**
 * Gestion du suivi des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
    case 'choisirFiche':
        $lesFiches = $pdo->getLesFicheFraisAPayer();
        include 'vues/v_selectionFichesFrais.php';
        break;
    case 'afficherFraisEtChangeEtat':
        $lesFiches = $pdo->getLesFicheFraisAPayer();
        include 'vues/v_selectionFichesFrais.php';
        // on récupère les valeurs nécessaires
        $key = filter_input(INPUT_POST, 'lstFiches', FILTER_SANITIZE_STRING);
        $idVisiteur = $lesFiches[$key]['idVisiteur'];
        $moisFiche = $lesFiches[$key]['mois'];
        $etatFiche = $lesFiches[$key]['idEtat'];
        $prenom = $lesFiches[$key]['prenom'];
        $nom = $lesFiches[$key]['nom'];
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $moisFiche);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisFiche);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $moisFiche);
        $numAnnee = substr($moisFiche, 0, 4);
        $numMois = substr($moisFiche, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

        if (isset($_POST['btnChangeEtat'])) {
            $lesFiches = $pdo->getLesFicheFraisAPayer();
            $key = filter_input(INPUT_POST, 'btnChangeEtat', FILTER_SANITIZE_STRING);
            $idVisiteur = $lesFiches[$key]['idVisiteur'];
            $moisFiche = $lesFiches[$key]['mois'];
            $etatFiche = $lesFiches[$key]['idEtat'];
            if ($etatFiche == "MP") {
                $nouvelEtat = 'RB'; //alors elle passe à l'état "remboursée"
            } else { //Si elle est "VA" :
                $nouvelEtat = 'MP'; //Alors elle passe à l'état "mise en paiement"
            }
            $pdo->majEtatFicheFrais($idVisiteur, $moisFiche, $nouvelEtat);
            header('Location: index.php?uc=suivreFrais&action=choisirFiche'); // redirige vers le menu pour choisir une fiche
        }
    
        include 'vues/v_etatFrais.php';
        break;
}
