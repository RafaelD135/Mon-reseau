<?php
if(isset($_SESSION['LOGGED_USER']))
{
	$stmt = $mysqlClient->prepare('SELECT COUNT(*) FROM notifications WHERE idUtilisateur = :idUser AND is_read = false');
	$stmt->execute([
		'idUser' => $_SESSION['LOGGED_USER']['user_id'],
	]);
	$nbNotif = $stmt->fetchColumn();
}

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
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

				<?php if (isset($_SESSION['LOGGED_USER'])) : ?>
					<li class="nav-item">
						<a class="nav-link" href="php/code_php/logout.php">Déconnexion</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="configprofil.php">Paramètre</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="profil.php">Profil</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="message.php">Messages</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="notifications.php">Notifications
							<?php if($nbNotif > 0)
							{
								echo '<i class="bi bi-record-fill text-danger"></i>';
								echo '(' . $nbNotif. ')';
							}
							;?> 
						</a> 
					</li>
				<?php else: ?>
					<li class="nav-item">
						<a href="login.php">
							<button class="btn btn-primary me-1" href="login.php">Connexion</button>
						</a>
					</li>
					<li class="nav-item">
						<a href="register.php">
							<button class="btn btn-secondary">Inscription</button>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
