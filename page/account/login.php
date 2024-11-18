<?php
	use
		Elector\Utils;

	if (isset($_SESSION["userUID"])) {
		Utils::head("/");
	}

	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/header.php";
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/navbar.php";

	include $_SERVER["DOCUMENT_ROOT"] . "/event/login.php";
?>

<?php
	if (isset($errorMessage)) {
		include $_SERVER["DOCUMENT_ROOT"] . "/template/system/notification.php";
	}
?>

<form class="login_container" method="POST">
	<h1><i class="ri-user-line"></i> Connexion</h1>
	<input class="box" autocomplete="username" type="text" name="identifiant" value="<?= isset($_POST["identifiant"]) ? $_POST["identifiant"] : null ?>" placeholder="Identifiant" required>
	<div class="password">
		<input class="box" autocomplete="password" type="password" name="password" value="<?= isset($_POST["password"]) ? $_POST["password"] : null ?>" placeholder="Mot de passe" required>
		<i class="ri-eye-off-line"></i>
	</div>
	<button type="submit"><i class="ri-lock-unlock-line"></i> Connexion</button>
</form>

<script src="./public/script/elector.js"></script>

<?php
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/footer.php";
?>
