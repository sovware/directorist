<?php
$user = wp_get_current_user();
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

<main class="font-inter directorist-mt-24 border-box">
	<div class="directorist-container-fluid">
		<div class="directorist-row">
			<div class="directorist-col-12">

				<?php if ( directorist_licensing_is_connected() ) : ?>
					<?php include_once 'not-connected.php';?>
				<?php else : ?>
					<?php include_once 'connected.php';?>
				<?php endif; ?>

				<?php if ( directorist_licensing_is_connected() ) : ?>
					<?php include_once 'not-connected-themes-extensions.php';?>
				<?php else : ?>
					<?php include_once 'connected-themes-extensions.php';?>
				<?php endif; ?>

				<?php include_once 'pagination.php';?>
				
			</div>
		</div>
	</div>

	<?php include_once 'connected-update-all.php';?>

</main>