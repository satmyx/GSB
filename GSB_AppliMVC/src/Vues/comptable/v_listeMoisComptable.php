<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="col-md-1">
    <h4>mois : </h4>
</div>
<div class="col-md-2">
    <form action="index.php?uc=validerFrais&action=voirEtatFrais" method="post" role="form">
        <div class="form-group">
            <select id="lstMois" name="lstMois" onchange="submit();" class="form-control">
                <option value="ChoixMois" selected>Choisir un mois</option>
                <?php
                foreach ($lesMois as $unMois) {
                    $mois = $unMois['mois'];
                    $numAnnee = $unMois['numAnnee'];
                    $numMois = $unMois['numMois'];
                    if ($mois == $moisASelectionner) {
                ?>
                        <option selected value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                    <?php
                    } else {
                    ?>
                        <option value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
    </form>
</div>
</div>