<?php
	use App\Utils\Lang;
?>

<form class="login_container" method="POST">
	<h1><?= Lang::translate(key: "LOGIN_TITLE") ?></h1>

	<input
		class="box"
		type="text"
		name="identifiant"
		value="<?= isset($_POST["identifiant"]) ? $_POST["identifiant"] : "" ?>"
		placeholder="<?= Lang::translate(key: "MAIN_IDENTIFIANT") ?>"
		require
		autofocus
	>
	<div class="password" id="password">
		<input
			class="box"
			type="password"
			name="password"
			value="<?= isset($_POST["password"]) ? $_POST["password"] : "" ?>"
			placeholder="<?= Lang::translate(key: "MAIN_PASSWORD") ?>"
			require
		>
		<i class="ri-eye-off-line"></i>
	</div>

	<button type="submit"><?= Lang::translate(key: "LOGIN_SUBMIT") ?></button>
</form>
