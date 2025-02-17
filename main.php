<?php
ini_set('session.gc_maxlifetime', 864000);
ini_set('session.cookie_lifetime', 864000);
session_start();
$annee=date('Y');


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
    $sql="SELECT id_ligne,objectif,indicateur,realisation,score,resultat_analyse FROM objectifs where user='".$user."' and annee=".($year-1).""; 
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
            <div class="accordion-header">
                <span>I - ANALYSE DE LA PERIODE ECOULEE</span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content ">
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
                                        <input class="input-objectfs-avant centred" type="number" placeholder="0 - 100" >
                                    </td>
                                    <td class="td-score">
                                        <select name="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>" id="obj-score-<?= htmlspecialchars(($objectif['id_ligne']+1))?>">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea class="input-objectfs-avant" placeholder="Commentaire"  ></textarea>
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
                    
                    
                    
                    <table id="objectifs-convenus" border="1">
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
                            <tr>
                                <td><span id="ss-1">Capacité à travailler en équipe</span></td>
                                <td><input type="radio" id="ss-point-fort-1" name="type-point-1" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-1" name="type-point-1" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-1" id="ss-score-1">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-1" name="ss-comment-1" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-2">Prise d'initiative</span></td>
                                <td><input type="radio" id="ss-point-fort-2" name="type-point-2" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-2" name="type-point-2" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-2" id="ss-score-2">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-2" name="ss-comment-2" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-3">Accompagner le changement</span></td>
                                <td><input type="radio" id="ss-point-fort-3" name="type-point-3" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-3" name="type-point-3" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-3" id="ss-score-3">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-3" name="ss-comment-3" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-4">Autonomie dans la résolution des problèmes</span></td>
                                <td><input type="radio" id="ss-point-fort-4" name="type-point-4" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-4" name="type-point-4" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-4" id="ss-score-4">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-4" name="ss-comment-4" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-5">Communication et Feed-Back</span></td>
                                <td><input type="radio" id="ss-point-fort-5" name="type-point-5" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-5" name="type-point-5" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-5" id="ss-score-5">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-5" name="ss-comment-5" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-6">Être orienté client</span></td>
                                <td><input type="radio" id="ss-point-fort-6" name="type-point-6" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-6" name="type-point-6" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-6" id="ss-score-6">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-6" name="ss-comment-6" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>



                    <table id="objectifs-convenus" border="1">
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
                                <td><input type="radio" id="ss-point-fort-7" name="type-point-7" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-7" name="type-point-7" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-7" id="ss-score-7">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-7" name="ss-comment-7" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-8">Relation avec les collègues</span></td>
                                <td><input type="radio" id="ss-point-fort-8" name="type-point-8" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-8" name="type-point-8" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-8" id="ss-score-8">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-8" name="ss-comment-8" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-9">Relation avec la hiérarchie </span></td>
                                <td><input type="radio" id="ss-point-fort-9" name="type-point-9" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-9" name="type-point-9" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-9" id="ss-score-9">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-9" name="ss-comment-9" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <table id="objectifs-convenus" border="1">
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
                                <td><input type="radio" id="ss-point-fort-10" name="type-point-10" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-10" name="type-point-10" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-10" id="ss-score-10">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-10" name="ss-comment-10" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-11">Gérer les conflits</span></td>
                                <td><input type="radio" id="ss-point-fort-11" name="type-point-11" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-11" name="type-point-11" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-11" id="ss-score-11">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-11" name="ss-comment-11" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-12">Structurer l'activité IT</span></td>
                                <td><input type="radio" id="ss-point-fort-12" name="type-point-12" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-12" name="type-point-12" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-12" id="ss-score-12">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-12" name="ss-comment-12" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-13">Déléguer (Pour les manager)</span></td>
                                <td><input type="radio" id="ss-point-fort-13" name="type-point-13" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-13" name="type-point-13" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-13" id="ss-score-13">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-13" name="ss-comment-13" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-14">Transversalité managériale (Pour les manager)</span></td>
                                <td><input type="radio" id="ss-point-fort-14" name="type-point-14" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-14" name="type-point-14" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-14" id="ss-score-14">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-14" name="ss-comment-14" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="ss-6">Être orienté client</span></td>
                                <td><input type="radio" id="ss-point-fort-6" name="type-point-6" value="fort"/></td>
                                <td><input type="radio" id="ss-point-progret-6" name="type-point-6" value="progret"/></td>
                                <td class="td-score">
                                    <select name="ss-score-6" id="ss-score-6">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="input-objectfs-avant" id="ss-comment-6" name="ss-comment-6" placeholder="Commentaire"  ></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
            <div class="accordion-header">
                <span>II - OBJECTIFS ET AXE DE DEVELOPPEMENT PROFESSIONNEL DE L'ANNÉE À VENIR  </span>
                <span class="arrow">&#9660;</span>
            </div>
            <div class="accordion-content open">
                <div class="section-header">
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
                                <td width="50%">Ojectif1</td>
                                <td width="10%">jan-26</td>
                                <td width="37%">Commaad</td>
                                <td width="3%"><button disabled>X</button></td>
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

    </footer>
</html>




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


<script>
        const table = document.getElementById('dynamicTable').querySelector('tbody');
        const addRowBtn = document.getElementById('addRowBtn');

        addRowBtn.addEventListener('click', () => {
            const rowCount = table.rows.length + 1;
            const row = table.insertRow();
            row.innerHTML = `
                <td>${rowCount}</td>
                <td>Sample Data</td>
                <td>Sample Data</td>
                <td><button onclick="removeRow(this)">X</button></td>
            `;
            updateRemoveButtons();
        });

        function removeRow(button) {
            if (table.rows.length > 1) {
                const row = button.closest('tr');
                table.removeChild(row);
                updateRowNumbers();
            }
        }

        function updateRowNumbers() {
            [...table.rows].forEach((row, index) => {
                row.cells[0].textContent = index + 1;
            });
        }

        function updateRemoveButtons() {
            const rows = table.rows;
            [...rows].forEach((row, index) => {
                const removeButton = row.cells[2].querySelector('button');
                if (rows.length === 1) {
                    removeButton.disabled = true;
                } else {
                    removeButton.disabled = false;
                }
            });
        }

        // Initialize the table with correct button states
        updateRemoveButtons();
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