<?php
	use
		Elector\Group,
		Elector\Utils;

	if (isset($_GET["group"])) {
		$group = new Group($_GET["group"]);
		$name = $group->getInformations()["name"] ?? null;
		if (!isset($name)) {
			Utils::head("/notfound");
		}
		$headerTitle = $name;
	}

	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/header.php";
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/navbar.php";
?>

<?php
	if (isset($_GET["group"])) {
		include __DIR__ . "/election.php";
	} else {
		include $_SERVER["DOCUMENT_ROOT"] . "/template/element/search.php";
	}
?>

<script src="./public/script/elector.js"></script>

<?php
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/footer.php";
?>
