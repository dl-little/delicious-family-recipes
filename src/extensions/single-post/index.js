import { registerBlockVariation } from '@wordpress/blocks';
const MY_VARIATION_NAME = 'dfr/single-post';

registerBlockVariation( 'core/query', {
	name: MY_VARIATION_NAME,
	title: 'Delicious Family Recipes Single Post Block',
	description: 'Provides a single post block for use on the homepage.',
	isActive: ( { namespace, query } ) => {
		return namespace === MY_VARIATION_NAME && query.postType === 'post';
	},
	icon: 'table-col-before',
	attributes: {
		className: 'dfr-single-post',
		namespace: MY_VARIATION_NAME,
		query: {
			perPage: 1,
			pages: 0,
			offset: 0,
			postType: 'post',
			author: '',
			exclude: [],
			sticky: 'exclude',
			inherit: false,
		},
	},
	innerBlocks: [
		[
			'core/post-template',
			{
				align: 'wide',
				style: {
					spacing: {
						blockGap: '15px',
					},
				},
			},
			[
				[
					'core/columns',
					{
						style: {
							spacing: {
								margin: {
									top: '0px',
									bottom: '0px',
								},
							},
						},
					},
					[
						[
							'core/column',
							{
								width: '50%',
							},
							[
								[
									'core/post-featured-image',
									{
										isLink: true,
										aspectRatio: '1/1',
									},
								],
							],
						],
						[
							'core/column',
							{
								width: '50%',
								verticalAlignment: 'center',
							},
							[
								[
									'core/group',
									{
										layout: {
											type: 'flex',
											orientation: 'vertical',
										},
										style: {
											spacing: {
												blockGap: '15px',
											},
										},
									},
									[
										[
											'core/post-terms',
											{
												term: 'category',
											},
										],
										[
											'core/post-title',
											{
												isLink: true,
												level: 3,
											},
										],
										[
											'core/post-excerpt',
											{
												excerptLength: 15,
												moreText: 'Get the Recipe',
											},
										],
									],
								],
							],
						],
					],
				],
			],
		],
		[ 'core/query-no-results' ],
	],
	scope: [ 'inserter' ],
} );
