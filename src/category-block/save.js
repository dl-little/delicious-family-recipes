import { useBlockProps } from '@wordpress/block-editor';
import SavedListItem from './components/SavedListItem';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {Element} Element to render.
 */
export default function save( props ) {

	const { attributes } = props;
	const { sortByCount, chosenCategories, popularCategories } = attributes;

	const categories   = !!sortByCount ? popularCategories : chosenCategories
	const numberOfCols = !!sortByCount ? popularCategories.length : chosenCategories.length;
	const gridAutoCols = numberOfCols >= 4 ? 'minmax(0, 4fr)' : `minmax(0, ${numberOfCols}fr)`;

	return (
		<aside { ...useBlockProps.save() }>
			<ul
				id='cat-list'
				style={
					{
						'--dfr-item-cols': `${gridAutoCols}`,
						'--dfr-item-count': `${numberOfCols}`,
					}
				}
				className={numberOfCols > 4 ? 'circle-style' : 'square-style'}
			>
				{categories.map( ( category ) =>
					<SavedListItem category={category} />
				)}
			</ul>
		</aside>
	);
}
