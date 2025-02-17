<?php
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);
session_start();
$annee=date('Y');
$validationStatus = $lastyearObjectifs = $lastyearSoftSkills = $nextyearObjectifs = $nextYearFormation = $nextYearEvolution = $scores = $discipline = [];


include "db.php";

function get_user($id){
    global $conn; 
    $sql="SELECT * FROM users where id='".$id."'"; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $user_data = [];

    while ($row = $result->fetch_assoc()) {
        $user_data[] = $row;
    }
    return $user_data[0];
}



function get_responsable($id){
    global $conn; 
    $sql="SELECT CONCAT(nom, ' ', prenom) AS full_name FROM users where id='".$id."'"; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $user_data = [];

    while ($row = $result->fetch_assoc()) {
        $user_data[] = $row;
    }
    return $user_data[0];
}

function getLastYearObj($user,$year){
    global $conn; 
    $sql="SELECT id_ligne,objectif,indicateur,realisation,score,resultat_analyse,validation,validation2 FROM objectifs where user='".$user."' and annee=".($year-1)." order by id_ligne"; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getSoftSkills($user,$year){
    global $conn; 
    $sql = "SELECT ss.id_ss, sn.tab_ss, sn.nom_ss, ss.point, ss.score, ss.commentaire, ss.validation, ss.validation2 
    FROM softskills ss JOIN set_softskills sn ON ss.id_ss = sn.id_ss  WHERE ss.user='".$user."' and ss.annee=".($year-1)."";

    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getValidationStatus($user,$year){
    global $conn; 
    $sql="SELECT v.validation1,CONCAT(u.nom, ' ', u.prenom) as 'valideur1',v.validation2,CONCAT(u1.nom, ' ', u1.prenom) as 'valideur2',v.comm_evalue,v.comm_evaluateur FROM validation v 
    JOIN users u ON u.id = v.valideur1  
    JOIN users u1 ON u1.id = v.valideur2  
    where v.user='".$user."' and v.annee=".$year.""; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $validation = [];

    while ($row = $result->fetch_assoc()) {
        $validation[] = $row;
    }
    return $validation[0];
}

function getNextYearObj($user,$year){
    global $conn; 
    $sql="SELECT id_ligne,objectif,indicateur,echeance FROM objectifs where user='".$user."' and annee=".($year).""; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getNextYearFormation($user,$year){
    global $conn; 
    $sql="SELECT id_ligne,formation,obj_formation,priorite FROM formations where user='".$user."' and annee=".($year).""; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getEvolution($user,$year){
    global $conn; 
    $sql="SELECT souhait,motivation,axes,avis  FROM evolution where user='".$user."' and annee=".($year).""; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    if(empty($data)){
        $data = ['souhait'=>1,'motivation'=>'','axes'=>'','avis'=>''];
        return $data;
    }
    return $data[0];
}

function getScores($user,$year){
    global $conn; 
    $sql="SELECT * FROM scores where user='".$user."' and annee=".($year).""; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getDiscipline($user,$year){
    global $conn; 
    $sql="SELECT * FROM discipline where user='".$user."' and annee=".($year-1).""; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data[0];
}
function initEvaluation($year){
    global $id_user,$user,$responsable,$validationStatus,$lastyearObjectifs,$lastyearSoftSkills,$nextyearObjectifs,$nextYearFormation,$nextYearEvolution,$scores,$discipline;
    $id_user=$_SESSION['user_id'];
    $user=get_user($id_user);
    $responsable=get_responsable($user['responsable'])["full_name"];
    $validationStatus=getValidationStatus($id_user,$year);
    $lastyearObjectifs=getLastYearObj($id_user,$year);
    $lastyearSoftSkills=getSoftSkills($id_user,$year);
    $nextyearObjectifs=getNextYearObj($id_user,$year);
    $nextYearFormation=getNextYearFormation($id_user,$year);
    $nextYearEvolution=getEvolution($id_user,$year);
    $scores=!empty(getScores($id_user,$year))?getScores($id_user,$year)[0]:[];
    $discipline=getDiscipline($id_user,$year);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    initEvaluation($annee);
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Evaluation Annuelle Groupe Mfadel</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <div class="banner">
        <img src="MG-logo.png" alt="Logo" width="200" height="100">  
        <div class="user">
            <p class="username names"><?= $user["nom"] . " " . $user["prenom"]; ?></p>

            <form action="logout.php" method="get">
                <input type="submit" class ="deco-btn" value="Se déconnecter">
            </form>
        </div>
    </div>
    <!-- <button id="stock-button"  class="update-button" onclick="downloadPDF()">Imprimer</button> -->

<div class="A4-format">

    <header>        
        <h1>Evaluation Annuelle <?= $annee?></h1>
    </header>

    <main>
        <div class="user-section">
            <div class="user-data">
                <label for="nom">NOM PRENOM  DU COLLABORATEUR :</label>
                <input class="names" type="text" id="nom" value="<?= htmlspecialchars($user['nom'])." ".htmlspecialchars($user['prenom']) ?>" readonly>
            </div>
            <div class="user-data">
                <label for="prenom">DATE D'EMBAUCHE :</label>
                <input type="text" id="prenom" value="<?= htmlspecialchars($user['date_embauche']) ?>" readonly>
            </div>
            <div class="user-data">
                <label for="email"> DEPARTEMENT / DIRECTION :</label>
                <input type="text" id="dir" value="<?= htmlspecialchars($user['direction']) ?>" readonly>
            </div>
            <div class="user-data">
                <label for="login">POSTE OCCUPE:</label>
                <input type="text" id="de" value="<?= htmlspecialchars($user['poste']) ?>" readonly>
            </div>
            <div class="user-data">    
                <label for="login">RESPONSABLE HIERARCHIQUE :</label>
                <input class="names" type="text" id="de" value="<?= $responsable?>" readonly>
            </div>
        </div>
        <span class="validation-span">
                    <center>
                        <?= $validationStatus['validation1']==1?
                        $validationStatus['validation2']==1?($validationStatus['valideur1']==$validationStatus['valideur2']?'Validé par '.$validationStatus['valideur1']:'Validé par '.$validationStatus['valideur1'].' et '.$validationStatus['valideur2']):
                        ' Validé par '.$validationStatus['valideur1']:
                        '<button onclick="sendEval()" class="button-validion">Soumettre mon auto Evaluation</button>'?>
                    </center>
        </span>
        <section class="accordion">
            <div class="accordion-header ">
                <span>I - ANALYSE DE LA PERIODE ECOULEE </span>

                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <div class="section-header">
                    <span>1 - RÉSULTATS OBTENUS PAR RAPPORT AUX OBJECTIFS CONVENUS</span>
                </div>
                <div class="section-detail">
                    <p class="info">Evaluer les réalisations de l'année sur la base des objectifs formalisés en début de période.</p>
                    <p class="info">En décrivant les résultats, il faut se focaliser sur les réalisations et non sur les activités. </p>
                    <table id="objectifs-convenus" border="1">
                        <thead>
                            <tr>
                                <th width="20%">RAPPEL DES OBJECTIFS</th>
                                <th width="40%">INDICATEUR DE MESURE</th>
                                <th width="5%">REALESATION EN %</th>
                                <th width="5%">SCORE</th>
                                <th width="35%">COMMENTAIRES DES RESULTATS ET ANALYSE DES ECARTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lastyearObjectifs as $objectif): ?>
                                <tr>
                                    <td><span>Objectif <?= htmlspecialchars(($objectif['id_ligne']+1))?> : </span><span><?= htmlspecialchars($objectif['objectif'])?></span></td>
                                    <td><?= htmlspecialchars($objectif['indicateur']) ?></td>
                                    <td>
                                        <input <?= $validationStatus['validation1']==1?'readonly':''?> class="input-objectfs-avant centred" type="number" placeholder="0 - 100" value="<?= $objectif['realisation']?>"  id="obj-realisation-<?= htmlspecialchars(($objectif['id_ligne']+1))?>">
                                    </td>
                                    <td class="td-score">
                                        <select  <?= $validationStatus['validation1']==1?'disabled':''?> name="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>" id="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>">
                                            <option  <?= $objectif['score'] == "1" ? "selected" : "" ?> value="1">1 - Résultats insuffisants</option>
                                            <option  <?= $objectif['score'] == "2" ? "selected" : "" ?> value="2">2 - Objectifs partiellement réalisés</option>
                                            <option  <?= $objectif['score'] == "3" ? "selected" : "" ?> value="3">3 - Objectifs atteints</option>
                                            <option  <?= $objectif['score'] == "4" ? "selected" : "" ?> value="4">4 - Objectifs  dépassés</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea <?= $validationStatus['validation1']==1?'readonly':''?> class="input-objectfs-avant" placeholder="En attente d'évaluation"  id="obj-commentaire-<?= htmlspecialchars(($objectif['id_ligne']+1))?>"><?= $objectif['resultat_analyse'] ? $objectif['resultat_analyse'] : "" ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; ?>


                        </tbody>
                    </table>
                    <?php if (empty($lastyearObjectifs)): ?>
                                <p>Aucun objectif disponible pour cette année.</p>
                    <?php endif; ?>
                </div>
                <div class="section-header">
                    <span>2- FORMATION DE L'ANNEE ECOULEE</span>
                </div>
                <div class="section-detail">
                    <span>A faire Plus tard</span>
                </div>
                <div class="section-header">
                    <span>3- EVALUATION DES SOFT SKILLS</span>
                </div>
                <div class="section-detail">
                    <p class="info">A- Soft Skills :  </p>
                    <p class="info">Méthodologie proposée : à chaque critère correspondent des définitions permettant de mieux échanger au cours de l’entretien.  </p>
                    <p class="info">Il y a neuf critères* qui doivent être passés en revue ; les autres sont en fonction de l’emploi occupé. </p>
                    
                    
                    
                    <table id="ss-convenus-1" border="1">
                        <thead>
                            <tr>
                                <th width="40%">*APTITUDES PROFESSIONNELLES NECESSAIRES DANS L’EMPLOI</th>
                                <th width="100">POINT FORT</th>
                                <th width="50">POINT DE PROGRES</th>
                                <th width="50">SCORE</th>
                                <th width="40%">COMMENTAIRES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $filteredSoftSkillsT1 = array_filter($lastyearSoftSkills, function($softskill) {return $softskill['tab_ss'] == 1;});?>
                            <?php foreach ($filteredSoftSkillsT1 as $softskill): ?>
                                <tr>
                                    <td><span id="ss-<?php echo $softskill['id_ss']; ?>"><?php echo htmlspecialchars($softskill['nom_ss']); ?></span></td>
                                    <td><input <?= $validationStatus['validation1']==1?'disabled':''?> type="radio" class="radio-btn" id="ss-point-fort-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="fort" <?= $softskill['point'] == 'fort' ? 'checked' : '' ?>/></td>
                                    <td><input <?= $validationStatus['validation1']==1?'disabled':''?> type="radio" class="radio-btn" id="ss-point-progret-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="progret" <?= $softskill['point'] == 'progret' ? 'checked' : '' ?>/></td>
                                    <td class="td-score">
                                        <select <?= $validationStatus['validation1']==1?'disabled':''?> name="ss-score-<?php echo $softskill['id_ss']; ?>" id="ss-score-<?php echo $softskill['id_ss']; ?>">
                                            <option value="1" <?php echo $softskill['score'] == 1 ? 'selected' : ''; ?>>1 - Aptitudes inférieures aux exigences attendues</option>
                                            <option value="2" <?php echo $softskill['score'] == 2 ? 'selected' : ''; ?>>2 - Aptitudes partiellements conformes aux exigences attendues</option>
                                            <option value="3" <?php echo $softskill['score'] == 3 ? 'selected' : ''; ?>>3 - Aptitudes conformes aux exigences attendues</option>
                                            <option value="4" <?php echo $softskill['score'] == 4 ? 'selected' : ''; ?>>4 - Aptitudes supérieures aux exigences attendues</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea <?= $validationStatus['validation1']==1?'readonly':''?> class="input-objectfs-avant" id="ss-commentaire-<?php echo $softskill['id_ss']; ?>" name="ss-commentaire-<?php echo $softskill['id_ss']; ?>" placeholder="Commentaire"><?php echo htmlspecialchars($softskill['commentaire']); ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (empty($filteredSoftSkillsT1)): ?>
                                <p>Aucune donnée disponible pour cette année.</p>
                    <?php endif; ?>


                    <table id="ss-convenus-2" border="1">
                        <thead>
                            <tr>
                                <th width="40%">*COMPORTEMENT DANS L’EMPLOI </th>
                                <th width="100">POINT FORT</th>
                                <th width="50">POINT DE PROGRES</th>
                                <th width="50">SCORE</th>
                                <th width="40%">COMMENTAIRES</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $filteredSoftSkillsT2 = array_filter($lastyearSoftSkills, function($softskill) {return $softskill['tab_ss'] == 2;});?>
                            <?php foreach ($filteredSoftSkillsT2 as $softskill): ?>
                                <tr>
                                    <td><span id="ss-<?php echo $softskill['id_ss']; ?>"><?php echo htmlspecialchars($softskill['nom_ss']); ?></span></td>
                                    <td><input <?= $validationStatus['validation1']==1?'disabled':''?> type="radio" class="radio-btn" id="ss-point-fort-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="fort" <?= $softskill['point'] == 'fort' ? 'checked' : '' ?>/></td>
                                    <td><input <?= $validationStatus['validation1']==1?'disabled':''?> type="radio" class="radio-btn" id="ss-point-progret-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="progret"  <?= $softskill['point'] == 'progret' ? 'checked' : '' ?>/></td>
                                    <td class="td-score">
                                        <select <?= $validationStatus['validation1']==1?'disabled':''?> name="ss-score-<?php echo $softskill['id_ss']; ?>" id="ss-score-<?php echo $softskill['id_ss']; ?>">
                                            <option value="1" <?php echo $softskill['score'] == 1 ? 'selected' : ''; ?>>1 - Aptitudes inférieures aux exigences attendues</option>
                                            <option value="2" <?php echo $softskill['score'] == 2 ? 'selected' : ''; ?>>2 - Aptitudes partiellements conformes aux exigences attendues</option>
                                            <option value="3" <?php echo $softskill['score'] == 3 ? 'selected' : ''; ?>>3 - Aptitudes conformes aux exigences attendues</option>
                                            <option value="4" <?php echo $softskill['score'] == 4 ? 'selected' : ''; ?>>4 - Aptitudes supérieures aux exigences attendues</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea <?= $validationStatus['validation1']==1?'readonly':''?> class="input-objectfs-avant" id="ss-commentaire-<?php echo $softskill['id_ss']; ?>" name="ss-commentaire-<?php echo $softskill['id_ss']; ?>" placeholder="Commentaire"><?php echo htmlspecialchars($softskill['commentaire']); ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if (empty($filteredSoftSkillsT2)): ?>
                                <p>Aucune donnée disponible pour cette année.</p>
                    <?php endif; ?>

                    <?php if ($user["collaborateurs"]!='NA'): ?>
                        <table id="ss-convenus-3" border="1">
                            <thead>
                                <tr>
                                    <th width="40%">COMPETENCE MANAGERIALE</th>
                                    <th width="100">POINT FORT</th>
                                    <th width="50">POINT DE PROGRES</th>
                                    <th width="50">SCORE</th>
                                    <th width="40%">COMMENTAIRES</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $filteredSoftSkillsT3 = array_filter($lastyearSoftSkills, function($softskill) {return $softskill['tab_ss'] == 3;});?>
                                <?php foreach ($filteredSoftSkillsT3 as $softskill): ?>
                                    <tr>
                                        <td><span id="ss-<?php echo $softskill['id_ss']; ?>"><?php echo htmlspecialchars($softskill['nom_ss']); ?></span></td>
                                        <td><input  <?= $validationStatus['validation1']==1?'disabled':''?> type="radio" class="radio-btn" id="ss-point-fort-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="fort" <?= $softskill['point'] == 'fort' ? 'checked' : '' ?>/></td>
                                        <td><input <?= $validationStatus['validation1']==1?'disabled':''?> type="radio" class="radio-btn" id="ss-point-progret-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="progret" <?= $softskill['point'] == 'progret' ? 'checked' : '' ?>/></td>
                                        <td class="td-score">
                                            <select <?= $validationStatus['validation1']==1?'disabled':''?> name="ss-score-<?php echo $softskill['id_ss']; ?>" id="ss-score-<?php echo $softskill['id_ss']; ?>">
                                                <option value="1" <?php echo $softskill['score'] == 1 ? 'selected' : ''; ?>>1 - Aptitudes inférieures aux exigences attendues</option>
                                                <option value="2" <?php echo $softskill['score'] == 2 ? 'selected' : ''; ?>>2 - Aptitudes partiellements conformes aux exigences attendues</option>
                                                <option value="3" <?php echo $softskill['score'] == 3 ? 'selected' : ''; ?>>3 - Aptitudes conformes aux exigences attendues</option>
                                                <option value="4" <?php echo $softskill['score'] == 4 ? 'selected' : ''; ?>>4 - Aptitudes supérieures aux exigences attendues</option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea <?= $validationStatus['validation1']==1?'readonly':''?> class="input-objectfs-avant" id="ss-commentaire-<?php echo $softskill['id_ss']; ?>" name="ss-commentaire-<?php echo $softskill['id_ss']; ?>" placeholder="Commentaire"><?php echo htmlspecialchars($softskill['commentaire']); ?></textarea>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($filteredSoftSkillsT3)): ?>
                                    <p>Aucune donnée disponible pour cette année.</p>
                        <?php endif; ?>    
                    <?php endif; ?>


                    


                    <?= $validationStatus['validation1']==1?'':
                    '<div class="button-validion-container">
                        <button class="button-validion" onClick="getAutoEval()">Enregistrer mon auto-évaluation</button>
                    </div>'
                    ?>

                </div>
            </div>
            <div class="accordion-header">
                <span>II - OBJECTIFS ET AXE DE DEVELOPPEMENT PROFESSIONNEL DE L'ANNÉE À VENIR  </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <div class="section-header ">
                    <span>1 - NOUVEAUX OBJECTIFS POUR L’ANNEE A VENIR</span>
                </div>
                <div class="section-detail">
                    <button <?= $validationStatus['validation1']==1?'hidden':''?>  <?= $validationStatus['validation1']==1?'disabled':''?> id="addRowBtn">Ajouter Objectif</button>
                    <table id="dynamicTable" class="add-obj" border="1">
                        <thead>
                            <tr>
                                <th>DEFINITION</th>
                                <th>ECHEANCE</th>
                                <th>INDICATEUR DE MESURE</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($nextyearObjectifs)): ?>
                                <tr>
                                    <td width="50%">
                                        <span>Objectif 1 :</span>
                                        <textarea id="input-objectif-1" class="input-objectif" placeholder="Nouvel Objectif"></textarea>
                                    </td>
                                    <td width="10%">
                                        <input id="input-echeance-1" class="input-objectif" placeholder="Échéance">
                                    </td>
                                    <td width="37%">
                                        <textarea id="input-indicateur-1" class="input-objectif" placeholder="Indicateurs de mesure"></textarea>
                                    </td>
                                    <td width="3%"></td>
                                </tr>
                            <?php else: ?>
                            <?php foreach ($nextyearObjectifs as $index => $row): ?>
                                <tr>
                                    <td width="50%">
                                        <span>Objectif <?= $index + 1 ?>:</span>
                                        <textarea <?= $validationStatus['validation1']==1?'disabled':''?>  id="input-objectif-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['objectif']) ?></textarea>
                                    </td>
                                    <td width="10%">
                                        <input <?= $validationStatus['validation1']==1?'disabled':''?>  id="input-echeance-<?= $index + 1 ?>" class="input-objectif" value="<?= htmlspecialchars($row['echeance']) ?>">
                                    </td>
                                    <td width="37%">
                                        <textarea <?= $validationStatus['validation1']==1?'disabled':''?>  id="input-indicateur-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['indicateur']) ?></textarea>
                                    </td>
                                    <td width="3%">
                                        <button <?= $validationStatus['validation1']==1?'disabled':''?> onclick="removeRow(this)">X</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-header ">
                    <span>2- SOUHAITS DE FORMATION DU COLLABORATEUR</span>
                </div>
                <div class="section-detail">
                    <button <?= $validationStatus['validation1']==1?'hidden':''?> <?= $validationStatus['validation1']==1?'disabled':''?> id="addRowBtnFormation" >Ajouter formation</button>
                    <table id="dynamicTableFormation" class="add-obj" border="1">
                        <thead>
                            <tr>
                                <th>THEMES DES FORMATIONS</th>
                                <th>OBJECTIFS</th>
                                <th>Priorité</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($nextYearFormation)): ?>
                                <tr>
                                    <td width="45%">
                                        <textarea id="input-formation-1"  class="input-objectif" placeholder="Formation" ></textarea>
                                    </td>
                                    <td width="45%">
                                        <textarea id="input-obj-formation-1"  class="input-objectif" placeholder="Objectif de la formation" ></textarea>
                                    </td>
                                    <td width="7%" class="td-priorite">
                                        <select  name="formation-priorite-1" id="formation-priorite-1" class="input-objectfs-avant">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </td>
                                    <td width="3%"></td>
                                </tr>
                            <?php else: ?>
                            <?php foreach ($nextYearFormation as $index => $row): ?>
                                <tr>
                                    <td width="45%">
                                        <textarea <?= $validationStatus['validation1']==1?'disabled':''?>  id="input-formation-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['formation']) ?></textarea>
                                    </td>
                                    <td width="45%">
                                        <textarea <?= $validationStatus['validation1']==1?'disabled':''?>  id="input-obj-formation-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['obj_formation']) ?></textarea>
                                    </td>
                                    <td width="7%">
                                        <select  <?= $validationStatus['validation1']==1?'disabled':''?> name="formation-priorite-<?= $index + 1 ?>" id="formation-priorite-<?= $index + 1 ?>" class="input-objectfs-avant">
                                            <option <?= $row['priorite']==1?'selected':''?> value="1">1</option>
                                            <option <?= $row['priorite']==2?'selected':''?> value="2">2</option>
                                            <option <?= $row['priorite']==3?'selected':''?> value="3">3</option>
                                        </select>
                                    </td>
                                    <td width="3%">
                                        <button <?= $validationStatus['validation1']==1?'disabled':''?> onclick="removeRowformation(this)">X</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="section-header ">
                    <span>3 - EVOLUTION DE CARRIERE </span>
                </div>
                <div class="section-detail">
                    <div class="souhait-container">
                        <span class="span-souhait">Souhaits du collaborateur : <span id='choix-evolution'><?php echo $nextYearEvolution["souhait"]?$nextYearEvolution["souhait"]:'1'?></span></span>
                        
                        <div class="souhait-choice">
                            <button <?= $validationStatus['validation1']==1?'disabled':''?> class="choice-btn<?= $nextYearEvolution["souhait"]=="1"?' selected-choice':''?>">1-  Maintien dans le même poste</button>
                            <button <?= $validationStatus['validation1']==1?'disabled':''?> class="choice-btn<?= $nextYearEvolution["souhait"]=="2"?' selected-choice':''?>">2-  Evolution dans le même département </button>
                            <button <?= $validationStatus['validation1']==1?'disabled':''?> class="choice-btn<?= $nextYearEvolution["souhait"]=="3"?' selected-choice':''?>">3- Changement de poste hors département </button>
                        </div>
                    </div>  
                    <div class="motivation-container">
                        <span>Motivations du collaborateur</span>
                        <textarea <?= $validationStatus['validation1']==1?'disabled':''?> class="input-objectif" name="motivation" id="motivation" cols="30" rows="10" placeholder="Indiquez les motivations et aptitudes mises en avant pour justifier vos souhaits"><?php echo $nextYearEvolution["motivation"]?></textarea>
                    </div>
                    <div class="motivation-container">
                        <span>Axes d'amélioration attendus</span>
                        <textarea <?= $validationStatus['validation1']==1?'disabled':''?> class="input-objectif" name="axes" id="axes" cols="30" rows="10" placeholder="Accompagnement et supervision"><?php echo $nextYearEvolution["axes"]?></textarea>
                    </div>
                    <div class="motivation-container">
                        <span>Avis du responsable hiérarchique</span>
                        <textarea disabled class="input-objectif" name="avis-motivation" id="avis-motivation" cols="30" rows="10" placeholder="sur les souhaits exprimés, sur les potentialités à moyen terme (1 – 2 ans), etc…
Actions d’accompagnement envisagées (formation, mise en situation, tutorat, …)."><?php echo $nextYearEvolution["avis"]?></textarea>
                    </div>
                </div>
                <?= $validationStatus['validation1']==1?'':
                    '<div class="button-validion-container">
                        <button class="button-validion" onClick="getObjectif()">Enregistrer mes objectifs</button>
                    </div>'
                ?>

            </div>
            <div class="accordion-header">
                <span>III- DISCIPLINE (pendant l'année <?= $annee-1?>) </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <div class="section-detail">
                    <p class="info">sera rempli par le département capital humain (récapitulatif des retards, absences non justifiées, sanctions disiciplinaires)</p>
                    <table class="discipline-table" border="1">
                        <thead>
                            <tr>
                                <th width="20%">Nombre de sanctions</th>
                                <th width="80%">Objet de sanctions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><textarea class="input-objectif" type="text" id="discipline-Nbsanctions" cols="30" rows="5" disabled><?php echo $discipline["nb_sanctions"]?></textarea></td>
                                <td><textarea class="input-objectif" type="text" id="discipline-Obsanctions"  cols="30" rows="5" disabled><?php echo $discipline["obj_sanctions"]?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="discipline-table" border="1">
                        <thead>
                            <tr>
                                <th width="20%">Discipline et assiduité</th>
                                <th width="80%">Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><textarea class="input-objectif" type="text" id="discipline-assiduite" cols="30" rows="5" disabled><?php echo $discipline["assiduite"]?></textarea></td>
                                <td><textarea class="input-objectif" type="text" id="discipline-commentaire"  cols="30" rows="5" disabled><?php echo $discipline["commentaire"]?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="accordion-header">
                <span>IV- SYNTHESE GENERALE  </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <table  id="synthese" class="synthese" border="1">
                    <thead>
                        <tr>
                            <th> </th>
                            <th>SCORE</th>
                            <th>Pondération ( % ) </th>
                            <th>Note</th>
                            <th>Note générale</th>
                            <th>Objectif</th>
                            <th>Résultat Final en %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ATTEINTE DES OBJECTIFS</td>
                            <td id="score-1"><?= $scores['s1']?></td>
                            <td><input type="text" disabled class="input-ponderation" id="ponderation-1" value="<?= $scores['p1']?>"></td>
                            <td id="note-1"><?= $scores['n1']?></td>
                            <td rowspan="<?=$user["collaborateurs"]=='NA'?'3':'4'?>" id="note-generale"><?= $scores['ng']?></td>
                            <td rowspan="<?=$user["collaborateurs"]=='NA'?'3':'4'?>"><input type="text" disabled class="input-ponderation-o" id="note-objectif" value="<?= $scores['obj']?>"></td>
                            <td rowspan="<?=$user["collaborateurs"]=='NA'?'3':'4'?>" id="resultat-final"><?= $scores['finale']?>%</td>
                        </tr>
                        <tr>
                            <td>APTITUDES PROFESSIONNELLES</td>
                            <td id="score-2"><?= $scores['s2']?></td>
                            <td><input disabled type="text" class="input-ponderation" id="ponderation-2" value="<?= $scores['p2']?>"></td>
                            <td id="note-2"><?= $scores['n2']?></td>

                        </tr>
                        <tr>
                            <td>COMPORTEMENT</td>
                            <td id="score-3"><?= $scores['s3']?></td>
                            <td><input type="text" disabled class="input-ponderation" id="ponderation-3" value="<?= $scores['p3']?>"></td>
                            <td id="note-3"><?= $scores['n3']?></td>

                        </tr>
                        <?php if ($user["collaborateurs"]!='NA'): ?>
                            <tr>
                                <td>COMPETENCES MANAGERIALES</td>
                                <td id="score-4"><?= $scores['s4']?></td>
                                <td><input type="text" disabled class="input-ponderation" id="ponderation-4" value="<?= $scores['p4']?>"></td>
                                <td id="note-4"><?= $scores['n4']?></td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
                                <button hidden onclick="calulateScore()" class="button-validion">calculer</button>
            </div>
            <div class="accordion-header">
                <span>V- CONCLUSION DE L'APPRECIATION  </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <div class="motivation-container">
                    <span>Commentaire Collaborateur</span>
                    <textarea <?= $validationStatus['validation1']==1?'disabled':''?> class="input-objectif" name="com-evalue" id="com-evalue" cols="30" rows="10"><?php echo $validationStatus["comm_evalue"]?></textarea>
                </div>
                <div class="motivation-container">
                    <span>Commentaire Manager</span>
                    <textarea disabled class="input-objectif" name="com-evaluateur" id="com-evaluateur" cols="30" rows="10"><?php echo $validationStatus["comm_evaluateur"]?></textarea>
                </div>
            </div>
        </section>
        
    </main>
</div>
    <footer>
        <div id="snackbar">Some text some message..</div>
    </footer>
</html>

<script>
    const noteObjectifInput = document.querySelector('#note-objectif');
    noteObjectifInput.addEventListener('input', () => {
        const values = noteObjectifInput.value;
        if (values < 0 || values > 4) {
            noteObjectifInput.value = '';
            return;
        }

        });

    // function getScore(){
    //     const nbObjectifs = document.getElementById('objectifs-convenus').querySelector('tbody').rows.length;
    //     let scoreObj =0
    //     for (let i = 1; i <= nbObjectifs; i++) {
    //         scoreObj = scoreObj + parseInt(document.getElementById(`obj-score-${i}`).value);
    //     }
    //     const score = scoreObj/nbObjectifs;
    //     document.getElementById(`score-1`).textContent=score.toFixed(2);

    //     const nbSoftSkills1 = document.getElementById('ss-convenus-1').querySelector('tbody').rows.length;
    //     let scoreSS1 =0
    //     for (let i = 1; i <= nbSoftSkills1; i++) {
    //         scoreSS1 = scoreSS1 + parseInt(document.getElementById(`ss-score-${i}`).value);
    //     }
    //     const SS1score = scoreSS1/nbSoftSkills1;
    //     document.getElementById(`score-2`).textContent=SS1score.toFixed(2);

    //     const nbSoftSkills2 = document.getElementById('ss-convenus-2').querySelector('tbody').rows.length;
    //     let scoreSS2 =0
    //     for (let i = 1; i <= nbSoftSkills2; i++) {
    //         scoreSS2 = scoreSS2 + parseInt(document.getElementById(`ss-score-${i+nbSoftSkills1}`).value);
    //     }
    //     const SS2score = scoreSS2/nbSoftSkills2;
    //     document.getElementById(`score-3`).textContent=SS2score.toFixed(2);

        
    //     const nbSoftSkills3 = document.getElementById('ss-convenus-3').querySelector('tbody').rows.length;
    //     let scoreSS3 =0
    //     for (let i = 1; i <= nbSoftSkills3; i++) {
    //         scoreSS3 = scoreSS3 + parseInt(document.getElementById(`ss-score-${i+nbSoftSkills2+nbSoftSkills1}`).value);
    //     }
    //     const SS3score = scoreSS3/nbSoftSkills3;
    //     document.getElementById(`score-4`).textContent=SS3score.toFixed(2);

    // }
    


    function calulateScore(){
        const porderations = document.querySelectorAll('.input-ponderation')
        let sum = 0;
        porderations.forEach(field => {
            const fieldValue = parseInt(field.value || 0, 10);
            sum += fieldValue;
        });
        let x = document.getElementById("snackbar");

        if(sum!=100 ){
            x.innerHTML="La somme des pondérations doit être égale à 100"
            x.className = "show error-message";
            setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
            return
        }
        if(document.querySelector('#note-objectif').value=='0'){
            x.innerHTML="Merci de définir la note objectif"
            x.className = "show error-message";
            setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
            return
        }

        const nbObjectifs = document.getElementById('objectifs-convenus').querySelector('tbody').rows.length;
        let scoreObj =0
        let k=0
        for (let i = 1; i <= nbObjectifs; i++) {
            scoreObj = scoreObj + parseInt(document.getElementById(`obj-score-${i}`).value);
        }
        const score = scoreObj/nbObjectifs;
        document.getElementById(`score-1`).textContent=score.toFixed(2);
        let note1=(score.toFixed(2)*parseInt(document.getElementById(`ponderation-1`).value)/100).toFixed(2)
        document.getElementById(`note-1`).textContent=note1;

        const nbSoftSkills1 = document.getElementById('ss-convenus-1').querySelector('tbody').rows.length;
        let scoreSS1 =0
        for (let i = 1; i <= nbSoftSkills1; i++) {
            scoreSS1 = scoreSS1 + parseInt(document.getElementById(`ss-score-${i}`).value);
        }
        const SS1score = scoreSS1/nbSoftSkills1;
        document.getElementById(`score-2`).textContent=SS1score.toFixed(2);
        let note2=(SS1score.toFixed(2)*parseInt(document.getElementById(`ponderation-2`).value)/100).toFixed(2)
        document.getElementById(`note-2`).textContent=note2;

        const nbSoftSkills2 = document.getElementById('ss-convenus-2').querySelector('tbody').rows.length;
        let scoreSS2 =0
        for (let i = 1; i <= nbSoftSkills2; i++) {
            scoreSS2 = scoreSS2 + parseInt(document.getElementById(`ss-score-${i+nbSoftSkills1}`).value);
        }
        const SS2score = scoreSS2/nbSoftSkills2;
        document.getElementById(`score-3`).textContent=SS2score.toFixed(2);
        let note3=(SS2score.toFixed(2)*parseInt(document.getElementById(`ponderation-3`).value)/100).toFixed(2)
        document.getElementById(`note-3`).textContent=note3;

        const element = document.getElementById('ss-convenus-3');
        let note4=0
        if (element) {
            const nbSoftSkills3 = document.getElementById('ss-convenus-3').querySelector('tbody').rows.length;
            let scoreSS3 =0
            for (let i = 1; i <= nbSoftSkills3; i++) {
                scoreSS3 = scoreSS3 + parseInt(document.getElementById(`ss-score-${i+nbSoftSkills2+nbSoftSkills1}`).value);
            }
            const SS3score = scoreSS3/nbSoftSkills3;
            document.getElementById(`score-4`).textContent=SS3score.toFixed(2);
            note4=(SS3score.toFixed(2)*parseInt(document.getElementById(`ponderation-4`).value)/100).toFixed(2)
            document.getElementById(`note-4`).textContent=note4;
        }



        let noteFinale=(parseFloat(note1)+parseFloat(note2)+parseFloat(note3)+parseFloat(note4)).toFixed(2)
        document.getElementById(`note-generale`).textContent=noteFinale;

        let noteObjectifInput = document.querySelector('#note-objectif');
        if(isNaN(noteFinale/noteObjectifInput.value)){
            let x = document.getElementById("snackbar");
            x.innerHTML="Note objectif NON valide !"
            x.className = "show error-message";
            setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
            return;
        }
        let resultat=isNaN(noteFinale/noteObjectifInput.value)?0:(noteFinale/noteObjectifInput.value)*100   
        document.getElementById(`resultat-final`).textContent=resultat.toFixed(2)+'%';
    }


            // const selectScore=document.querySelectorAll('#objectifs-convenus select,#ss-convenus-1 select,#ss-convenus-2 select,#ss-convenus-3 select')
            // selectScore.forEach(select=>{
            //     select.addEventListener('input',()=>{
            //         getScore()
            //     })
            // })
            const inputs = document.querySelectorAll('.input-ponderation');
            inputs.forEach(input => {
            input.addEventListener('click', () => {
                input.value = '';
                });
            input.addEventListener('input', () => {
                const value = input.value;
                if (!/^\d+$/.test(value)) {
                input.value = '';
                return;
                }
                let sum = 0;
                let filledCount = 0;
                inputs.forEach(field => {
                const fieldValue = parseInt(field.value || 0, 10);
                sum += fieldValue;
                if (field.value.trim() !== '') filledCount++;
                });

                // Check if all fields are filled
                if (filledCount === inputs.length) {
                // If all fields are filled and sum is not 100, clear the current input
                if (sum !== 100) {
                    //alert("not 100");
                }
                } else {
                // If the sum exceeds 100 at any point, clear the current input
                if (sum > 100) {
                    input.value = '';
                }
                }
            });
            });
</script>


<script>
        // Get all the choice buttons and the span
        const choiceButtons = document.querySelectorAll('.choice-btn');
        const choixEvolution = document.getElementById('choix-evolution');

        // Add click event listener to each button
        choiceButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                choiceButtons.forEach(btn => btn.classList.remove('selected-choice'));
                button.classList.add('selected-choice');
                choixEvolution.textContent = index + 1;
            });
        });
    </script>


<script>
    async function getObjectif(type = "save"){
        const errors =document.querySelectorAll('.objectif-error')
        errors.forEach(element => {
            element.classList.remove('objectif-error');
        })
        let x = document.getElementById("snackbar");
        let valid=true;

        const nbObjectifs = document.getElementById('dynamicTable').querySelector('tbody').rows.length;
        const objectives = [];
        for (let i = 1; i <= nbObjectifs; i++) {
            const index=i-1
            const objectif = document.getElementById(`input-objectif-${i}`).value;
            if(objectif==""){
                valid=false
                document.getElementById(`input-objectif-${i}`).parentElement.parentElement.classList.add("objectif-error")
            }
            const echeance = document.getElementById(`input-echeance-${i}`).value;
            if(echeance==""){
                valid=false
                document.getElementById(`input-echeance-${i}`).parentElement.parentElement.classList.add("objectif-error")
            }
            const indicateur = document.getElementById(`input-indicateur-${i}`).value;
            if(indicateur==""){
                valid=false
                document.getElementById(`input-indicateur-${i}`).parentElement.parentElement.classList.add("objectif-error")
            }

            objectives.push({
                index,
                objectif,
                echeance,
                indicateur
            });
        }

        const nbFormations = document.getElementById('dynamicTableFormation').querySelector('tbody').rows.length;
        const formations = [];
        if(document.getElementById(`input-formation-1`).value!="" || document.getElementById(`input-obj-formation-1`).value!=""){
            for (let i = 1; i <= nbFormations; i++) {
                const index=i-1
                const formation = document.getElementById(`input-formation-${i}`).value;
                if(formation==""){
                    valid=false
                    document.getElementById(`input-formation-${i}`).parentElement.parentElement.classList.add("objectif-error")
                }
                const objFormation = document.getElementById(`input-obj-formation-${i}`).value;
                if(objFormation==""){
                    valid=false
                    document.getElementById(`input-obj-formation-${i}`).parentElement.parentElement.classList.add("objectif-error")
                }
                const priorite = document.getElementById(`formation-priorite-${i}`).value;
                formations.push({
                    index,
                    formation,
                    objFormation,
                    priorite
                });
            }
        }

        const evolution = [];

        const souhait = document.getElementById(`choix-evolution`).textContent;
        const motivations = document.getElementById(`motivation`).value;
        if(motivations==""){
                valid=false
                document.getElementById(`motivation`).parentElement.classList.add("objectif-error")
            }

        const axes = document.getElementById(`axes`).value;
        if(axes==""){
                valid=false
                document.getElementById(`axes`).parentElement.classList.add("objectif-error")
            }
        const avisresponsable = document.getElementById(`avis-motivation`).value;

        evolution.push({
                souhait,
                motivations,
                axes,
                avisresponsable
            });
        

        if (!valid){
            x.innerHTML="Merci de renseigner tous les champs !"
            x.className = "show error-message";
            setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
            return
        }

        const payload = JSON.stringify({
            objectives: objectives,
            formations: formations,
            evolution: evolution
        });

        await fetch('xhr/objectifNextYear.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: payload
        })
        .then(response => response.json())
        .then(data => {
              // Get the snackbar DIV

            if (data.status="success"){
                x.innerHTML="Données enregistrées avec succés!"
            }else{
                x.innerHTML="Problème survenu, Merci de contacter votre administrateur!"
            }
            if(type != "save"){
                return 
            }
            x.className = "show success-message";
            setTimeout(function(){ x.className = x.className.replace("show success-message", ""); }, 3000);
        })
        .catch(error => {
            console.error('Error sending data:', error);
        });
        return x.innerHTML
    }

    async function getAutoEval(type = "save"){
        const errors =document.querySelectorAll('.input-error')
        errors.forEach(element => {
            element.classList.remove('input-error');
        })
        let x = document.getElementById("snackbar");
        let valid=true;
        const nbObjectifs = document.getElementById('objectifs-convenus').querySelector('tbody').rows.length;
        const objectives = [];
        for (let i = 1; i <= nbObjectifs; i++) {
            const index=i-1
            const realisation = document.getElementById(`obj-realisation-${i}`).value;
            let intValue = parseInt(realisation);
            if(isNaN(intValue) || !Number.isInteger(intValue)){
                valid=false
                document.getElementById(`obj-realisation-${i}`).parentElement.parentElement.classList.add("input-error")
            }
            const score = document.getElementById(`obj-score-${i}`).value;
            const commentaire = document.getElementById(`obj-commentaire-${i}`).value;
            if(commentaire==""){
                valid=false
                document.getElementById(`obj-commentaire-${i}`).parentElement.parentElement.classList.add("input-error")
            }

            objectives.push({
                index,
                realisation,
                score,
                commentaire
            });
        }
        const nbSoftSkills1 = document.getElementById('ss-convenus-1').querySelector('tbody').rows.length;
        const nbSoftSkills2 = document.getElementById('ss-convenus-2').querySelector('tbody').rows.length;
        const element = document.getElementById('ss-convenus-3');
        let nbSoftSkills3=0
        if (element) {
            nbSoftSkills3 = document.getElementById('ss-convenus-3').querySelector('tbody').rows.length;
        }
        const softSkills = [];
        for (let i = 1; i <= nbSoftSkills1+nbSoftSkills2+nbSoftSkills3; i++) {
            const index=i 
            let point =""
            if(!document.querySelector(`input[name="type-point-${i}"]:checked`)){
                valid=false
                document.querySelector(`input[name="type-point-${i}"]`).parentElement.parentElement.classList.add("input-error")
            }else{
                point=document.querySelector(`input[name="type-point-${i}"]:checked`).value;

            }
            const score = document.getElementById(`ss-score-${i}`).value;
            const commentaire = document.getElementById(`ss-commentaire-${i}`).value;
            if(commentaire==""){
                valid=false
                document.getElementById(`ss-commentaire-${i}`).parentElement.parentElement.classList.add("input-error")
            }

            softSkills.push({
                index,
                point,
                score,
                commentaire
            });
        }
        if (!valid){
            x.innerHTML="Merci de renseigner tous les champs !"
            x.className = "show error-message";
            setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
            return
        }

        const payload = JSON.stringify({
            objectives: objectives,
            softSkills: softSkills
        });
       await fetch('xhr/updateObjectifs.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: payload
        })
        .then(response => response.json())
        .then(data => {
              // Get the snackbar DIV

            if (data.status="success"){
                x.innerHTML="Données enregistrées avec succés!"
            }else{
                x.innerHTML="Problème survenu, Merci de contacter votre administrateur!"
            }
            if(type != "save"){
                return 
                }
            x.className = "show success-message";
            setTimeout(function(){ x.className = x.className.replace("show success-message", ""); }, 3000);
        })
        .catch(error => {
            console.error('Error sending data:', error);
        });

        return x.innerHTML
    }

    async function SetValidation(commentaire){
        const payload = JSON.stringify({
            type: "submit",
            commentaire:commentaire
        });
        await fetch('xhr/updateValidation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: payload
        })
        .then(response => response.json())
        .then(data => {
            if (data.status!="success"){
                return "error"
            }
        })
        .catch(error => {
            console.error('Error sending data:', error);
        });
        return "success"
    }

    async function sendEval(){
        let message1 =await getObjectif("submit")
        let message2 =await getAutoEval("submit")
        

        let x = document.getElementById("snackbar");
        let commEvalue=document.getElementById('com-evalue').value
            if(commEvalue==""){
                document.getElementById('com-evalue').classList.add("input-error")
                x.innerHTML="Merci de renseigner votre commentaire !"
                x.className = "show error-message";
                setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
                return
            }
        if (message1 =="Données enregistrées avec succés!" &&  message1==message2 && commEvalue !=""){

            await SetValidation(commEvalue);
            x.innerHTML="Evaluation envoyée avec succés!"
            x.className = "show success-message";
            setTimeout(function(){ x.className = x.className.replace("show success-message", ""); }, 3000);
        }else{
            x.innerHTML="Erreur lors de l'envoi de votre évaluation!"
            x.className = "show error-message";
            setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
        }
    }
</script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            const headers = document.querySelectorAll('.accordion-header');
            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const content = header.nextElementSibling;
                    content.classList.toggle('open');
                    header.children[1].classList.toggle('opened');
                });
            });
        });
</script>

<!-- Gerer le tableau des objectifs -->
<script>
        const table = document.getElementById('dynamicTable').querySelector('tbody');
        const addRowBtn = document.getElementById('addRowBtn');

        addRowBtn.addEventListener('click', () => {
            const rowCount = table.rows.length + 1;
            const row = table.insertRow();
            row.innerHTML = `
                <td><span>Objectif ${rowCount} :</span><textarea id="input-objectif-${rowCount}"  class="input-objectif" placeholder="Nouvel Objectif" ></textarea></td>
                <td><input id="input-echeance-${rowCount}"  class="input-objectif" placeholder="Echéance" ></td>
                <td><textarea id="input-indicateur-${rowCount}"  class="input-objectif" placeholder="Indicateurs de mesure" ></textarea></td>
                <td><button onclick="removeRow(this)">X</button></td>
            `;
        });

        function removeRow(button) {
            if (table.rows.length > 1) {
                const row = button.closest('tr');
                table.removeChild(row);
                updateRowNumbers();
            }
        }

        function updateRowNumbers() {
            document.getElementById('dynamicTable').querySelector('tbody').children.forEach((row, index) => {
                const rowOldIndex=row.cells[0].children[1].id.split('-')[2]

                let currentData=document.getElementById(`input-objectif-${rowOldIndex}`).value
                row.cells[0].innerHTML = `<td><span>Objectif ${index+1} :</span><textarea id="input-objectif-${index+1}"  class="input-objectif" placeholder="Nouvel Objectif" ></textarea></td>`;
                document.getElementById(`input-objectif-${index+1}`).value=currentData

                currentData=document.getElementById(`input-echeance-${rowOldIndex}`).value
                row.cells[1].innerHTML = `<td><input id="input-echeance-${index+1}"  class="input-objectif" placeholder="Echéance" ></td>`;
                document.getElementById(`input-echeance-${index+1}`).value=currentData

                currentData=document.getElementById(`input-indicateur-${rowOldIndex}`).value
                row.cells[2].innerHTML = `<td><textarea id="input-indicateur-${index+1}"  class="input-objectif" placeholder="Indicateurs de mesure" ></textarea></td>`;
                document.getElementById(`input-indicateur-${index+1}`).value=currentData


            });
        }

</script>


<!-- Gerer le tableau des formations -->

<script>
        const tablef = document.getElementById('dynamicTableFormation').querySelector('tbody');
        const addRowBtnF = document.getElementById('addRowBtnFormation');

        addRowBtnF.addEventListener('click', () => {
            const rowCount = tablef.rows.length + 1;
            const row = tablef.insertRow();
            row.innerHTML = `
                <td><textarea id="input-formation-${rowCount}"  class="input-objectif" placeholder="Formation" ></textarea></td>
                <td><textarea id="input-obj-formation-${rowCount}"  class="input-objectif" placeholder="Objectif de la formation" ></textarea></td>
                <td><select name="formation-priorite-${rowCount}" id="formation-priorite-${rowCount}" class="input-objectfs-avant">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select></td>
                <td><button onclick="removeRowformation(this)">X</button></td>
            `;
        });

        function removeRowformation(button) {
            if (tablef.rows.length > 1) {
                const row = button.closest('tr');
                tablef.removeChild(row);
                updateRowFNumbers();
            }
        }

        function updateRowFNumbers() {
            
            document.getElementById('dynamicTableFormation').querySelector('tbody').children.forEach((row, index) => {
                const rowOldIndex=row.cells[0].children[0].id.split('-')[2]

                let currentData=document.getElementById(`input-formation-${rowOldIndex}`).value
                row.cells[0].innerHTML = `<td><textarea id="input-formation-${index+1}"  class="input-objectif" placeholder="Formation" ></textarea></td>`;
                document.getElementById(`input-formation-${index+1}`).value=currentData

                currentData=document.getElementById(`input-obj-formation-${rowOldIndex}`).value
                row.cells[1].innerHTML = `<td><textarea id="input-obj-formation-${index+1}"  class="input-objectif" placeholder="Objectif de la formation" ></textarea></td>`;
                document.getElementById(`input-obj-formation-${index+1}`).value=currentData

                currentData=document.getElementById(`formation-priorite-${rowOldIndex}`).value
                row.cells[2].innerHTML = `<td><select  name="formation-priorite-${index+1}" id="formation-priorite-${index+1}" class="input-objectfs-avant">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        </select></td>`;
                document.getElementById(`formation-priorite-${index+1}`).value=currentData
            });
        }

</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // function downloadPDF() {
        
        
    //         const element = document.querySelector(".A4-format");
    //             const options = {
    //                 filename: 'EVAL 20225.pdf',
    //                 margin: 0,
    //                 image: { type: 'jpeg', quality: 0.98 },
    //                 html2canvas: { scale: 0.9 },
    //                 jsPDF: {
    //                     unit: 'in',
    //                     format: 'a4',
    //                     orientation: 'portrait',
    //                     left: 1,
    //                     right: 1
    //                 }
    //             };
 
    //             html2pdf().set(options).from(element).save();
    // }


     function downloadPDF() {
     const element = document.querySelector(".A4-format");

     const options = {
         filename: 'EVAL_20225.pdf',
         margin: [10, 10, 10, 10],  
         pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
         jsPDF: {
             unit: 'pt', 
             format: 'a4',
             orientation: 'portrait',
         }
     };

     html2pdf().set(options).from(element).save();
 }


</script>
