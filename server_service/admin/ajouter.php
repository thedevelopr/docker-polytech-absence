<?php

$warningMessage = "";
$errorMessage = "";
$successMessage = "";

$is_enregister = isset($_POST['enregistrer']);

if ($is_enregister) {
	require_once('admin/enregistrer.php');
}
if (!empty($successMessage)) {
	echo "<div class=\"alert alert-success\">" . $successMessage . "</div>";
} else if (!empty($errorMessage)) {
	echo "<div class=\"alert alert-danger\">" . $errorMessage . "</div>";
} else {
	if (!empty($warningMessage)) {
		echo "<div class=\"alert alert-warning\">" . $warningMessage . "</div>";
	}

	if ($_SESSION[$s]["rc"]["type"] == 'cm') {
		$sql = "select t1.id_u as id_e, t1.nom, t1.prenom from utilisateur t1 " .
			"join ametice t2 on t1.id_u=t2.id_e where t2.id_c=?";
		$liste_etudiants = sqlQueryAll($sql, [$_SESSION[$s]["rc"]["id_c"]]);
	} else {
		$sql = "select t1.id_u as id_e, t1.nom, t1.prenom from utilisateur t1 join etudiant t2 on t2.id_e=t1.id_u " .
			"join ametice t3 on t2.id_e=t3.id_e where t3.id_c=? and t2.groupe=?";
		$liste_etudiants = sqlQueryAll($sql, [$_SESSION[$s]["rc"]["id_c"], $_SESSION[$s]["rc"]["groupe"]]);
	}

?>
	<div class="card mt-3">
		<h5 class="card-header">Enregister l'absence</h5>
		<div class="card-body">

			<form method="POST" action="index.php?req=rechercherCours">
				<div class="row g-3">
					<div class="col-md-6">
						<div class="form-floating">
							<input type="text" class="form-control" id="filiere" name="filiere_nom" placeholder="Filière" value="<?php echo $_SESSION[$s]["rc"]["filiere_nom"]; ?>" readonly="readonly">
							<label for="filiere">Filière</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<input type="text" class="form-control" id="annee" name="annee" placeholder="Année" value="<?php echo $_SESSION[$s]["rc"]["annee"]; ?>" readonly="readonly">
							<label for="annee">Année</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="cours" name="cours_nom" placeholder="Cours" value="<?php echo $_SESSION[$s]["rc"]["cours_nom"]; ?>" readonly="readonly">
							<label for="cours">Cours</label>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="type" name="type" placeholder="Type" value="<?php echo $_SESSION[$s]["rc"]["type"]; ?>" readonly="readonly">
							<label for="type">Type</label>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="groupe" name="groupe" placeholder="Groupe" value="<?php if ($_SESSION[$s]["rc"]["groupe"] == 0) {
																																echo "Tous les groupes";
																															} else {
																																echo $_SESSION[$s]["rc"]["groupe"];
																															} ?>" readonly="readonly">
							<label for="groupe">Groupe</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
							<input type="date" id="date" class="form-control" name="date" placeholder="La date">
							<label for="date">Date</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
							<select name="heure" class="form-select" id="heure">
								<?php
								for ($i = 8; $i < 21; $i++) {
									echo "<option value=\"$i\">$i</option>";
								}
								?>
							</select>
							<label for="heure">Heure</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-floating">
							<select name="minutes" class="form-select" id="minutes">
								<?php
								for ($i = 0; $i < 60; $i += 15) {
									echo "<option value=\"$i\">$i</option>";
								}
								?>
							</select>
							<label for="minutes">Minutes</label>
						</div>
					</div>
				</div>

				<table class="table table-hover table-bordered mt-4">
					<thead class="table-light">
						<tr>
							<th>Nom et prénom</th>
							<th>Cocher si absent</th>
						</tr>
					</thead>

					<tbody>
						<?php for ($i = 0; $i < count($liste_etudiants); $i++) { ?>
							<tr>
								<?php
								echo "<td class=\"col-md-10\">" . $liste_etudiants[$i]['prenom'] . " " . $liste_etudiants[$i]['nom'] . " </td>";
								echo "<td class=\"col-md-2\"><input type=\"checkbox\" class=\"form-check-input\" name=\"absents[]\" value=\"" . $liste_etudiants[$i]['id_e'] . "\"";
								if ($is_enregister) {
									$found = false;
									foreach ($absents as $abs) {
										if ($abs == $liste_etudiants[$i]['id_e']) {
											$found = true;
										}
									}
									if ($found) {
										echo "checked/>";
									} else {
										echo "/>";
									}
								} else {
									echo "/>";
								}
								echo "</td>";
								?>
							</tr>
						<?PHP } ?>
					</tbody>
				</table>

				<div class="mt-2">
					<a href="index.php?req=rechercherCours" class="btn btn-danger">Annuler</a>
					<button class="btn btn-primary" name="enregistrer" type="submit">Enregistrer</button>
				</div>
			</form>

		</div>
	</div>
<?php
}
?>