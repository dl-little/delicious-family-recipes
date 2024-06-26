<div class="wrap" id="dfr-settings-admin">
	<h1 class="wp-heading-inline"><?php echo get_admin_page_title(); ?></h1>
	<?php
	if ( ! empty( $_GET ) && ! empty( $_GET[ 'settings-updated' ] ) ) {
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php _e('Settings saved.') ?></strong></p>
		</div>
		<?php
	}
	?>
	<form action="options.php" method="post">
		<?php
			settings_fields( $this->page_slug );
			do_settings_sections( $this->page_slug );
			submit_button( __( 'Save Settings' ) );
		?>
	</form>
</div>
