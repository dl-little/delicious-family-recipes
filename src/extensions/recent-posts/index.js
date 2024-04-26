import { registerBlockVariation } from '@wordpress/blocks';
const MY_VARIATION_NAME = 'dfr/recent-posts';

registerBlockVariation( 'core/query', {
	name: MY_VARIATION_NAME,
	title: 'Delicious Family Recipes Recent Post Block',
    description: 'Recent posts for use on the homepage.',
    isActive: ( { namespace, query } ) => {
		return (
			namespace === MY_VARIATION_NAME
			&& query.postType === 'post'
		);
	},
	icon: 'calendar',
    attributes: {
		className: 'dfr-recent-posts',
        namespace: MY_VARIATION_NAME,
		query: {
			perPage: 3,
			pages: 0,
			offset: 0,
			postType: 'post',
			order: 'desc',
			orderBy: 'date',
			author: '',
			search: '',
			exclude: [],
			sticky: 'exclude',
			inherit: false,
		},
    },
	innerBlocks: [
		[
			'core/post-template', {
				align: 'wide',
				style: {
					spacing: {
						blockGap: "15px"
					}
				},
				layout: {
					type: 'grid',
					columnCount: 3
				},
			},
			[
				['core/post-featured-image', {
					isLink: true,
					aspectRatio: "3/4"
				}],
				[ 'core/post-terms', {
					term: 'category'
				}],
				[ 'core/post-title', {
					isLink: true,
					level: 3
				}],
				['core/post-excerpt', {
					excerptLength: 30,
					showMoreOnNewLine: false
				}]
			],
		],
		[ 'core/query-no-results' ],
	],
    scope: [ 'inserter' ],
});
