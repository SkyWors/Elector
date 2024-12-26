<?php
	use App\Configs\Path;
	use App\Configs\Role;
	use App\Models\Repositories\UserRepository;
	use App\Utils\Lang;

	$backPath = "/";
	include Path::COMPONENTS . "/actions/back_button.php";

	$state = $groupRepo->getElectionState();
	switch ($state) {
		case 1:
			$stateMessage = Lang::translate(key: "ELECTION_FIRST_ROUND");
			break;
		case 2:
			$stateMessage = Lang::translate(key: "ELECTION_SECOND_ROUND");
			break;
		case 3:
			$stateMessage = Lang::translate(key: "MAIN_FINISH");
			break;
		default:
			$stateMessage = Lang::translate(key: "ELECTION_NO_ELECTION");
			break;
	}
?>

<h2 class="election_state_title"><?= Lang::translate(key: "MAIN_ELECTION") ?> <?= $groupRepo->getInformations()["name"] ?></h2>
<p class="election_state_subtitle"><?= $stateMessage ?></p>

<div class="election_container">
	<?php
		foreach ($groupRepo->getCandidats() as $candidat) {
			if ($state === 3 && !isset($crown)) {
				$crown = true;
			}
			include Path::COMPONENTS . "/tiles/user_tile.php";
			if ($state === 3 && !isset($crown)) {
				$crown = false;
			}
		}
	?>
</div>

<?php
	if (
		isset($_SESSION["user"]) // Check if user is connected
		&& isset($state) // Check if election is started
		&& $state != 3 // Check if election is not finished
		&& array_intersect($roles, [Role::USER]) // Check if user have correct permissions
		&& UserRepository::getGroup(uid: $_SESSION["user"]["uid"]) === $_GET["group"] // Check if user is in the group
	) {
		if ($userRepo->isVote() === null) {
?>
	<div class="election_button_container">
		<button class="election_submit" id="submit"><i class="ri-article-line"></i> <?= Lang::translate(key: "MAIN_VOTE") ?></button>
	</div>
<?php
		}
	}
?>

<script>var round = "<?= $state ?>";</script>
