<?php
$maxLength = 0;
foreach ($ue['bloccompetences'] as $item) {
    if (isset($item['competences']) && is_array($item['competences'])) {
        $currentLength = count($item['competences']);
        if ($currentLength > $maxLength) {
            $maxLength = $currentLength;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Syllabus Formation Ingénieur</title>
    <link rel="stylesheet" href="../public/css/syllabus.css">

</head>
<body>
    <div class="page">
        <table class="header">
            <tr>
                <td><b> <?php echo htmlspecialchars($ue["code"] . " " . $ue["libelle"], ENT_QUOTES, 'UTF-8') ?></b> </td>
                <td> <b> <?php echo htmlspecialchars($ue["cycleenseignement"], ENT_QUOTES, 'UTF-8') ?></b> </td>
                <td> <b> <?php echo htmlspecialchars($ue["semestre"],ENT_QUOTES, 'UTF-8') ?></b> </td>
            </tr>
            <?php if ($showRespoInfo) : ?>
            <tr>
                <td>responsable de l'UE : <?php echo $ue['responsable'] ? htmlspecialchars(str_replace(['.'], ' ', $ue['responsable'][0]['username']), ENT_QUOTES, 'UTF-8') : '' ?> </td>
                <td colspan="2">contact : <?php echo $ue['responsable'] ? htmlspecialchars($ue['responsable'][0]['email'], ENT_QUOTES, 'UTF-8') : '' ?> </td>
                
            </tr>
            <?php endif; ?>
        </table>
        <table class="layout-table">
            <tr>
                <td><h4>Pourquoi cette UE ?</h4></td>
                <td><h4>Eléménts constitutifs de l'UE</h4></td>
            </tr>
            <tr>
                <td class="ue--partie1">      
                    <p> <?php echo htmlspecialchars($ue['description'] ?? '', ENT_QUOTES, 'UTF-8') ?> </p>
                </td>
                <td width="50%">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 80%;"></th>
                                <th style="font-size: 14px;"><small>coefficient</small> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalHeuresEncadrees = 0;
                            $totalHeuresAutonomies = 0;
                            foreach ($ue['matiereenseignee'] as $index => $ecue) : ?>
                            <tr style="background-color: white;">
                                <td style="font-size: 11px;"> <small><?php echo htmlspecialchars($ue["code"] . "-" . ($index + 1) . " " . $ecue["libelle"], ENT_QUOTES, 'UTF-8') ?></small> </td>
                                <td  class="coefficient"><small> <b> <?php echo htmlspecialchars($ecue['coefficient'] ?? '', ENT_QUOTES, 'UTF-8') ?></b> </small></td>
                            </tr>
                                <?php
                                $totalHeuresEncadrees += $ecue['nbhcours'] + $ecue['nbhcourstd'] + $ecue['nbhtd'] + $ecue['nbhtp'] + $ecue['nbhprojet'] + $ecue['nbhautre'] + $ecue['nbhcontrole'];
                                $totalHeuresAutonomies += $ecue['nbhautonomie'];
                            endforeach;?>
                        </tbody>
                    </table>

                    <table style="width: 100%;" >
                        <tr>
                            <th style="font-size: 12px;"><small>Volume d'heures d'enseignement encadré</small></th>
                            <th style="font-size: 12px;"><small>Volume d'heures de travail personnel</small></th>
                            <th style="font-size: 12px;"><small>Nombre d'ECTS</small></th>
                        </tr>
                        <tr style="background-color: white; text-align: center;">
                            <td style="text-align: center;"><?php echo htmlspecialchars($totalHeuresEncadrees, ENT_QUOTES, 'UTF-8') ?></td>
                            <td style="text-align: center;"><?php echo htmlspecialchars($totalHeuresAutonomies, ENT_QUOTES, 'UTF-8') ?></td>
                            <td style="text-align: center;"> <?php echo htmlspecialchars($ue['ects'], ENT_QUOTES, 'UTF-8') ?> </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <hr/>
        <small style="margin:0; font-size:10px;">Alignement curriculaire</small>
        <h4 style="margin-top:0;">Parmi les compétences visées par la formation, lesquelles sont développées dans cette UE ?</h4>
        
        <div class="competence">
        

            <table style="border-collapse: collapse;" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="85%">
                        <table style="border-collapse: collapse;" cellspacing="0" cellpadding="0">
                            <tr>
                                <?php foreach ($ue['bloccompetences'] as $blocCompetence) : ?>
                                <td>
                                    <table class="bloc-comp">
                                        <tr><td class="bloc-header <?= $blocCompetence['actif'] ? 'bloc-actif' : ''?>"><?= $blocCompetence['code'] ?></td></tr>
                                        <?php $index = 0;
                                        foreach ($blocCompetence['competences'] as $competence) :
                                            $index++; ?>
                                            <tr><td class="comp <?php echo htmlspecialchars($competence['etat'], ENT_QUOTES, 'UTF-8') ?>"> <?php echo htmlspecialchars($competence['code'], ENT_QUOTES, 'UTF-8') ?> </td></tr>
                                        <?php endforeach; ?>
                                        <?php for ($i = $index; $i < $maxLength; $i++) :?>
                                            <tr><td class="vide"></td></tr>
                                        <?php endfor; ?>   
                                    </table>
                                </td>
                                
                                <?php endforeach; ?>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align: bottom;" >
                        <table >
                            <tr>
                                <td class="label-bc-notactif">BC1</td>
                                <td class=" label-text">L'UE ne contribue pas à ce bloc de compétences</td>
                            </tr>
                            <tr>
                                <td class="label-bc-actif">BC1</td>
                                <td class="label-text">L'UE contribue à ce bloc de compétences</td>
                            </tr>
                            <tr>
                                <td class="label-c-nad">C1</td>
                                <td class=" label-text">Compétence non adressée dans cette UE</td>
                            </tr>
                            <tr>
                                <td class="label-c-meo">C1</td>
                                <td class=" label-text">Compétence mise en œuvre dans cette UE</td>
                            </tr>
                            <tr>
                                <td class="label-c-ens">C1</td>
                                <td class=" label-text">Compétence enseignée dans cette UE</td>
                            </tr>
                            <tr>
                                <td class="label-c-eva">C1</td>
                                <td class=" label-text">Compétence évaluée dans cette UE</td>
                            </tr>
                            <tr>
                                <td class="label-c-enseva">C1</td>
                                <td class=" label-text">Compétence enseignée et évaluée dans cette UE</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table> 
        </div>
    </div>
    
    <?php
    $i = 0;
    $count = count($ue['matiereenseignee']);
    foreach ($ue['matiereenseignee'] as $index => $ecue) :
        $i++; ?>
        <div class="page">
            <table class="header">
                <tr>
                    <td colspan="2"> <small> <b> <?php echo htmlspecialchars($ue["code"] . " " . $ue["libelle"], ENT_QUOTES, 'UTF-8') ?></b> </small> </td>
                    <td> <small> <b> <?php echo htmlspecialchars($ue["cycleenseignement"], ENT_QUOTES, 'UTF-8') ?></b> </small> </td>  
                </tr>
                <tr>
                    <td colspan="2"> <b> <?php echo htmlspecialchars($ue["code"] . "-" . ($index + 1) . " " . $ecue["libelle"], ENT_QUOTES, 'UTF-8') ?> </b></td>
                    <td> <b> <?php echo htmlspecialchars($ue["semestre"], ENT_QUOTES, 'UTF-8') ?></b> </td>
                </tr>
                <?php if ($showRespoInfo) : ?>
                <tr>
                    <td>responsable de l'ECUE : <?= $ecue['responsable'] ? htmlspecialchars(str_replace(['.'], ' ', $ecue['responsable'][0]['username']), ENT_QUOTES, 'UTF-8') : '' ?> </td>
                    <td colspan="2">contact : <?= $ecue['responsable'] ? htmlspecialchars($ecue['responsable'][0]['email'] ?? '', ENT_QUOTES, 'UTF-8') : '' ?> </td>     
                </tr>
                <?php endif; ?>
            </table>         

            <table class="layout-table">
                <tr>
                    <td ><h4>Contexte et enjeux de l'enseignement</h4></td>
                    <td><h4>Prise en compte des dimensions socio-environnementales</h4></td>
                    <td> <h4>Modalités d'enseignement et d'évaluation</h4> </td>
                </tr>
                <tr>
                    <td style="background-color: white;width:37%;vertical-align: baseline;font-size:12px">
                        <?= htmlspecialchars($ecue['contexte'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td> 
                        <table style="width:100%;" cellpadding=0 cellspacing=0>
                            <tr><td style="background-color: white;height:90px;">
                                <?php foreach ($ecue['socioenvdimension'] as $onu) : ?>
                                    <span class="onu"><?= htmlspecialchars($onu, ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endforeach;  ?>
                            </td></tr>
                            <tr><td> <h4>Prérequis</h4></td></tr>
                            <tr><td style="background-color: white;height:50px;vertical-align: baseline;font-size:12px;">
                                <?= htmlspecialchars($ecue['prerequis'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </td></tr>
                        </table>
                    </td>
                    <td width="30%">
                        <table style="width: 100%;font-size:10px">
                            <tr>
                                <th style="width: 80%;"></th>
                                <th><small>Nb d'heures</small></th>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>Cours</small></td>
                                <td class="coefficient"> <?= (is_numeric($ecue['nbhcours']) && floor($ecue['nbhcours']) == $ecue['nbhcours']) ? number_format($ecue['nbhcours'], 0) : $ecue['nbhcours'] ?> </td>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>Cours intégré (cours + TD)</small></td>
                                <td class="coefficient"> <?= (is_numeric($ecue['nbhcourstd']) && floor($ecue['nbhcourstd']) == $ecue['nbhcourstd']) ? number_format($ecue['nbhcourstd'], 0) : $ecue['nbhcourstd'] ?> </td>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>TD</small></td>
                                <td class="coefficient"> <?= (is_numeric($ecue['nbhtd']) && floor($ecue['nbhtd']) == $ecue['nbhtd']) ? number_format($ecue['nbhtd'], 0) : $ecue['nbhtd'] ?> </td>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>TP</small></td>
                                <td class="coefficient"> <?= (is_numeric($ecue['nbhtp']) && floor($ecue['nbhtp']) == $ecue['nbhtp']) ? number_format($ecue['nbhtp'], 0) : $ecue['nbhtp'] ?> </td>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>Projets</small></td>
                                <td class="coefficient"> <?=(is_numeric($ecue['nbhprojet']) && floor($ecue['nbhprojet']) == $ecue['nbhprojet']) ? number_format($ecue['nbhprojet'], 0) : $ecue['nbhprojet']?> </td>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>Travail en autonomie encadré</small></td>
                                <td class="coefficient"> <?= (is_numeric($ecue['nbhautre']) && floor($ecue['nbhautre']) == $ecue['nbhautre']) ? number_format($ecue['nbhautre'], 0) : $ecue['nbhautre'] ?> </td>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>Contrôles et soutenances</small></td>
                                <td class="coefficient"> <?= (is_numeric($ecue['nbhcontrole']) && floor($ecue['nbhcontrole']) == $ecue['nbhcontrole']) ? number_format($ecue['nbhcontrole'], 0) : $ecue['nbhcontrole'] ?> </td>
                            </tr>
                            <tr style="background-color: white;">
                                <td ><small>Travail personnel</small></td>
                                <td class="coefficient"> <?=  (is_numeric($ecue['nbhautonomie']) && floor($ecue['nbhautonomie']) == $ecue['nbhautonomie']) ? number_format($ecue['nbhautonomie'], 0) : $ecue['nbhautonomie'] ?> </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>

            <hr>
            <table class="layout-table">
                <tr>
                    <td style="padding:0"><h4>Objectifs pédagogiques</h4></td>
                    <td style="padding:0"><h4>Activités</h4></td>
                    <td style="padding:0"><h4>Évaluations et retours faits aux élèves</h4></td>
                </tr>
                <tr style="font-size:4px">
                    <td style="font-size:10px;padding:0"><small>(à la fin de cet enseignement, l'étudiant sera capable de …)</small></td>
                    <td style="font-size:10px;padding:0"><small>(CM, TD, TP, projet, sortie terrain, etc. )</small></td>
                    <td style="font-size:10px;padding:0"><small>(évaluations qui comptent pour la note ou qui permettent à l'étudiant de se situer, corrigés, feedback personnalisé...)</small></td>
                </tr>
                <tr>
                    <td style="background-color:white;width: 33%;vertical-align: baseline;font-size:13px;">
                        <?= nl2br(htmlspecialchars($ecue['objectif'], ENT_QUOTES, 'UTF-8')) ?>
                    </td>
                    <td style="background-color:white;width: 33%;vertical-align: baseline;font-size:13px;">
                        <?= nl2br(htmlspecialchars($ecue['activites'], ENT_QUOTES, 'UTF-8')) ?>
                    </td>
                    <td style="background-color:white;width: 33%;vertical-align: baseline;font-size:13px;">
                        <?= nl2br(htmlspecialchars($ecue['evaluation'], ENT_QUOTES, 'UTF-8')) ?>
                    </td>
                </tr>
            </table>
     
            
        
        </div>
        <div class="<?= ($i == $count) ? '' : 'page' ?>">
            <table class="header">
                <tr>
                    <td colspan="2"> <small> <b> <?= htmlspecialchars($ue["code"] . " " . $ue["libelle"], ENT_QUOTES, 'UTF-8') ?></b> </small> </td>
                    <td> <small> <b> <?= htmlspecialchars($ue["cycleenseignement"], ENT_QUOTES, 'UTF-8') ?></b> </small> </td>  
                </tr>
                <tr>
                    <td colspan="2"> <b> <?= htmlspecialchars($ue["code"] . "-" . ($index + 1) . " " . $ecue["libelle"], ENT_QUOTES, 'UTF-8') ?> </b></td>
                    <td> <b> <?= $ue["semestre"] ?></b> </td>
                </tr>
                <?php if ($showRespoInfo) : ?>
                    <tr>
                    <td>responsable de l'ECUE : <?= $ecue['responsable'] ? htmlspecialchars(str_replace(['.'], ' ', $ecue['responsable'][0]['username']), ENT_QUOTES, 'UTF-8') : '' ?> </td>
                    <td colspan="2">contact : <?= $ecue['responsable'] ? htmlspecialchars($ecue['responsable'][0]['email'], ENT_QUOTES, 'UTF-8') : '' ?> </td>                    
                    </tr>
                <?php endif; ?>
            </table>
            
            <h4 style="margin-bottom:5">Plan de cours</h4> 
            <p style="background-color:white; padding:10px;height:150px;margin:0;font-size:11.5px">
                <?= nl2br(htmlspecialchars($ecue["plandecours"], ENT_QUOTES, 'UTF-8')) ?>
            </p>
                                
            <h4 style="margin-bottom:5">Ressources et références</h4>
            <p style="background-color:white; padding:10px;max-height:360px;margin:0;font-size:11px">
                <?= nl2br(htmlspecialchars($ecue["ressourcereference"], ENT_QUOTES, 'UTF-8')) ?>
            </p>
            

        </div>

    <?php endforeach; ?>

    <div class="page">
</body>
</html>


<?php
$footer = '
        <table class="footer">
            <tr>
                <td class="footer-logo">
                    <img style="width:70px;" src ="./public/img/logo-imt.svg"/>
                </td>
                <td >
                    <table style="border-collapse: collapse; width: 100%;" >
                        <tr>
                            <td class="footer-label">SYLLABUS FORMATIONS INGÉNIEUR ANNÉE ' . htmlspecialchars($ue["anneescolaire"], ENT_QUOTES, 'UTF-8') .' </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
        </table>';
?>

