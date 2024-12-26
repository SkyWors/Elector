<?php
	use App\Configs\Path;
	use App\Utils\Lang;

	$backPath = "/dashboard";
	include Path::COMPONENTS . "/actions/back_button.php";
?>

<?php
	if ($groupRepo->getElection() == null) {
?>
		<p><?= Lang::translate(key: "DASHBOARD_NO_ELECTION") ?> <?= $groupRepo->getInformations()["name"] ?>.</p>
		<button id="createElection"><?= Lang::translate(key: "DASHBOARD_CREATE_ELECTION") ?></button>
<?php
	} else {
		if ($groupRepo->getElectionState() != 3) {
			if ($groupRepo->getElectionState() === 1) {
?>
	<button id="stateElection" value="2"><?= Lang::translate(key: "MAIN_NEXT_STEP") ?></button>
<?php
			}
?>
	<button id="stateElection" value="3"><?= Lang::translate(key: "DASHBOARD_FINISH_ELECTION") ?></button>
<?php
		} else {
?>
		<p><?= Lang::translate(key: "DASHBOARD_FINISHED_ELECTION") ?></p>
<?php
		}
	}
?>
