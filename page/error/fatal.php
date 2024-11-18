<?php
	http_response_code(500);

	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/header.php";
?>

<div class="box error_page">
	<h1>Une erreur est survenue.</h1>
	<?php
		$back = "/";
		include $_SERVER["DOCUMENT_ROOT"] . "/template/action/back_button.php";
	?>
</div>

<?php
	include $_SERVER["DOCUMENT_ROOT"] . "/template/system/footer.php";
?>
