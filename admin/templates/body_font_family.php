<?php 

$font_families = DFR\Fonts::get_font_families(); 

if ( empty( $font_families ) ) {
	?>
		<p><strong><?php _e('Missing font file.') ?></strong></p>
	<?php
} else {
	?>
		<select name="<?php echo self::$prefix . 'body_font_family'; ?>" id="<?php echo self::$prefix . 'body_font_family'; ?>">
			<?php
				foreach( $font_families as $font ) {
				?>
					<option
						value="<?php echo $font['fontFamily']; ?>"
						<?php
							if ( DFR\Admin::get_option( 'body_font_family', "-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif" ) === $font['fontFamily'] ) {
								echo 'selected';
							}
						?>
					>
						<?php echo $font['name']; ?>
					</option>
				<?php
				}
			?>
		</select>
	<?php 
}
