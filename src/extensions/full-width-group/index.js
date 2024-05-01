import { registerBlockVariation } from '@wordpress/blocks';
const MY_VARIATION_NAME = 'dfr/full-width-group';

registerBlockVariation( 'core/group', {
	name: MY_VARIATION_NAME,
	title: 'Delicious Family Recipes Full Width Group Block',
	description:
		'Provides a background color to a group for use on the homepage.',
	isActive: ( { namespace } ) => {
		return namespace === MY_VARIATION_NAME;
	},
	icon: 'align-full-width',
	attributes: {
		className: 'dfr-full-width-group',
		namespace: MY_VARIATION_NAME,
		layout: {
			type: 'flex',
			orientation: 'vertical',
			justifyContent: 'center',
		},
		style: {
			spacing: {
				padding: {
					top: '30px',
					bottom: '30px',
				},
			},
		},
	},
	innerBlocks: [ [ 'core/group' ] ],
	scope: [ 'inserter' ],
} );
