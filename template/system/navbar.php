<nav class="box" id="navbar">
	<div class="item logo">
		<a href="/" title="Accueil"><i class="ri-notification-3-line"></i>Elector</a>
	</div>
	<div class="action">
		<div class="item">
			<a href="/" title="Recherche"><i class="ri-search-line"></i>Recherche</a>
		</div>
		<?php
			if (isset($_SESSION["userUID"])) {
				$userInformations = $user->getInformations();
				if ($userInformations["id_role"] === ROLE_TEACHER) {
		?>
		<div class="item">
			<a href="/dashboard" title="Dashboard"><i class="ri-speed-up-line"></i>Dashboard</a>
		</div>
		<?php
				} else {
				?>
		<div class="item">
			<a href="/?group=<?= $userInformations["uid_group"] ?>" title="Ma classe"><i class="ri-group-line"></i>Ma classe</a>
		</div>
		<?php
				}
		?>
		<div class="item">
			<a href="/logout" title="Déconnexion"><i class="ri-logout-box-r-line"></i>Déconnexion</a>
		</div>
		<?php
			} else {
		?>
		<div class="item">
			<a href="login" title="Connexion"><i class="ri-user-line"></i>Connexion</a>
		</div>
		<?php
			}
		?>
	</div>
</nav>
