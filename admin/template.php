<div class="wrap">
	<h1>Internexus Watcher</h1>
	<form method="post" action="options.php">
	<?php
		// This prints out all hidden setting fields
		settings_fields( 'watcher-plugin' );
		do_settings_sections( 'watcher-plugin' );
		submit_button();
	?>
	</form>
</div>
