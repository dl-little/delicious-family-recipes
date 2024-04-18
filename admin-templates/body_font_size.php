<input 
    type="number"
    name="<?php echo self::$prefix . 'body_font_size'; ?>"
    id="<?php echo self::$prefix . 'body_font_size'; ?>"
    value="<?php echo DFR\Admin::get_option( 'body_font_size', 18 ); ?>"
    min="16"
    max="28"
/>
