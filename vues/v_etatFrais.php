<?php
/**
 * Vue État de Frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<div class="panel panel-primary">
    <?php if ($estConnecteVisiteur) { ?>
        <div class="panel-heading">Fiche de frais du mois 
            <?php echo $numMois . '-' . $numAnnee ?> : </div>
    <?php } elseif ($estConnecteComptable) { ?>
        <div class="panel-heading">Fiche de frais du mois 
            <?php echo $numMois . '-' . $numAnnee ?> pour <?php echo $prenom . ' ' . $nom ?> / statut <?php echo $etatFiche ?></div> 
    <?php } ?>
    <div class="panel-body">
        <strong><u>Etat :</u></strong> <?php echo $libEtat ?>
        depuis le <?php echo $dateModif ?> <br> 
        <strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">Eléments forfaitisés</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle'];
                ?>
                <th> <?php echo htmlspecialchars($libelle) ?></th>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                ?>
                <td class="qteForfait"><?php echo $quantite ?> </td>
                <?php
            }
            ?>
        </tr>
    </table>
</div>
<div class="panel panel-info">
    <div class="panel-heading">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reçus</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>                
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant'];
            ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>

<?php if ($estConnecteComptable) { ?>
    <div>
        <h4>Nombre de justificatifs : <?php echo $nbJustificatifs ?></h4>
    </div>
    <div>   
        <form method="post" 
              action="index.php?uc=suivreFrais&action=afficherFraisEtChangeEtat" 
              role="form"> 
            <button class="btn btn-success" type="submit" id="btnChangeEtat" role="form" value="<?php echo $key ?>"
                    action="index.php?uc=suivreFrais&action=afficherFraisEtChangeEtat" name="btnChangeEtat"
                    onclick="confirm('Modifications effectuées!');"
                    >Passer la fiche à l'état suivant </button> 

            <input type ="hidden" name="lstFiches" id="lstFiches" value="<?php echo $key ?>" /> 

        </form>       
    </div>
<?php } ?>