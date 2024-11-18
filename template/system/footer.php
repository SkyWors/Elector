</div>

<?php
	use
		Elector\Utils;
?>

<footer>
	<i class="ri-notification-3-line"></i> Elector - Développé avec 🧡 par <a href="https://github.com/SkyWors" target="_blank">Erick P.</a> <i class="ri-external-link-line"></i>
	<a class="link" href="https://github.com/SkyWors/Elector/tree/<?= Utils::getGitCommit() ?>" target="_blank"><i class="ri-github-fill"></i> <?= Utils::getGitBranch() . " #" . substr(Utils::getGitCommit(), 0, 7) ?></a>
</footer>

</body>
</html>
