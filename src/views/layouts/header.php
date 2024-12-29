<?php
	use App\Utils\Lang;
?>

<html lang="<?= Lang::translate(key: "MAIN_LANG") ?>" data-theme="light">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($GLOBALS["title"]) ? $GLOBALS["title"] : APP_NAME . " - " . Lang::translate(key: "MAIN_ERROR") ?></title>
	<link rel="stylesheet" href="/styles/main.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css">
</head>
<body>
	<div class="main">
	<noscript>
		<div class="no_script">
			<?= Lang::translate(key: "MAIN_NO_SCRIPT") ?>
		</div>
	</noscript>
