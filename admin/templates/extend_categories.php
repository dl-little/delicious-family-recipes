<input 
	type="checkbox"
	name="<?php echo self::$prefix . 'extend_categories'; ?>"
	id="<?php echo self::$prefix . 'extend_categories'; ?>"
	<?php
		if ( DFR\Admin::get_option( 'extend_categories', true ) ) {
			echo 'checked';
		}
	?> 
/>