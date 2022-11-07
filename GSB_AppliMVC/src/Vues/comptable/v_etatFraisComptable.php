
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
?>

<hr>
<form method="post" 
      action="index.php?uc=validerFrais&action=CorrigerElemHorsForfait" 
      role="form">
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
        $datee = implode('-', array_reverse(explode('/', $date))); /* transforme une date fr en une date us -> 29/10/2020 en 2020-10-29 */
        $libellehorsFrais = $frais['libelle'];
        $montant = $frais['montant'];
        $id = $frais['id'];
        $pos = strpos($libellehorsFrais, 'REFUSE');
        if ($pos !== FALSE) {
          ?>
          <tr>
            <td><div class="form-group">
                <label for="date"></label>
                <input type="text" 
                       name="lesDates[<?php echo $id ?>]"
                       size="10" maxlength="15" 
                       value="<?php echo $datee ?>"
                       disabled="disabled">
              </div></td>
            <td><div class="form-group">
                <label for="libelle"></label>
                <input type="text" 
                       name="lesLibelles[<?php echo $id ?>]"
                       size="15" maxlength="40" 
                       value="<?php echo $libellehorsFrais ?>"
                       disabled="disabled">
              </div></td>
            <td><div class="form-group">
                <label for="montant"></label>
                <input type="text" 
                       name="lesMontants[<?php echo $id ?>]"
                       size="10" maxlength="15" 
                       value="<?php echo $montant ?>"
                       disabled="disabled"> €
              </div></td>
            <td> Pas d'actions possibles après refus</td>
          <?php } else { ?>
          <tr>
            <td><div class="form-group">
                <label for="date"></label>
                <input type="date" 
                       name="lesDates[<?php echo $id ?>]"
                       size="10" maxlength="15" 
                       value="<?php echo $datee ?>">
              </div></td>
            <td><div class="form-group">
                <label for="libelle"></label>
                <input type="text" 
                       name="lesLibelles[<?php echo $id ?>]"
                       size="15" maxlength="40" 
                       value="<?php echo $libellehorsFrais ?>">
              </div></td>
            <td><div class="form-group">
                <label for="montant"></label>
                <input type="text" 
                       name="lesMontants[<?php echo $id ?>]"
                       size="10" maxlength="15" 
                       value="<?php echo $montant ?>"> €
              </div></td>
            <td><input id="okElemHorsForf" name="corriger[<?php echo $id ?>]" type="submit" value="Corriger" class="btn btn-success" 
                       accept=""role="button"> 
              <a href="index.php?uc=validerFrais&action=supprimerFrais&idFrais=<?php echo $id ?>&mois=<?php echo $frais['mois'] ?>&idVisiteur=<?php echo $_SESSION['idVisi'] ?> " 
                 type="reset" class="btn btn-danger" role="button"
                 onclick="return confirm('Voulez-vous vraiment supprimer ou reporter ce frais hors forfait?');">Réinitialiser</a>
            </td>
          <?php } ?>
        </tr>
      <?php } ?>
    </table>
  </div>
</form>
<form method="post" 
      action="index.php?uc=validerFrais&action=Valider" 
      role="form">
  <input id="okFicheFrais" type="submit" value="Valider" class="btn btn-success" 
         accept=""role="button" onclick="return confirm('Voulez-vous vraiment valider cette fiche de frais ?');"> 
</form></br></br>