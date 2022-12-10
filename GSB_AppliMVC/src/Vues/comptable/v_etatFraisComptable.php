<?php

/**
 * Vue État de Frais
 * 
 * PHP Version 8
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

use Outils\Utilitaires;

?>

<?php if ($uc === 'validerFrais') { ?>

    <div class="row">
        <h3>Eléments forfaitisés</h3>
        <div class="col-md-4">
            <form method="post" action="index.php?uc=validerFrais&action=CorrigerFraisForfait" role="form">
                <fieldset>
                    <?php
                    foreach ($lesFraisForfait as $unFrais) {
                        $idFrais = $unFrais['idfrais'];
                        $libelle = htmlspecialchars($unFrais['libelle']);
                        $quantite = $unFrais['quantite']; ?>
                        <div class="form-group">
                            <label for="idFrais"><?php echo $libelle ?></label>
                            <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>" class="form-control">
                        </div>
                    <?php
                    }
                    ?>
                    <button class="btn btn-success" name="corriger[<?php echo $idFrais ?>]" type="submit">Corriger</button>
                    <button class="btn btn-danger" type="reset">Réinitialiser</button>
                </fieldset>
            </form>
        </div>
    </div>

    <hr>

    <form method="post" action="index.php?uc=validerFrais&action=CorrigerElemHorsForfait" role="form">
        <div class="panel panel-info">
            <div class="panel-heading">Eléments hors-forfait</div>
            <table class="table table-bordered table-responsive">
                <tr>
                    <th>Date</th>
                    <th>Libelle</th>
                    <th>Montant</th>
                    <th></th>
                </tr>
                <?php
                foreach ($lesFraisHorsForfait as $frais) {
                    $date = $frais['date'];
                    $datee = Utilitaires::dateFrancaisVersAnglais($date);
                    $libellehorsFrais = $frais['libelle'];
                    $montant = $frais['montant'];
                    $id = $frais['id'];
                    $pos = strpos($libellehorsFrais, 'REFUSE');
                    if ($pos !== FALSE) {
                ?>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="date"></label>
                                    <input type="text" name="lesDates[<?php echo $id ?>]" size="10" maxlength="15" value="<?php echo $datee ?>" class="form-control" disabled="disabled">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="libelle"></label>
                                    <input type="text" name="lesLibelles[<?php echo $id ?>]" size="15" maxlength="40" value="<?php echo $libellehorsFrais ?>" class="form-control" disabled="disabled">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="montant"></label>
                                    <input type="text" name="lesMontants[<?php echo $id ?>]" size="10" maxlength="15" value="<?php echo $montant ?>" class="form-control" disabled="disabled"> €
                                </div>
                            </td>
                            <td> Pas d'actions possibles après refus</td>
                        <?php } else { ?>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="date"></label>
                                    <input type="date" name="lesDates[<?php echo $id ?>]" size="10" maxlength="15" value="<?php echo $datee ?>" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="libelle"></label>
                                    <input type="text" name="lesLibelles[<?php echo $id ?>]" size="15" maxlength="40" value="<?php echo $libellehorsFrais ?>" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="montant"></label>
                                    <input type="text" name="lesMontants[<?php echo $id ?>]" size="10" maxlength="15" value="<?php echo $montant ?>" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="container-fluid mt-20">
                                    <input id="okElemHorsForf" name="corriger[<?php echo $id ?>]" type="submit" value="Corriger" class="btn btn-success" accept="" role="button">
                                    <a href="index.php?uc=validerFrais&action=supprimerFrais&idFrais=<?php echo $id ?>&mois=<?php echo $frais['mois'] ?>&idVisiteur=<?php echo $_SESSION['idVisi'] ?> " type="reset" class="btn btn-danger" role="button" onclick="return confirm('Voulez-vous vraiment supprimer ou reporter ce frais hors forfait?');">Réinitialiser</a>
                                </div>
                            </td>
                        <?php } ?>
                        </tr>
                    <?php } ?>
            </table>
        </div>
        <div class="row">
            <div class="col-md-2">
                <p>Nombre de justificatifs : </p>
            </div>
            <div class="col-md-1">
                <input type="texte" name="nbJust" size="1" maxlength="15" value="<?php echo $lesInfosFicheFrais['nbJustificatifs'] ?>" class="form-control">
            </div>
        </div>
    </form>
    <form method="post" action="index.php?uc=validerFrais&action=Valider" role="form">
        <input id="okFicheFrais" type="submit" value="Valider" class="btn btn-success" accept="" role="button" onclick="return confirm('Voulez-vous vraiment valider cette fiche de frais ?');">
    </form>
    </br></br>
<?php } else { ?>
    <div class="row">
        <h3>Eléments forfaitisés</h3>
        <div class="col-md-4">
            <form method="post" role="form">
                <fieldset>
                    <?php
                    foreach ($lesFraisForfait as $unFrais) {
                        $idFrais = $unFrais['idfrais'];
                        $libelle = htmlspecialchars($unFrais['libelle']);
                        $quantite = $unFrais['quantite']; ?>
                        <div class="form-group">
                            <label for="idFrais"><?php echo $libelle ?></label>
                            <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>" class="form-control" disabled="disabled">
                        </div>
                    <?php
                    }
                    ?>
                </fieldset>
            </form>
        </div>
    </div>

    <hr>

    <form method="post" role="form">
        <div class="panel panel-info">
            <div class="panel-heading">Eléments hors-forfait</div>
            <table class="table table-bordered table-responsive">
                <tr>
                    <th>Date</th>
                    <th>Libelle</th>
                    <th>Montant</th>
                </tr>
                <?php
                foreach ($lesFraisHorsForfait as $frais) {
                    $date = $frais['date'];
                    $datee = Utilitaires::dateFrancaisVersAnglais($date);
                    $libellehorsFrais = $frais['libelle'];
                    $montant = $frais['montant'];
                    $id = $frais['id'];
                ?>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="date"></label>
                                <input type="date" name="lesDates[<?php echo $id ?>]" size="10" maxlength="15" value="<?php echo $datee ?>" class="form-control" disabled="disabled">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="libelle"></label>
                                <input type="text" name="lesLibelles[<?php echo $id ?>]" size="15" maxlength="40" value="<?php echo $libellehorsFrais ?>" class="form-control" disabled="disabled">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="montant"></label>
                                <input type="text" name="lesMontants[<?php echo $id ?>]" size="10" maxlength="15" value="<?php echo $montant ?>" class="form-control" disabled="disabled">
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="row">
            <div class="col-md-2">
                <p>Nombre de justificatifs : </p>
            </div>
            <div class="col-md-1">
                <input type="texte" name="nbJust" size="1" maxlength="15" value="<?php echo $lesInfosFicheFrais['nbJustificatifs'] ?>" class="form-control" disabled="disabled">
            </div>
        </div>
    </form>
    <form method="post" action="index.php?uc=suivrePaiement&action=Valider" role="form">
        <input id="okFicheFrais" type="submit" value="Mettre en Paiement" class="btn btn-success" accept="" role="button" onclick="return confirm('Voulez-vous vraiment mettre en paiement cette fiche de frais ?');">
    </form></br></br>
<?php
}
