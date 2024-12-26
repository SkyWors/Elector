<?php
	use App\Utils\Lang;
?>

<div class="add_group_container" id="add_group_container" style="display: none">
	<form id="form" method="POST" enctype="multipart/form-data">
		<h1><?= Lang::translate(key: "DASHBOARD_ADD_GROUP") ?></h1>
		<input
			class="box"
			id="group_name"
			type="text"
			name="name"
			max="100"
			placeholder="<?= Lang::translate(key: "MAIN_OBJECT_NAME") ?>"
			required
		>
		<input
			id="file"
			type="file"
			name="import"
			accept=".csv"
			style="display: none"
			required
		>
		<label class="file" id="file_label" for="file" title="Importer" style="display: none"><i class="ri-download-2-line"></i> <?= Lang::translate(key: "DASHBOARD_ADD_STUDENT") ?></label>
	</form>
</div>
