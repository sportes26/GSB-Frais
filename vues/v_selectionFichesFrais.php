<?php
/**
 * Vue Sélection des frais
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
<br>
<div>
    <form action="index.php?uc=suivreFrais&action=afficherFraisEtChangeEtat" 
          method="post" class="form-inline" role="form" name="frmFiches" onsubmit="return verifieSiValeur();">
        <input nam="uc" value="suivreFrais" type="hidden"/>
        <input name="action" value="afficherFraisEtChangeEtat" type="hidden"/>
        <div class="form-group">
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
            <script src ="main.js"></script>
            <label for="lstFiches" accesskey="n">Selectionner la fiche de frais : </label>
            <select id="lstFiches" name="lstFiches" class="form-control" onclick="return estVideLstFiches();">
                <?php
                foreach ($lesFiches as $key => $uneFiche) {
                    $idVisiteur = $uneFiche['idVisiteur'];
                    $nom = htmlspecialchars($uneFiche['nom']);
                    $prenom = htmlspecialchars($uneFiche['prenom']);
                    $etatFiche = htmlspecialchars($uneFiche['idEtat']);
                    $moisFiche = $uneFiche['mois'];
                    $numAnnee = substr($moisFiche, 0, 4);
                    $numMois = substr($moisFiche, 4, 2);
                    $montantValide = ($uneFiche['montantValide']);
                    $dateModif = ($uneFiche['dateModif']);
                    ?>
                        <!-- <option selected value="<?php echo $idVisiteur . "," . $moisFiche . "," . $nom . "," . $prenom . "," . $etatFiche ?>"> -->
                    <option selected value="<?php echo $key ?>">
                        <?php echo $prenom . ' ' . $nom . ' ' . ':' . ' ' . $numMois . '/' . $numAnnee . ' (' . $etatFiche . ')' ?> </option>
                    <?php
                }
                ?>  
            </select>
            <div class="form-group">
                <button class="btn btn-default" type="submit">Valider</button></div>
        </div>
    </form>
</div>
<br>