<?php
	use App\Utils\GitHub;
	use App\Utils\Lang;
?>

	</div>

	<footer>
		<i class="ri-notification-3-line"></i> Elector - <?= Lang::translate(key: "FOOTER_MESSAGE") ?> <a href="https://github.com/SkyWors" target="_blank">Erick P.</a> <i class="ri-external-link-line"></i>
		<a class="link" href="https://github.com/SkyWors/Elector/tree/<?= GitHub::getCommit() ?>" target="_blank"><i class="ri-github-fill"></i> <?= GitHub::getBranch() . " #" . substr(string: GitHub::getCommit(), offset: 0, length: 7) ?></a>
	</footer>
</body>
</html>
