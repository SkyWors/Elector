<?php
	http_response_code(404);

	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/header.php";
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/navbar.php";
?>

<div class="box error_page">
	<h1>Cette page est introuvable.</h1>
	<?php
		$back = "/";
		include $_SERVER["DOCUMENT_ROOT"] . "/template/action/back_button.php";
	?>
</div>

<?php
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/footer.php";
?>
