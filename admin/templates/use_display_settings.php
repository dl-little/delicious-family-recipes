<input 
	type="checkbox"
	name="<?php echo self::$prefix . 'use_display_settings'; ?>"
	id="<?php echo self::$prefix . 'use_display_settings'; ?>"
	<?php
		if ( DFR\Admin::get_option( 'use_display_settings', true ) ) {
			echo 'checked';
		}
	?> 
/>
