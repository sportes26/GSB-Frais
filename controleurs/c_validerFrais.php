<?php
/**
 * Gestion de la validation des frais 
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
$idComptable = $_SESSION['idComptable'];
switch ($action) {
    case 'selectionnerVisiteur':
        // on accède ainsi à l'ensemble de la liste des visiteurs
        $lesVisiteurs = $pdo->getVisiteurs();
        // test pour que $visiteurSelection ne renvoie pas une erreur lors de l'initialisation
        if (!empty($_POST['lstVisiteurs'])) {
            $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        } else {
            $visiteurSelection = 1;
        }
        include 'vues/v_listeMoisParVisiteurs.php';
        break;
    case 'selectionnerMois':
        // on accède ainsi à l'ensemble de la liste des visiteurs
        $lesVisiteurs = $pdo->getVisiteurs();
        $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        // on accède ainsi à l'ensemble de la liste des mois disponibles pour ce visiteur
        $lesMois = $pdo->getLesMoisDisponibles($visiteurSelection);
        include 'vues/v_listeMoisParVisiteurs.php';
        break;
    case 'afficheFrais':
        $lesVisiteurs = $pdo->getVisiteurs();
        $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $lesMois = $pdo->getLesMoisDisponibles($visiteurSelection);
        $moisSelection = filter_input(INPUT_POST, 'lstMoisVisiteurs', FILTER_SANITIZE_STRING);
        include 'vues/v_listeMoisParVisiteurs.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurSelection, $moisSelection);
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurSelection, $moisSelection);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurSelection, $moisSelection);
        $numAnnee = substr($moisSelection, 0, 4);
        $numMois = substr($moisSelection, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include 'vues/v_valideFrais.php';
        break;
    case 'corrigerFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        $visiteurSelection = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
        $moisSelection = filter_input(INPUT_POST, 'moisSelection', FILTER_SANITIZE_STRING);
        $pdo->majFraisForfait($visiteurSelection, $moisSelection, $lesFrais);
        break;
    case 'reinitialiserFraisForfait':
        //Récupération du visiteur
        $lesVisiteurs = $pdo->getLesVisiteurs(); //Récupération de la liste de visiteurs
        $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING); //Enregistrement du visiteur selectionné
        //Récupération du mois
        $lesMois = $pdo->getLesMoisDisponibles($visiteurSelection); //récupération de la liste des mois
        $moisSelection = filter_input(INPUT_POST, 'lstMoisVisiteurs', FILTER_SANITIZE_STRING); //enregistrement du mois selectionné
        break;
    case 'refuserEtReporterFrais':
        $visiteurSelection = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
        $moisSelection = filter_input(INPUT_POST, 'moisSelection', FILTER_SANITIZE_STRING);
        // on récupère le frais et on le passe en statut refusé
        if (isset($_POST['btnRefusFraisHF'])) {
            $idFrais = filter_input(INPUT_POST, 'btnRefusFraisHF', FILTER_SANITIZE_STRING);
            $pdo->modifieStatutRefuse($idFrais, $visiteurSelection, $moisSelection);
            header('Location: index.php?uc=validerFrais&action=selectionnerVisiteur'); // redirige vers le menu
        }
        // on récupère le frais et on le reporte
        //Si je clic sur "reporter":
        if (isset($_POST['btnReportFraisHF'])) {
            //Récupération de l'idFraisHF qu'on souhaite supprimer
            $idFrais = filter_input(INPUT_POST, 'btnReportFraisHF', FILTER_SANITIZE_STRING);

            //Obtention du mois suivant:
            $moisSuivant = getMoisSuivant($moisSelection);
            //Première saisie du mois ?
            if ($pdo->estPremierFraisMois($visiteurSelection, $moisSuivant)) {
                //Création de la fiche de frais:
                $pdo->creeNouvellesLignesFrais($visiteurSelection, $moisSuivant);
            }
            //Récupération des différentes données du fraisHF:
            $libelle = $pdo->getLibelleFraisHF($idFrais, $visiteurSelection, $moisSelection);
            $dateFrais = getNouveauMois($moisSuivant);
            $montant = $pdo->getMontantFraisHF($idFrais, $visiteurSelection, $moisSelection);
            //Création du frais HF:
            $pdo->creeNouveauFraisHorsForfait($visiteurSelection, $moisSuivant, $libelle, $dateFrais, $montant);
            //Suppression de ce frais du mois en cours de saisie:
            $pdo->supprimerFraisHorsForfait($idFrais);
            header('Location: index.php?uc=validerFrais&action=selectionnerVisiteur'); // redirige vers le menu
        }
        break;
    case 'majJustifs':
        // récupération des variables nécessaires
        $visiteurSelection = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
        $moisSelection = filter_input(INPUT_POST, 'moisSelection', FILTER_SANITIZE_STRING);
        $nbJustificatifs = filter_input(INPUT_POST, 'nbJustificatifs', FILTER_SANITIZE_STRING);
        // mise à jour du nombre de justificatifs
        $pdo->majNbJustificatifs($visiteurSelection, $moisSelection, $nbJustificatifs);
        break;
    case 'validationFicheFrais':
        // récupération des variables nécessaires et addition des montants
        $visiteurSelection = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
        $moisSelection = filter_input(INPUT_POST, 'moisSelection', FILTER_SANITIZE_STRING);
        $montantFraisForfait = $pdo->getTotalMontantFraisForfait($visiteurSelection, $moisSelection);
        $montantFraisHorsForfait = $pdo->getTotalMontantFraisHF($visiteurSelection, $moisSelection);
        $totalFrais = $montantFraisForfait + $montantFraisHorsForfait;
        // mise à jour du montant validé dans la fiche et mise à jour du statut 
        $pdo->majMontantFicheFrais($visiteurSelection, $moisSelection, $totalFrais);
        $statutValide = 'VA';
        $pdo->majEtatFicheFrais($visiteurSelection, $moisSelection, $statutValide);
        break;
}

