<h2>Mes fiches de frais</h2>
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner un visiteur : </h3>
    </div>
    <div class="col-md-4">
        <form action="index.php?uc=validerFrais&action=selectionMois" method="post" role="form">
            <div class="form-group">
                <label for="lstVisiteur" accesskey="n">Visiteurs : </label>
                <select id="lstVisiteur" name="lstVisiteur" class="form-control">
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $id = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                    ?>
                        <option value="<?php echo $id ?>" selected="true">
                            <?php echo $nom . ' ' . $prenom ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <input id="ok" type="submit" value="Valider" class="btn btn-success" role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" role="button">
        </form>
    </div>
</div>