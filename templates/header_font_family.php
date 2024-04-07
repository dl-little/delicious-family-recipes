<?php $font_families = DFR\Fonts::get_font_families(); ?>

<select name="<?php echo self::$prefix . 'header_font_family'; ?>" id="<?php echo self::$prefix . 'header_font_family'; ?>">
	<?php
		foreach( $font_families as $font ) {
		?>
			<option
				value="<?php echo $font['fontFamily']; ?>"
				<?php
					if ( get_option( self::$prefix . 'header_font_family' ) === $font['fontFamily'] ) {
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
