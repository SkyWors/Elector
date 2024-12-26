<?php
	use App\Configs\Path;
	use App\Utils\Lang;
	use App\Utils\System;
?>

<select class="lang_selection" id="lang_selection">

<?php
	foreach (System::getFiles(Path::PUBLIC . "/langs") as $file) {
		$file = str_replace(search: ".json", replace: "", subject: $file);
		if ($file === $_COOKIE["LANG"]) {
			echo "<option value='" . $file . "' selected>" . mb_substr(string: Lang::nameFormat(name: $file), start: 0, length: 3) . "</option>";
		} else {
			echo "<option value='" . $file . "'>" . mb_substr(string: Lang::nameFormat(name: $file), start: 0, length: 3) . "</option>";
		}
	}
?>

</select>
