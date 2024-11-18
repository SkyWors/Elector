<?php
	use
		Elector\Group,
		Elector\Utils;

	if (!isset($_SESSION["userUID"])) {
		include $_SERVER["DOCUMENT_ROOT"] . "/page/error/notfound.php";
	}

	$userInformations = $user->getInformations();
	if ($userInformations["id_role"] != ROLE_TEACHER) {
		include $_SERVER["DOCUMENT_ROOT"] . "/page/error/notfound.php";
	}

	if (isset($_GET["group"])) {
		$group = new Group($_GET["group"]);
		$name = $group->getInformations()["name"];
		$headerTitle = $name;
	}

	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/header.php";
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/navbar.php";
?>

<?php
	if (isset($_GET["group"])) {
		include __DIR__ . "/group.php";
	} else {
		include __DIR__ . "/group_list.php";
	}
?>

<script src="./public/script/elector.js"></script>
<script src="./public/script/dashboard.js"></script>

<?php
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/footer.php";
?>
