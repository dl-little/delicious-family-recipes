import { registerBlockVariation } from '@wordpress/blocks';
const MY_VARIATION_NAME = 'dfr/section-header';

registerBlockVariation( 'core/group', {
	name: MY_VARIATION_NAME,
	title: 'Delicious Family Recipes Section Header Block',
	description: 'Section header for use on the homepage.',
	isActive: ( { namespace } ) => {
		return namespace === MY_VARIATION_NAME;
	},
	icon: 'heading',
	attributes: {
		className: 'dfr-section-header',
		namespace: MY_VARIATION_NAME,
		layout: {
			type: 'flex',
			orientation: 'vertical',
			justifyContent: 'center',
		},
		style: {
			spacing: {
				blockGap: '8px',
			},
		},
	},
	innerBlocks: [
		[
			'core/group',
			{
				layout: {
					type: 'flex',
					flexWrap: 'nowrap',
					justifyContent: 'center',
				},
			},
			[
				[ 'core/heading' ],
				[
					'core/spacer',
					{
						style: {
							layout: {
								flexSize: null,
								selfStretch: 'fill',
							},
						},
					},
				],
			],
		],
		[
			'core/group',
			{
				layout: {
					type: 'flex',
					flexWrap: 'nowrap',
					justifyContent: 'center',
				},
			},
			[
				[ 'core/paragraph' ],
				[
					'core/spacer',
					{
						style: {
							layout: {
								flexSize: null,
								selfStretch: 'fill',
							},
						},
					},
				],
				[ 'core/buttons' ],
			],
		],
	],
	scope: [ 'inserter' ],
} );
