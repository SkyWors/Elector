<?php
	use App\Configs\Path;
	use App\Utils\ApplicationData;
?>

<div class="group_container">
	<?php
		foreach (ApplicationData::getGroups() as $element) {
			include Path::COMPONENTS . "/tiles/group_tile.php";
		}
	?>

	<div class="box tile" id="add">
		<i class="ri-add-large-line"></i>
	</div>
</div>
