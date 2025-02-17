<?php
session_start();
var_dump($_SESSION['access']);
$annee=date('Y');
//3

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

function get_collab(){
    $sql='SELECT * FROM users WHERE FIND_IN_SET(id, (SELECT collaborateurs FROM users WHERE id = 2))';
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
    $sql="SELECT id_ligne,objectif,indicateur,realisation,score,resultat_analyse,validation,validation2 FROM objectifs where user='".$user."' and annee=".($year-1).""; 
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
$id_user=$_SESSION['user_id'];
$user=get_user($id_user);
$responsable=get_responsable($user['responsable'])["full_name"];
$lastyearObjectifs=getLastYearObj($id_user,$annee);
$lastyearSoftSkills=getSoftSkills($id_user,$annee);
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
    <button id="stock-button"  class="update-button" onclick="downloadPDF()">Imprimer</button>

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
        <section class="accordion">
            <div class="accordion-header ">
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
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
                                <th width="30%">RAPPEL DES OBJECTIFS</th>
                                <th width="10%">INDICATEUR DE MESURE</th>
                                <th width="5%">REALESATION EN %</th>
                                <th width="5%">SCORE</th>
                                <th width="45%">COMMENTAIRES DES RESULTATS ET ANALYSE DES ECARTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lastyearObjectifs as $objectif): ?>
                                <tr>
                                    <td><span>Objectif <?= htmlspecialchars(($objectif['id_ligne']+1))?> : </span><span><?= htmlspecialchars($objectif['objectif'])?></span></td>
                                    <td><?= htmlspecialchars($objectif['indicateur']) ?></td>
                                    <td>
                                        <input <?= $objectif['validation']==1?'readonly':''?> class="input-objectfs-avant centred" type="number" placeholder="0 - 100" value="<?= $objectif['realisation']?>"  id="obj-realisation-<?= htmlspecialchars(($objectif['id_ligne']+1))?>">
                                    </td>
                                    <td class="td-score">
                                        <select  <?= $objectif['validation']==1?'disabled':''?> name="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>" id="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>">
                                            <option  <?= $objectif['score'] == "1" ? "selected" : "" ?> value="1">1</option>
                                            <option  <?= $objectif['score'] == "2" ? "selected" : "" ?> value="2">2</option>
                                            <option  <?= $objectif['score'] == "3" ? "selected" : "" ?> value="3">3</option>
                                            <option  <?= $objectif['score'] == "4" ? "selected" : "" ?> value="4">4</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea <?= $objectif['validation']==1?'readonly':''?> class="input-objectfs-avant" placeholder="En attente d'évaluation"  id="obj-commentaire-<?= htmlspecialchars(($objectif['id_ligne']+1))?>"><?= $objectif['resultat_analyse'] ? $objectif['resultat_analyse'] : "" ?></textarea>
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
                                    <td><input type="radio" class="radio-btn" id="ss-point-fort-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="fort" /></td>
                                    <td><input type="radio" class="radio-btn" id="ss-point-progret-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="progret" /></td>
                                    <td class="td-score">
                                        <select disabled name="ss-score-<?php echo $softskill['id_ss']; ?>" id="ss-score-<?php echo $softskill['id_ss']; ?>">
                                            <option value="1" <?php echo $softskill['score'] == 1 ? 'selected' : ''; ?>>1</option>
                                            <option value="2" <?php echo $softskill['score'] == 2 ? 'selected' : ''; ?>>2</option>
                                            <option value="3" <?php echo $softskill['score'] == 3 ? 'selected' : ''; ?>>3</option>
                                            <option value="4" <?php echo $softskill['score'] == 4 ? 'selected' : ''; ?>>4</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea readonly class="input-objectfs-avant" id="ss-comment-<?php echo $softskill['id_ss']; ?>" name="ss-comment-<?php echo $softskill['id_ss']; ?>" placeholder="Commentaire"><?php echo htmlspecialchars($softskill['commentaire']); ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><span id="ss-1">Capacité à travailler en équipe</span></td>
                                <td><input  type="radio" class="radio-btn" id="ss-point-fort-1" name="type-point-1" value="fort"/></td>
                                <td><input  type="radio" class="radio-btn" id="ss-point-progret-1" name="type-point-1" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-1" id="ss-score-1">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-1" name="ss-comment-1" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-2">Prise d'initiative</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-2" name="type-point-2" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-2" name="type-point-2" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-2" id="ss-score-2">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-2" name="ss-comment-2" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-3">Accompagner le changement</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-3" name="type-point-3" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-3" name="type-point-3" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-3" id="ss-score-3">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-3" name="ss-comment-3" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-4">Autonomie dans la résolution des problèmes</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-4" name="type-point-4" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-4" name="type-point-4" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-4" id="ss-score-4">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-4" name="ss-comment-4" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-5">Communication et Feed-Back</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-5" name="type-point-5" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-5" name="type-point-5" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-5" id="ss-score-5">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-5" name="ss-comment-5" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-6">Être orienté client</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-6" name="type-point-6" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-6" name="type-point-6" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-6" id="ss-score-6">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-6" name="ss-comment-6" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>



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
                            <tr>
                                <td><span id="ss-7">Ponctualité et Absence</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-7" name="type-point-7" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-7" name="type-point-7" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-7" id="ss-score-7">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-7" name="ss-comment-7" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-8">Relation avec les collègues</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-8" name="type-point-8" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-8" name="type-point-8" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-8" id="ss-score-8">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-8" name="ss-comment-8" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-9">Relation avec la hiérarchie </span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-9" name="type-point-9" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-9" name="type-point-9" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-9" id="ss-score-9">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-9" name="ss-comment-9" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <table id="ss-convenus-2" border="1">
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
                            <tr>
                                <td><span id="ss-10">Animer une équipe </span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-10" name="type-point-10" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-10" name="type-point-10" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-10" id="ss-score-10">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-10" name="ss-comment-10" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-11">Gérer les conflits</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-11" name="type-point-11" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-11" name="type-point-11" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-11" id="ss-score-11">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-11" name="ss-comment-11" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-12">Structurer l'activité IT</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-12" name="type-point-12" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-12" name="type-point-12" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-12" id="ss-score-12">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-12" name="ss-comment-12" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-13">Déléguer (Pour les manager)</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-13" name="type-point-13" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-13" name="type-point-13" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-13" id="ss-score-13">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-13" name="ss-comment-13" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-14">Transversalité managériale (Pour les manager)</span></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-fort-14" name="type-point-14" value="fort"/></td>
                                <td><input disabled type="radio" class="radio-btn"  id="ss-point-progret-14" name="type-point-14" value="progret"/></td>
                                <td class="td-score">
                                    <select disabled name="ss-score-14" id="ss-score-14">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea readonly class="input-objectfs-avant" id="ss-comment-14" name="ss-comment-14" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="button-validion-container">
                        <button class="button-validion" onClick="getAutoEval()">Envoyer mon auto-évaluation</button>
                    </div>

                </div>
            </div>
            <div class="accordion-header">
                <span>II - OBJECTIFS ET AXE DE DEVELOPPEMENT PROFESSIONNEL DE L'ANNÉE À VENIR  </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content open">
                <div class="section-header ">
                    <span>1 - NOUVEAUX OBJECTIFS POUR L’ANNEE A VENIR</span>
                </div>
                <div class="section-detail">
                    <button id="addRowBtn">Ajouter Objectif</button>
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
                            <tr>
                                <td width="50%">
                                    <span>Objectif 1 :</span>
                                    <textarea id="input-objectif-1"  class="input-objectif" placeholder="Nouvel Objectif" ></textarea>
                                </td>
                                <td width="10%">
                                    <input id="input-echeance-1"  class="input-objectif" placeholder="Echéance" >
                                </td>
                                <td width="37%">
                                    <textarea id="input-indicateur-1"  class="input-objectif" placeholder="Indicateurs de mesure" ></textarea>
                                </td>
                                <td width="3%"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="section-header ">
                    <span>2- SOUHAITS DE FORMATION DU COLLABORATEUR</span>
                </div>
                <div class="section-detail">
                    <button id="addRowBtnFormation">Ajouter formarion</button>
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
                            <tr>
                                <td width="45%">
                                    <textarea id="input-formation-1"  class="input-objectif" placeholder="Formation" ></textarea>
                                </td>
                                <td width="45%">
                                    <textarea id="input-obj-formation-1"  class="input-objectif" placeholder="Objectif de la formation" ></textarea>
                                </td>
                                <td width="7%">
                                    <select  disabled name="formation-priorite-1" id="formation-priorite-1" class="input-objectfs-avant">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </td>
                                <td width="3%"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="accordion-header">
                <span>III- DISCIPLINE (pendant l'année <?= $annee-1?>) </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>

            </div>
            <div class="accordion-header">
                <span>IV- SYNTHESE GENERALE  </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>

            </div>
            <div class="accordion-header">
                <span>V- CONCLUSION DE L'APPRECIATION  </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content">
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>

            </div>
        </section>
        
    </main>
</div>
    <footer>
        <div id="snackbar">Some text some message..</div>
    </footer>
</html>

<script>
    function getAutoEval(){
        const nbObjectifs = document.getElementById('objectifs-convenus').querySelector('tbody').rows.length;
        const objectives = [];
        for (let i = 1; i <= nbObjectifs; i++) {
            const index=i-1
            const realisation = document.getElementById(`obj-realisation-${i}`).value;
            const score = document.getElementById(`obj-score-${i}`).value;
            const commentaire = document.getElementById(`obj-commentaire-${i}`).value;

            objectives.push({
                index,
                realisation,
                score,
                commentaire
            });
        }
        console.log(objectives)
          // Convert the array to JSON for sending
        const payload = JSON.stringify(objectives);

        // Send data using fetch
        fetch('xhr/updateObjectifs.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: payload
        })
        .then(response => response.json())
        .then(data => {
              // Get the snackbar DIV
            var x = document.getElementById("snackbar");
            if (data.status="success"){
                x.innerHTML="Données envoyées avec succés!"
            }else{
                x.innerHTML="Problème survenu, Merci de contacter votre administrateur!"
            }
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        })
        .catch(error => {
            console.error('Error sending data:', error);
        });
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
                console.log(row.cells[0].innerHTML)
                row.cells[0].innerHTML = `<td><span>Objectif ${index+1} :</span><textarea id="input-objectif-${index+1}"  class="input-objectif" placeholder="Nouvel Objectif" ></textarea></td>`;
                row.cells[1].innerHTML = `<td><input id="input-echeance-${index+1}"  class="input-objectif" placeholder="Echéance" ></td>`;
                row.cells[2].innerHTML = `<td><textarea id="input-indicateur-${index+1}"  class="input-objectif" placeholder="Indicateurs de mesure" ></textarea></td>`;
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
                <td><select  disabled name="formation-priorite-${rowCount}" id="formation-priorite-${rowCount}" class="input-objectfs-avant">
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
                row.cells[0].innerHTML = `<td><textarea id="input-formation-${index+1}"  class="input-objectif" placeholder="Formation" ></textarea></td>`;
                row.cells[1].innerHTML = `<td><textarea id="input-obj-formation-${index+1}"  class="input-objectif" placeholder="Objectif de la formation" ></textarea></td>`;
                row.cells[2].innerHTML = `<td><select  disabled name="formation-priorite-${rowCount}" id="formation-priorite-${rowCount}" class="input-objectfs-avant">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        </select></td>
                    <td><button onclick="removeRowformation(this)">X</button></td>`;
            });
        }

</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        
        
            const element = document.querySelector(".A4-format");
                const options = {
                    filename: 'EVAL 20225.pdf',
                    margin: 0,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 0.9 },
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'portrait',
                        left: 1,
                        right: 1
                    }
                };
 
                html2pdf().set(options).from(element).save();
    }


//     function downloadPDF() {
//     const element = document.querySelector(".A4-format");

//     const options = {
//         filename: 'EVAL_20225.pdf',
//         margin: [10, 10, 10, 10], // Adjust margins if needed (in px)
//         pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }, // To handle page breaks properly
//         jsPDF: {
//             unit: 'pt', // Points (suitable for print PDFs)
//             format: 'a4',
//             orientation: 'portrait',
//         }
//     };

//     html2pdf().set(options).from(element).save();
// }

</script>