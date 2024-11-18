<div class="box tile" id="student" data-uid="<?= $candidat["uid"] ?>">
	<div class="names">
		<?php
			if (isset($crown) && $crown === true) {
		?>
			<i class="ri-vip-crown-line gold"></i>
		<?php
			}
		?>

		<?php
			if (isset($crown) && $crown === false) {
		?>
			<i class="ri-vip-crown-line silver"></i>
		<?php
			}
		?>

		<p><?= ucfirst($candidat["name"]) ?></p>
		<p><?= ucfirst(substr($candidat["surname"], 0, 2)) . "." ?></p>
	</div>
	<p class="vote" style="display: none" id="vote" data-uid="<?= $candidat["uid"] ?>"></p>
</div>
