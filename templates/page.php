<div class="wrap" id="dfr-settings-admin">
	<span class="dashicons dashicons-admin-customizer"></span>
	<h1 class="wp-heading-inline"><?php echo get_admin_page_title(); ?></h1>
	<form action="options.php" method="post">
		<?php
			settings_fields( $this->page_slug );
			do_settings_sections( $this->page_slug );
			submit_button( __( 'Save Settings' ) );
		?>
	</form>
</div>
