<?php
	$back = "/dashboard";
	include $_SERVER["DOCUMENT_ROOT"] . "/template/action/back_button.php";
?>

<?php
	if ($group->getElection() == null) {
?>
		<p>Aucune éléction en cours pour le groupe <?= $name ?>.</p>
		<button id="createElection">Créer une éléction</button>
<?php
	} else {
		if ($group->getElectionState() != 3) {
			if ($group->getElectionState() === 1) {
?>
	<button id="stateElection" value="2">Etape suivante</button>
<?php
			}
?>
	<button id="stateElection" value="3">Terminer l'élection</button>
<?php
		} else {
?>
		<p>Election terminé.</p>
<?php
		}
	}
?>
