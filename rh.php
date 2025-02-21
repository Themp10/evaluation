<?php
    ini_set('session.gc_maxlifetime', 36000);
    ini_set('session.cookie_lifetime', 36000);

session_start();
$annee=date('Y');
$validationStatus = $lastyearObjectifs = $lastyearSoftSkills = $nextyearObjectifs = $nextYearFormation = $nextYearEvolution = $scores = [];


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

function getCollabData($user){

}

function initDiscipline($year){
    global $id_user,$user;
    $id_user=$_SESSION['user_id'];
    $user=get_user($id_user);

}

function getEtat($annee){
    global $conn; 
    $sql="SELECT u.id,CONCAT(u.nom, ' ', u.prenom) AS Employe,CONCAT(u1.nom, ' ', u1.prenom) AS Responsable,v.saisie,v.submit,v.validation1,v.validation2,u2.nom,v.validationRH,v.validationDG 
    FROM validation v 
    JOIN users u ON u.id = v.user  
    JOIN users u1 ON u1.id = v.valideur1  
    JOIN users u2 ON u2.id = v.valideur2
    WHERE u.id NOT IN (1,2,3,4,5,63,62,65) order by Employe";
    //where v.annee=2025"; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $validation = [];

    while ($row = $result->fetch_assoc()) {
        $validation[] = $row;
    }
    return $validation;
}

function generateTableEtat(){
    global $annee;
    $etat=getEtat($annee);
    $html='';
    $waiting='En attente';
    $enAttente='enattente';

    foreach ($etat as $row) {
        //auto evaluation


        if($row['submit']==1){
            $statusAE='Réalisée';
            $classSubmit="submited";
        }else{
            if($row['saisie']==1){
                $statusAE='En cours';
                $classSubmit="encours";

            }else{
                $statusAE='Non débutée';
                $classSubmit="nondebutee";

            }
        }
        //resp evaluation
        $statusER=$waiting;
        $classR=$enAttente;
        if($row['validation1']==1){
            $statusER='Validée';
            $classR="validated";
        }else{
            if($row['submit']==1){
                $statusER='En cours';
                $classR="encours";
            }
        }
        //N+2 evaluation
        $statusN2=$waiting;
        $classN2=$enAttente;
        if($row['validation2']==1){
            $statusN2='Validée';
            $classN2="validated";
        }else{
            if($row['validation1']==1){
                $statusN2='En cours';
                $classN2="encours";
            }
        }

        //RH evaluation
        $statusRH=$waiting;
        $classRH=$enAttente;
        if($row['validationRH']==1){
            $statusRH='Validée';
            $classRH="validated";
        }else{
            if($row['validation2']==1 || $row['validation1']==1 ){
                $statusRH='En cours';
                $classRH="encours";
            }
        }

        //DG evaluation
        $statusDG=$waiting;
        $classDG=$enAttente;
        if($row['validationDG']==1){
            $statusDG='Validée';
            $classDG="validated";
        }else{
            if($row['validationRH']==1 && $row['validation2']==1){
                $statusDG='En cours';
                $classDG="encours";
            }
        }
        $html .= '<tr>
                    <td><div>' . htmlspecialchars($row['Employe']) . '</div></td>
                    <td><div>' . htmlspecialchars($row['Responsable']) . '</div></td>
                    <td><div class="rh-statut '.$classSubmit.'">' . $statusAE. '</div></td>
                    <td><div class="rh-statut '.$classR.'">' . $statusER. '</div></td>
                    <td><div class="rh-statut '.$classN2.'">' . $statusN2. '</div></td>
                    <td><div class="rh-statut '.$classRH.'">' . $statusRH. '</div></td>
                    <td><div class="rh-statut '.$classDG.'">' . $statusDG. '</div></td>
                </tr>';
    }
    return $html;
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
    if(empty($data)){
        $data = ['commentaire'=>'','assiduite'=>'','obj_sanctions'=>'','nb_sanctions'=>''];
        return $data;
    }
    return $data[0];
}

function getValidationStatus($user,$year){
    global $conn; 
    $sql="SELECT validation1,validation2,validationRH,validationDG FROM validation where user='".$user."' and annee=".$year.""; 
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    if(empty($data)){
        $data = ['validation1'=>0,'validation2'=>0,'validationRH'=>0,'validationDG'=>0];
        return $data;
    }
    return $data[0];
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

function getValidation($user,$year){
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

function getDisciplineEval($user,$year){
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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_user=$_SESSION['user_id'];
    $user=get_user($id_user);
    $collabList=getEtat($annee);


}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user=$_SESSION['user_id'];
    $user=get_user($id_user);
    $collabList=getEtat($annee);

    if (isset($_POST['selected_collaborateur'])) {
        $selectedCollaborateurId = $_POST['selected_collaborateur'];
        $_SESSION['collaborateurId'] = $selectedCollaborateurId;
        $userDiscpiline = get_user($selectedCollaborateurId);
        $responsable=get_responsable($userDiscpiline['responsable'])["full_name"];
        $validation=getValidationStatus($selectedCollaborateurId,$annee);
        $discipline=getDiscipline($selectedCollaborateurId,$annee);
        $lastyearObjectifs=getLastYearObj($selectedCollaborateurId,$annee);
        $lastyearSoftSkills=getSoftSkills($selectedCollaborateurId,$annee);
        $nextyearObjectifs=getNextYearObj($selectedCollaborateurId,$annee);
        $validationStatus=getValidation($selectedCollaborateurId,$annee);
        $nextYearFormation=getNextYearFormation($selectedCollaborateurId,$annee);
        $nextYearEvolution=getEvolution($selectedCollaborateurId,$annee);
        $scores=!empty(getScores($selectedCollaborateurId,$annee))?getScores($selectedCollaborateurId,$annee)[0]:[];
        $discipline=getDisciplineEval($selectedCollaborateurId,$annee);
    }
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

    <div class="accordion-header">
        <span>Etat avancement </span>
        <span class="arrow">&#9660;</span>
    </div>
    <div class="accordion-content">
        <div class="filter-container">
            <div class="filter-row">
                <div class="filter-title">Auto Evaluation : </div>
                <div class="filter-statut submited" onclick="selectFilter(this)">Réalisée</div>
                <div class="filter-statut encours" onclick="selectFilter(this)">En cours</div>
                <div class="filter-statut nondebutee" onclick="selectFilter(this)">Non débutée</div>
            </div>
            <div class="filter-row">
                <div class="filter-title">Evaluation N+1 : </div>
                <div class="filter-statut validated" onclick="selectFilter(this)">Validée</div>
                <div class="filter-statut encours" onclick="selectFilter(this)">En cours</div>
                <div class="filter-statut enattente" onclick="selectFilter(this)">En attente</div>
            </div>
            <div class="filter-row">
                <div class="filter-title">Evaluation N+2 : </div>
                <div class="filter-statut validated" onclick="selectFilter(this)">Validée</div>
                <div class="filter-statut encours" onclick="selectFilter(this)">En cours</div>
                <div class="filter-statut enattente" onclick="selectFilter(this)">En attente</div>
            </div>
            <div class="filter-row">
                <div class="filter-title">Evaluation RH : </div>
                <div class="filter-statut validated" onclick="selectFilter(this)">Validée</div>
                <div class="filter-statut encours" onclick="selectFilter(this)">En cours</div>
                <div class="filter-statut enattente" onclick="selectFilter(this)">En attente</div>
            </div>
            <div class="filter-row">
                <div class="filter-title">Evaluation DG : </div>
                <div class="filter-statut validated" onclick="selectFilter(this)">Validée</div>
                <div class="filter-statut encours" onclick="selectFilter(this)">En cours</div>
                <div class="filter-statut enattente" onclick="selectFilter(this)">En attente</div>
            </div>
        </div>
        <table border="1" id="table-etat">
            <thead>
            <tr>
                <th width="20%">Employé</th>
                <th width="20%">Responsable</th>
                <th width="12%">Auto évaluation</th>
                <th width="12%">Evaluation n+1</th>
                <th width="12%">Evaluation n+2</th>
                <th width="12%">Evaluation RH</th>
                <th width="12%">Evaluation DG</th>
            </tr>
            </thead>
            <tbody>
                <?= generateTableEtat(); ?>
            </tbody>
        </table>

    </div>
    <?php if ($user["type"]=='d'): ?>
        <div class="eval-header">
            <h2>Validation des évaluations</h2>
        </div>
            <form class="rh-collaborateurs-container" action="rh.php" method="post">
            <h1>Collaborateurs</h1>
            <?php foreach ($collabList as $collaborateur): ?>
                <?php if ($collaborateur['validationDG'] == 0): ?>
                    <button type="submit" name="selected_collaborateur" value="<?= htmlspecialchars($collaborateur['id']) ?>" class="<?=($collaborateur['validation1']==1 && $collaborateur['validation2']==1 && $collaborateur['validationRH']==1 && $collaborateur['validationDG']==0) ? 'collaborateurN2' :'collaborateur'?>">
                        <?= htmlspecialchars($collaborateur['Employe']) ?>
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>

            <h1>Validé par DG</h1>
            <?php foreach ($collabList as $collaborateur): ?>
                <?php if ($collaborateur['validationDG'] == 1): ?>
                    <button type="submit" name="selected_collaborateur" value="<?= htmlspecialchars($collaborateur['id']) ?>" class="collaborateurN2">
                        <?= htmlspecialchars($collaborateur['Employe']) ?>
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>
            </form>
            <?php if (isset($_POST['selected_collaborateur'])): ?>
                <div class="user-section">
                    <div class="user-data">
                        <label for="nom">NOM PRENOM  DU COLLABORATEUR :</label>
                        <input class="names" type="text" id="nom" value="<?= htmlspecialchars($userDiscpiline['nom'])." ".htmlspecialchars($userDiscpiline['prenom']) ?>" readonly>
                    </div>
                    <div class="user-data">
                        <label for="prenom">DATE D'EMBAUCHE :</label>
                        <input type="text" id="prenom" value="<?= htmlspecialchars($userDiscpiline['date_embauche']) ?>" readonly>
                    </div>
                    <div class="user-data">
                        <label for="email"> DEPARTEMENT / DIRECTION :</label>
                        <input type="text" id="dir" value="<?= htmlspecialchars($userDiscpiline['direction']) ?>" readonly>
                    </div>
                    <div class="user-data">
                        <label for="login">POSTE OCCUPE:</label>
                        <input type="text" id="de" value="<?= htmlspecialchars($userDiscpiline['poste']) ?>" readonly>
                    </div>
                    <div class="user-data">    
                        <label for="login">RESPONSABLE HIERARCHIQUE :</label>
                        <input class="names" type="text" id="de" value="<?= $responsable?>" readonly>
                    </div>
                </div>
               
                <?php if ($validation['validation1']==1 && $validation['validation2']==1 && $validation['validationRH']==1 && $validation['validationDG']==0): ?>
                    <button onclick="validationDG()" class="button-validion">Valider</button>
                <?php endif; ?> 

                <div class="accordion-header ">
                    <span>I - ANALYSE DE LA PERIODE ECOULEE </span>

                    <span class="arrow">&#9660;</span>
                </div>
                <div class="accordion-content open">
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
                                        <input readonly class="input-objectfs-avant centred" type="number" placeholder="0 - 100" value="<?= $objectif['realisation']?>"  id="obj-realisation-<?= htmlspecialchars(($objectif['id_ligne']+1))?>">
                                    </td>
                                    <td class="td-score">
                                        <select  disabled name="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>" id="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>">
                                            <option  <?= $objectif['score'] == "1" ? "selected" : "" ?> value="1">1 - Résultats insuffisants</option>
                                            <option  <?= $objectif['score'] == "2" ? "selected" : "" ?> value="2">2 - Objectifs partiellement réalisés</option>
                                            <option  <?= $objectif['score'] == "3" ? "selected" : "" ?> value="3">3 - Objectifs atteints</option>
                                            <option  <?= $objectif['score'] == "4" ? "selected" : "" ?> value="4">4 - Objectifs  dépassés</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea readonly class="input-objectfs-avant" placeholder="En attente d'évaluation"  id="obj-commentaire-<?= htmlspecialchars(($objectif['id_ligne']+1))?>"><?= $objectif['resultat_analyse'] ? $objectif['resultat_analyse'] : "" ?></textarea>
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
                                    <td><input disabled type="radio" class="radio-btn" id="ss-point-fort-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="fort" <?= $softskill['point'] == 'fort' ? 'checked' : '' ?>/></td>
                                    <td><input disabled type="radio" class="radio-btn" id="ss-point-progret-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="progret" <?= $softskill['point'] == 'progret' ? 'checked' : '' ?>/></td>
                                    <td class="td-score">
                                        <select disabled name="ss-score-<?php echo $softskill['id_ss']; ?>" id="ss-score-<?php echo $softskill['id_ss']; ?>">
                                            <option value="1" <?php echo $softskill['score'] == 1 ? 'selected' : ''; ?>>1 - Aptitudes inférieures aux exigences attendues</option>
                                            <option value="2" <?php echo $softskill['score'] == 2 ? 'selected' : ''; ?>>2 - Aptitudes partiellements conformes aux exigences attendues</option>
                                            <option value="3" <?php echo $softskill['score'] == 3 ? 'selected' : ''; ?>>3 - Aptitudes conformes aux exigences attendues</option>
                                            <option value="4" <?php echo $softskill['score'] == 4 ? 'selected' : ''; ?>>4 - Aptitudes supérieures aux exigences attendues</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea readonly class="input-objectfs-avant" id="ss-commentaire-<?php echo $softskill['id_ss']; ?>" name="ss-commentaire-<?php echo $softskill['id_ss']; ?>" placeholder="Commentaire"><?php echo htmlspecialchars($softskill['commentaire']); ?></textarea>
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
                                    <td><input disabled type="radio" class="radio-btn" id="ss-point-fort-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="fort" <?= $softskill['point'] == 'fort' ? 'checked' : '' ?>/></td>
                                    <td><input disabled type="radio" class="radio-btn" id="ss-point-progret-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="progret"  <?= $softskill['point'] == 'progret' ? 'checked' : '' ?>/></td>
                                    <td class="td-score">
                                        <select disabled name="ss-score-<?php echo $softskill['id_ss']; ?>" id="ss-score-<?php echo $softskill['id_ss']; ?>">
                                            <option value="1" <?php echo $softskill['score'] == 1 ? 'selected' : ''; ?>>1 - Aptitudes inférieures aux exigences attendues</option>
                                            <option value="2" <?php echo $softskill['score'] == 2 ? 'selected' : ''; ?>>2 - Aptitudes partiellements conformes aux exigences attendues</option>
                                            <option value="3" <?php echo $softskill['score'] == 3 ? 'selected' : ''; ?>>3 - Aptitudes conformes aux exigences attendues</option>
                                            <option value="4" <?php echo $softskill['score'] == 4 ? 'selected' : ''; ?>>4 - Aptitudes supérieures aux exigences attendues</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea readonly class="input-objectfs-avant" id="ss-commentaire-<?php echo $softskill['id_ss']; ?>" name="ss-commentaire-<?php echo $softskill['id_ss']; ?>" placeholder="Commentaire"><?php echo htmlspecialchars($softskill['commentaire']); ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if (empty($filteredSoftSkillsT2)): ?>
                                <p>Aucune donnée disponible pour cette année.</p>
                    <?php endif; ?>

                    <?php if ($userDiscpiline["collaborateurs"]!='NA'): ?>
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
                                        <td><input  disabled type="radio" class="radio-btn" id="ss-point-fort-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="fort" <?= $softskill['point'] == 'fort' ? 'checked' : '' ?>/></td>
                                        <td><input disabled type="radio" class="radio-btn" id="ss-point-progret-<?php echo $softskill['id_ss']; ?>" name="type-point-<?php echo $softskill['id_ss']; ?>" value="progret" <?= $softskill['point'] == 'progret' ? 'checked' : '' ?>/></td>
                                        <td class="td-score">
                                            <select disabled name="ss-score-<?php echo $softskill['id_ss']; ?>" id="ss-score-<?php echo $softskill['id_ss']; ?>">
                                                <option value="1" <?php echo $softskill['score'] == 1 ? 'selected' : ''; ?>>1 - Aptitudes inférieures aux exigences attendues</option>
                                                <option value="2" <?php echo $softskill['score'] == 2 ? 'selected' : ''; ?>>2 - Aptitudes partiellements conformes aux exigences attendues</option>
                                                <option value="3" <?php echo $softskill['score'] == 3 ? 'selected' : ''; ?>>3 - Aptitudes conformes aux exigences attendues</option>
                                                <option value="4" <?php echo $softskill['score'] == 4 ? 'selected' : ''; ?>>4 - Aptitudes supérieures aux exigences attendues</option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea readonly class="input-objectfs-avant" id="ss-commentaire-<?php echo $softskill['id_ss']; ?>" name="ss-commentaire-<?php echo $softskill['id_ss']; ?>" placeholder="Commentaire"><?php echo htmlspecialchars($softskill['commentaire']); ?></textarea>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($filteredSoftSkillsT3)): ?>
                                    <p>Aucune donnée disponible pour cette année.</p>
                        <?php endif; ?>    
                    <?php endif; ?>


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
                                        <textarea disabled id="input-objectif-1" class="input-objectif" placeholder="Nouvel Objectif"></textarea>
                                    </td>
                                    <td width="10%">
                                        <input disabled id="input-echeance-1" class="input-objectif" placeholder="Échéance">
                                    </td>
                                    <td width="37%">
                                        <textarea disabled id="input-indicateur-1" class="input-objectif" placeholder="Indicateurs de mesure"></textarea>
                                    </td>
                                    <td width="3%"></td>
                                </tr>
                            <?php else: ?>
                            <?php foreach ($nextyearObjectifs as $index => $row): ?>
                                <tr>
                                    <td width="50%">
                                        <span>Objectif <?= $index + 1 ?>:</span>
                                        <textarea disabled  id="input-objectif-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['objectif']) ?></textarea>
                                    </td>
                                    <td width="10%">
                                        <input disabled  id="input-echeance-<?= $index + 1 ?>" class="input-objectif" value="<?= htmlspecialchars($row['echeance']) ?>">
                                    </td>
                                    <td width="37%">
                                        <textarea disabled  id="input-indicateur-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['indicateur']) ?></textarea>
                                    </td>
                                    <td width="3%">
                                        <button disabled onclick="removeRow(this)">X</button>
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
                    <button <?= $validationStatus['validation1']==1?'hidden':''?> disabled id="addRowBtnFormation" >Ajouter formation</button>
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
                                        <textarea disabled id="input-formation-1"  class="input-objectif" placeholder="Formation" ></textarea>
                                    </td>
                                    <td width="45%">
                                        <textarea disabled id="input-obj-formation-1"  class="input-objectif" placeholder="Objectif de la formation" ></textarea>
                                    </td>
                                    <td width="7%" class="td-priorite">
                                        <select  disabled name="formation-priorite-1" id="formation-priorite-1" class="input-objectfs-avant">
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
                                        <textarea disabled  id="input-formation-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['formation']) ?></textarea>
                                    </td>
                                    <td width="45%">
                                        <textarea disabled  id="input-obj-formation-<?= $index + 1 ?>" class="input-objectif"><?= htmlspecialchars($row['obj_formation']) ?></textarea>
                                    </td>
                                    <td width="7%">
                                        <select  disabled name="formation-priorite-<?= $index + 1 ?>" id="formation-priorite-<?= $index + 1 ?>" class="input-objectfs-avant">
                                            <option <?= $row['priorite']==1?'selected':''?> value="1">1</option>
                                            <option <?= $row['priorite']==2?'selected':''?> value="2">2</option>
                                            <option <?= $row['priorite']==3?'selected':''?> value="3">3</option>
                                        </select>
                                    </td>
                                    <td width="3%">
                                        <button disabled onclick="removeRowformation(this)">X</button>
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
                            <button disabled class="choice-btn<?= $nextYearEvolution["souhait"]=="1"?' selected-choice':''?>">1-  Maintien dans le même poste</button>
                            <button disabled class="choice-btn<?= $nextYearEvolution["souhait"]=="2"?' selected-choice':''?>">2-  Evolution dans le même département </button>
                            <button disabled class="choice-btn<?= $nextYearEvolution["souhait"]=="3"?' selected-choice':''?>">3- Changement de poste hors département </button>
                        </div>
                    </div>  
                    <div class="motivation-container">
                        <span>Motivations du collaborateur</span>
                        <textarea disabled class="input-objectif" name="motivation" id="motivation" cols="30" rows="10" placeholder="Indiquez les motivations et aptitudes mises en avant pour justifier vos souhaits"><?php echo $nextYearEvolution["motivation"]?></textarea>
                    </div>
                    <div class="motivation-container">
                        <span>Axes d'amélioration attendus</span>
                        <textarea disabled class="input-objectif" name="axes" id="axes" cols="30" rows="10" placeholder="Accompagnement et supervision"><?php echo $nextYearEvolution["axes"]?></textarea>
                    </div>
                    <div class="motivation-container">
                        <span>Avis du responsable hiérarchique</span>
                        <textarea disabled class="input-objectif" name="avis-motivation" id="avis-motivation" cols="30" rows="10" placeholder="sur les souhaits exprimés, sur les potentialités à moyen terme (1 – 2 ans), etc…
Actions d’accompagnement envisagées (formation, mise en situation, tutorat, …)."><?php echo $nextYearEvolution["avis"]?></textarea>
                    </div>
                </div>

            </div>
            <div class="accordion-header">
                <span>III- DISCIPLINE (pendant l'année <?= $annee-1?>) </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content open">
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
            <div class="accordion-content open">
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
            <div class="accordion-content open">
                <div class="motivation-container">
                    <span>Commentaire Collaborateur</span>
                    <textarea disabled class="input-objectif" name="com-evalue" id="com-evalue" cols="30" rows="10"><?php echo $validationStatus["comm_evalue"]?></textarea>
                </div>
                <div class="motivation-container">
                    <span>Commentaire Manager</span>
                    <textarea disabled class="input-objectif" name="com-evaluateur" id="com-evaluateur" cols="30" rows="10"><?php echo $validationStatus["comm_evaluateur"]?></textarea>
                </div>
            </div>


            <?php if ($validation['validation1']==1 && $validation['validation2']==1 && $validation['validationRH']==1 && $validation['validationDG']==0): ?>
                    <button onclick="validationDG()" class="button-validion">Valider</button>
                <?php endif; ?> 

            <?php endif; ?> 
         







            
            <?php elseif ($user["type"]=='r'): ?>    
        <div class="accordion-header">
            <span>Saisie discipline </span>
            <span class="arrow">&#9660;</span>
        </div>
        <div class="accordion-content open">
            <form class="rh-collaborateurs-container" action="rh.php" method="post">
                <h1>Collaborateurs</h1>
                <?php foreach ($collabList as $collaborateur): ?>
                    <button type="submit" name="selected_collaborateur" value="<?= htmlspecialchars($collaborateur['id']) ?>" class="<?=$collaborateur['validationRH'] == 1?'collaborateurN2':($collaborateur['validation1'] == 1?'collaborateur valideeN1':'collaborateur')?>">
                        <?= htmlspecialchars($collaborateur['Employe']) ?>
                    </button>
                <?php endforeach; ?>
            </form>
            <?php if (isset($_POST['selected_collaborateur'])): ?>
                <div class="user-section">
                    <div class="user-data">
                        <label for="nom">NOM PRENOM  DU COLLABORATEUR :</label>
                        <input class="names" type="text" id="nom" value="<?= htmlspecialchars($userDiscpiline['nom'])." ".htmlspecialchars($userDiscpiline['prenom']) ?>" readonly>
                    </div>
                    <div class="user-data">
                        <label for="prenom">DATE D'EMBAUCHE :</label>
                        <input type="text" id="prenom" value="<?= htmlspecialchars($userDiscpiline['date_embauche']) ?>" readonly>
                    </div>
                    <div class="user-data">
                        <label for="email"> DEPARTEMENT / DIRECTION :</label>
                        <input type="text" id="dir" value="<?= htmlspecialchars($userDiscpiline['direction']) ?>" readonly>
                    </div>
                    <div class="user-data">
                        <label for="login">POSTE OCCUPE:</label>
                        <input type="text" id="de" value="<?= htmlspecialchars($userDiscpiline['poste']) ?>" readonly>
                    </div>
                    <div class="user-data">    
                        <label for="login">RESPONSABLE HIERARCHIQUE :</label>
                        <input class="names" type="text" id="de" value="<?= $responsable?>" readonly>
                    </div>
                </div>
            
                <div class="rh-eval-container">
                    <table class="discipline-table" border="1">
                            <thead>
                                <tr>
                                    <th width="20%">Nombre de sanctions</th>
                                    <th width="80%">Objet de sanctions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><textarea class="input-objectif" type="text" id="discipline-Nbsanctions" cols="30" rows="5"   <?= $validationStatus['validation1']==0?'disabled':''?>  ><?php echo $discipline["nb_sanctions"]?></textarea></td>
                                    <td><textarea class="input-objectif" type="text" id="discipline-Obsanctions"  cols="30" rows="5" <?= $validationStatus['validation1']==0?'disabled':''?> ><?php echo $discipline["obj_sanctions"]?></textarea></td>
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
                                    <td><textarea class="input-objectif" type="text" id="discipline-assiduite" cols="30" rows="5" <?= $validationStatus['validation1']==0?'disabled':''?> ><?php echo $discipline["assiduite"]?></textarea></td>
                                    <td><textarea class="input-objectif" type="text" id="discipline-commentaire"  cols="30" rows="5" <?= $validationStatus['validation1']==0?'disabled':''?> ><?php echo $discipline["commentaire"]?></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                </div>   
                <?php if ($validation['validation1']==1): ?>
                    <button onclick="sendDiscipline()" class="button-validion">Valider</button>
                <?php endif; ?> 
            <?php endif; ?> 
        </div>
    <?php endif; ?>

    
</div>
<footer>
        <div id="snackbar">Some text some message..</div>
    </footer>
</html>

<script>
    function selectFilter(element){
        element.classList.toggle('selected-filter');
        applyFilters();
    }
    function getColumnIndex(category) {
        const columnMap = {
            "Auto Evaluation :": 2,
            "Evaluation N+1 :": 3,
            "Evaluation N+2 :": 4,
            "Evaluation RH :": 5,
            "Evaluation DG :": 6
        };
        return columnMap[category] || -1;
    }
    function applyFilters() {
        const table = document.querySelector("#table-etat");
        const tableRows = table.querySelectorAll("tbody tr");

        let activeFilters = {};

        document.querySelectorAll(".selected-filter").forEach(selected => {
            let category = selected.closest(".filter-row").querySelector(".filter-title").textContent.trim();
            let value = selected.textContent.trim();

            if (!activeFilters[category]) {
                activeFilters[category] = [];
            }
            activeFilters[category].push(value);
        });

        tableRows.forEach(row => {
            let showRow = true;

            Object.keys(activeFilters).forEach(category => {
                let columnIndex = getColumnIndex(category);
                let cellText = row.children[columnIndex]?.textContent.trim();

                if (activeFilters[category].length > 0 && !activeFilters[category].includes(cellText)) {
                    showRow = false;
                }
            });

            row.style.display = showRow ? "" : "none";
        });
    }
    function validationDG(){

        let x = document.getElementById("snackbar");
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'xhr/updateValidationDG.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        var data = {
            val: 1
        };
        xhr.send(JSON.stringify(data));
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    x.innerHTML="Evaluation validée !"
                    x.className = "show success-message";
                    setTimeout(function(){ x.className = x.className.replace("show success-message", ""); }, 3000);
                } else {
                    x.innerHTML="Erreur lors de la validation !"
                    x.className = "show error-message";
                    setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
                }
            }
        };
    }
    function sendDiscipline(){
        
        let valid=true
        const errors =document.querySelectorAll('.objectif-error')
        errors.forEach(element => {
            element.classList.remove('objectif-error');
        })

        let x = document.getElementById("snackbar");
        var nb_sanctions = document.getElementById('discipline-Nbsanctions').value;
        if(nb_sanctions==""){
                valid=false
                document.getElementById('discipline-Nbsanctions').classList.add("objectif-error")
            }
        var obj_sanctions = document.getElementById('discipline-Obsanctions').value;
        if(obj_sanctions==""){
                valid=false
                document.getElementById('discipline-Obsanctions').classList.add("objectif-error")
            }
        var assiduite = document.getElementById('discipline-assiduite').value;
        if(assiduite==""){
                valid=false
                document.getElementById('discipline-assiduite').classList.add("objectif-error")
            }
        var commentaire = document.getElementById('discipline-commentaire').value;
        if(commentaire==""){
                valid=false
                document.getElementById('discipline-commentaire').classList.add("objectif-error")
            }
        if (!valid){
            x.innerHTML="Merci de renseigner tous les champs !"
            x.className = "show error-message";
            setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
            return
        }
        var year = <?= $annee ?>;
        var data = {
            nb_sanctions: nb_sanctions,
            obj_sanctions: obj_sanctions,
            assiduite: assiduite,
            commentaire: commentaire
        };
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'xhr/updateDiscipline.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(data));
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    x.innerHTML="Evaluation validée !"
                    x.className = "show success-message";
                    setTimeout(function(){ x.className = x.className.replace("show success-message", ""); }, 3000);
                } else {
                    x.innerHTML="Erreur lors de la validation !"
                    x.className = "show error-message";
                    setTimeout(function(){ x.className = x.className.replace("show error-message", ""); }, 3000);
                }
            }
        };

    }
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