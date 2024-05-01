const root = document.querySelector( ':root' );
const inputs = document.querySelectorAll(
	'*[name*="dfr_"]:not([type="checkbox"]'
);

const hasExistingVar = ( target ) => {
	return !! getComputedStyle( root ).getPropertyValue( target );
};

const updateExistingVar = ( target, value ) => {
	if ( target.includes( 'size' ) ) {
		value += 'px';
	}

	root.style.setProperty( target, value );
};

inputs.forEach( ( input ) => {
	input.addEventListener( 'change', function ( e ) {
		if ( ! hasExistingVar( '--' + e.target.id.replaceAll( '_', '-' ) ) ) {
			return;
		}

		updateExistingVar(
			'--' + e.target.id.replaceAll( '_', '-' ),
			e.target.value
		);
	} );
} );
