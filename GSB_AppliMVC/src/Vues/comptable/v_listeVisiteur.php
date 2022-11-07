<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2>Valider la fiche de frais</h2>
<div class="row">
  <div class="col-md-2">
    <h4>Choisir le visiteur : </h4>
  </div>
  <div class="col-md-2">
    <form action="index.php?uc=validerFrais&action=selectionnerMois" 
          method="post" role="form">
      <div class="form-group">
        <select id="lstVisiteurs" name="lstVisiteurs" onchange="submit();" class="form-control">
          <option value="Choix" selected>Choisir un visiteur</option>
          <?php
          foreach ($lesVisiteurs as $unVisiteur) {
              $id_vst = $unVisiteur['id'];
              $nom_vst = $unVisiteur['nom'];
              $prenom_vst = $unVisiteur['prenom'];
              ?> 
              <option value="<?php echo $id_vst ?>">
              <?php echo $nom_vst . ' ' . $prenom_vst ?> </option> 
              <?php
            }
          ?>   
        </select>     
      </div>
    </form> 
  </div>
