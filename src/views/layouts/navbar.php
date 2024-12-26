<?php
	use App\Configs\Path;
	use App\Configs\Role;
	use App\Models\Repositories\UserRepository;
	use App\Utils\Lang;
?>

<nav class="box">
	<div class="item logo">
		<a href="/" title="<?= Lang::translate(key: "NAVBAR_HOME") ?>"><i class="ri-notification-3-line"></i>Elector</a>
	</div>
	<div class="action">
		<div class="item">
			<a href="/" title="<?= Lang::translate(key: "NAVBAR_SEARCH") ?>"><i class="ri-search-line"></i> <?= Lang::translate(key: "NAVBAR_SEARCH") ?></a>
		</div>

		<?php if (!isset($_SESSION["user"])) { ?>

		<div class="item">
			<a href="/login"><i class="ri-user-line"></i> <?= Lang::translate(key: "NAVBAR_LOGIN") ?></a>
		</div>

		<?php } else { ?>

			<?php if (!empty(array_intersect(UserRepository::getRoles(uid: $_SESSION["user"]["uid"]), [Role::TEACHER, Role::ADMINISTRATOR]))) { ?>

		<div class="item">
			<a href="/dashboard" title="<?= Lang::translate(key: "NAVBAR_DASHBOARD") ?>"><i class="ri-speed-up-line"></i> <?= Lang::translate(key: "NAVBAR_DASHBOARD") ?></a>
		</div>

			<?php } else { ?>

		<div class="item">
			<a href="/?group=<?= UserRepository::getGroup(uid: $_SESSION["user"]["uid"]) ?>" title="<?= Lang::translate(key: "NAVBAR_GROUP") ?>"><i class="ri-group-line"></i> <?= Lang::translate(key: "NAVBAR_GROUP") ?></a>
		</div>

			<?php } ?>

		<div class="item">
			<a href="/disconnect" title="<?= Lang::translate(key: "NAVBAR_DISCONNECT") ?>"><i class="ri-logout-box-r-line"></i></a>
		</div>

		<?php } ?>

		<div class="item">
			<?php include Path::COMPONENTS . "/actions/lang_selection.php"; ?>
		</div>
		<div class="item">
			<?php include Path::COMPONENTS . "/actions/theme_button.php"; ?>
		</div>
	</div>
</nav>
