<?php
	use
		Elector\Utils;

	include $_SERVER["DOCUMENT_ROOT"] . "/event/add_group.php";
?>

<div class="group_container">
	<?php
		foreach (Utils::getGroups() as $element) {
			include $_SERVER["DOCUMENT_ROOT"] . "/template/element/group_tile.php";
		}
	?>

	<div class="box tile" id="add">
		<i class="ri-add-large-line"></i>
	</div>
</div>

<div class="add_group_container" id="add_group_container" style="display: none">
	<form id="form" method="POST" enctype="multipart/form-data">
		<h1>Ajouter un groupe</h1>
		<input class="box" id="group_name" type="text" name="name" max="100" placeholder="Nom" required>
		<input type="file" id="file" name="import" accept=".csv" style="display: none" required>
		<label class="file" id="file_label" for="file" title="Importer" style="display: none"><i class="ri-download-2-line"></i> Ajouter des élèves</label>
	</form>
</div>
