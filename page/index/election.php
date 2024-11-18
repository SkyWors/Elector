<?php
	$back = "/";
	include $_SERVER["DOCUMENT_ROOT"] . "/template/action/back_button.php";

	$state = $group->getElectionState();
	switch ($state) {
		case 1:
			$stateMessage = "Premier tours";
			break;
		case 2:
			$stateMessage = "Deuxième tours";
			break;
		case 3:
			$stateMessage = "Terminé";
			break;
		default:
			$stateMessage = "Aucunes éléctions commencées.";
			break;
	}
?>

<h2 class="election_state_title">Eléction <?= $name ?></h2>
<p class="election_state_subtitle"><?= $stateMessage ?></p>

<div class="election_container">
	<?php
		foreach ($group->getCandidats() as $candidat) {
			if ($state === 3 && !isset($crown)) {
				$crown = true;
			}
			include $_SERVER["DOCUMENT_ROOT"] . "/template/element/user_tile.php";
			if ($state === 3 && !isset($crown)) {
				$crown = false;
			}
		}
	?>
</div>

<?php
	if (isset($state) && isset($_SESSION["userUID"]) && $state != 3 && $userInformations["id_role"] === ROLE_STUDENT) {
		if ($user->isVote($user->getInformations()["uid_group"]) === null) {
?>
<div class="election_button_container">
	<button class="election_submit" id="submit"><i class="ri-article-line"></i> Voter !</button>
</div>
<?php
		}
?>

<script>const uid = "<?= $_SESSION["userUID"] ?>";</script>

<?php
	}
?>

<script>var round = "<?= $state ?>";</script>

<script src="https://cdn.jsdelivr.net/npm/@tsparticles/confetti@3.4.0/tsparticles.confetti.bundle.min.js"></script>
<script src="./public/script/elector.js"></script>
<script src="./public/script/election.js"></script>
