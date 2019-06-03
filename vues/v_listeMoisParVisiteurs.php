<?php
/**
 * Vue Liste des mois par visiteur
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
?>

<!-- <link rel="stylesheet" href="styles/fonts/style.css" type="text/css"> -->
<style>
    .wrapper {
        display: flex;
        justify-content: flex-start;
        align-items:baseline;
    }
</style> 

<br>
<!-- affiche la liste des visiteurs disponibles dans la base de donnée -->
<div class="wrapper" id="wrapper">
    <form action="index.php?uc=validerFrais&action=selectionnerMois" 
          method="post" class="form-inline" role="form">
        <input nam="uc" value="validerFrais" type="hidden" />
        <input name="action" value="selectionnerVisiteur" type="hidden" />
        <div class="form-group"> 
            <label for="lstVisiteurs" accesskey="n">Choisir le visiteur : </label>
            <select id="lstVisiteurs" name="lstVisiteurs" class="form-control" onchange="if (this.value != 0) {
                        this.form.submit();
                    }">   
                <option selected="selected" value="available">Veuillez sélectionner un visiteur</option>
                <?php
                foreach ($lesVisiteurs as $unVisiteur) {
                    $nomVisiteur = $unVisiteur['nom'];
                    $prenomVisiteur = $unVisiteur['prenom'];
                    $idVisiteurChoisi = $unVisiteur['id'];
                    if ($idVisiteurChoisi == $visiteurSelection) {
                        ?>
                        <option selected value="<?php echo $idVisiteurChoisi ?>">
                            <?php echo $nomVisiteur . " " . $prenomVisiteur ?></option>
                        <?php
                    } else {
                        ?>
                        <option value ="<?php echo $idVisiteurChoisi ?>">
                            <?php echo $nomVisiteur . " " . $prenomVisiteur ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
    </form>
    <!-- affiche la liste des mois pour un visiteur en particulier -->
    <form action="index.php?uc=validerFrais&action=afficheFrais" 
          method="post" class="form-inline" role="form">
        <input nam="uc" value="validerFrais" type="hidden"/>
        <input name="action" value="selectionnerMois" type="hidden"/>
        <div class="form-group">
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
            <script src ="main.js"></script>
            <label for="lstMoisVisiteurs">&nbsp; Mois : </label>
            <select id="lstMoisVisiteurs" name="lstMoisVisiteurs" class="form-control" onclick="estVideMoisVisiteurs();"> <!-- affiche un message d'avertissement si pas de mois dispo -->  
                <?php
                foreach ($lesMois as $unMois) {
                    $mois = $unMois['mois'];
                    $numAnnee = $unMois['numAnnee'];
                    $numMois = $unMois['numMois'];
                    if ($mois == $moisSelection) {
                        ?>
                        <option selected value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?></option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?></option>
                        <?php
                    }
                }
                ?>
            </select> 
            <input name="lstVisiteurs" value="<?php echo $visiteurSelection ?>" type="hidden">
            <div class="form-group">
                <button class="btn btn-default" type="submit">Valider</button></div>
            <!-- <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button"> -->
        </div>
    </form>
</div>
<br>