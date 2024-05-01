import { __ } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';

export function standardizeCats( cats ) {
	return cats?.map( ( cat ) => ( {
		value: cat.id,
		label: cat.name,
	} ) );
}

export function renderCategoryName( name ) {
	return ! name ? __( '(Untitled)' ) : decodeEntities( name ).trim();
}
