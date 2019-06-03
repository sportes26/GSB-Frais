<?php
/**
 * Vue Validation des frais
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
<div class="row"> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src ="main.js"></script>
    <h2>Valider la fiche de frais <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=validerFrais&action=corrigerFraisForfait" 
              role="form"
              class="ajax">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite'];
                    ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
            </fieldset>
            <input name="visiteurSelection" type="hidden" value="<?php echo $visiteurSelection ?>">
            <input name="moisSelection" type="hidden" value="<?php echo $moisSelection ?>">
            <button class="btn btn-success" type="submit"
                    onclick ="corrigerFrais('form.ajax'); return confirm('Frais Mis à jour');"
                    href="index.php?uc=validerFrais&action=corrigerFraisForfait="
                    >Corriger</button>
            <button class="btn btn-danger" type="reset"
                    href="index.php?uc=validerFrais&action=reinitialiserFraisForfait="
                    >Réinitialiser</button>
        </form>
    </div>
</div>

<!-- Hors Forfait -->
<hr>
<div class="row">
    <div class="panel panel-info" id="test">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        <form method="post" 
              action="index.php?uc=validerFrais&action=refuserEtReporterFrais" 
              role="form"
              class="ajax">
            <table class="table table-bordered table-responsive">

                <thead>
                    <tr>
                        <th class="date">Date</th>
                        <th class="libelle">Libellé</th>  
                        <th class="montant">Montant</th>  
                        <th class="action">&nbsp;</th> 
                    </tr>
                </thead>  
                <tbody>
                    <?php
                    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                        $statut = $unFraisHorsForfait['statut'];
                        $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                        $date = $unFraisHorsForfait['date'];
                        $montant = $unFraisHorsForfait['montant'];
                        $idFrais = $unFraisHorsForfait['id'];
                        ?>           
                        <tr>
                    <input class="form-control" name="idFrais" type="hidden" value="<?php echo $idFrais ?>">

                    <td> 
                        <div class="form-group">
                            <input type="text" id="lesFraisDate" disabled
                                   name="lesFraisDate[<?php echo $idFrais ?>]"
                                   size="6" maxlength="10" 
                                   value="<?php echo $date ?>" 
                                   class="form-control">
                        </div>

                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" id="lesFraisLibelle" disabled
                                   name="lesFraisLibelle[<?php echo $idFrais ?>]"
                                   size="20" maxlength="50" 
                                   value="<?php echo $statut . $libelle ?>" 
                                   class="form-control">
                        </div>
                    </td>
                    <td><div class="form-group">
                            <input type="text" id="lesFraisMontant" disabled
                                   name="lesFraisMontant[<?php echo $idFrais ?>]"
                                   size="3" maxlength="6" 
                                   value="<?php echo $montant ?>" 
                                   class="form-control">
                        </div></td>
                    <td><button class="btn btn-success" type="submit" id="btnReportFraisHF"
                                href="index.php?uc=validerFrais&action=refuserEtReporterFrais=<?php echo $idFrais ?>"
                                role="form" name="btnReportFraisHF" value="<?php echo $idFrais ?>"
                                onclick="confirm('Frais reporté au mois suivant!');"
                                >Reporter</button>
                        <button class="btn btn-danger" type="submit" id="btnRefusFraisHF"
                                href="index.php?uc=validerFrais&action=refuserEtReporterFrais=<?php echo $idFrais ?>" 
                                role="form" name="btnRefusFraisHF" value="<?php echo $idFrais ?>"
                                onclick="confirm('Frais refusé!');"
                                >Refuser</button></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>  
                <input name="visiteurSelection" type="hidden" value="<?php echo $visiteurSelection ?>">
                <input name="moisSelection" type="hidden" value="<?php echo $moisSelection ?>">

            </table>
        </form>
    </div>
</div>

<div>
    <iframe name="iframeJustifs" style="display:none;"></iframe>
    <form method="post" 
          action="index.php?uc=validerFrais&action=majJustifs"
          role="form"
          class="form-inline"
          target="iframeJustifs">
        <h4>Nombre de justificatifs :</h4>

        
            <div class="form-group">
                <input type="text" id="nbJustificatifs" 
                       name="nbJustificatifs"
                       size="4" maxlength="3" 
                       value="<?php echo $nbJustificatifs ?>" 
                       class="form-control">
            </div>
        <div class="form-group">
                <button class="btn btn-success" type="submit" 
                        href="index.php?uc=validerFrais&action=majJustifs"
                        onclick="return confirm('Nb justificatifs mis à jour!');"
                        >Valider</button>
            </div>
        

        <input name="visiteurSelection" value="<?php echo $visiteurSelection; ?>" type="hidden">
        <input name="moisSelection" value="<?php echo $moisSelection; ?>" type="hidden">
    </form>
    </div>

</form>
</div>

<div style="text-align: center">
    <iframe name="iframe" style="display:none;"></iframe>
    <form method="post" 
          action="index.php?uc=validerFrais&action=validationFicheFrais" 
          role="form"
          target="iframe">
        <button class="btn btn-lg" type="submit"
                href="index.php?uc=validerFrais&action=validationFicheFrais"
                onclick="if (confirm('Fiche de frais validée')) {
                            this.form.submit();
                        } else {
                            return false;
                        }
                ">Valider la fiche de frais</button>
        <input name="visiteurSelection" value="<?php echo $visiteurSelection; ?>" type="hidden"> 
        <input name="moisSelection" value="<?php echo $moisSelection; ?>" type="hidden"> 
    </form>

</div>
<br>