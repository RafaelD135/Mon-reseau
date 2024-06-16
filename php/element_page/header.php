<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container-fluid">
		<a class="navbar-brand" href="index.php">Mon Reseau</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<div class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="index.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="contact.php">Contact</a>
				</li>

				<?php if (isset($_SESSION['LOGGED_USER'])) : ?>
					<li class="nav-item">
						<a class="nav-link" href="php/code_php/logout.php">Déconnexion</a>
					</li>
				<?php else: ?>
					<li>
						<a href="login.php">
							<button class="btn btn-primary" href="login.php">Connexion</button>
						</a>
					</li>
					<li>
						<a href="register.php">
							<button class="btn btn-secondary">Inscription</button>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
