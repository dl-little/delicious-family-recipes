import { useSelect } from '@wordpress/data';
import { renderCategoryName } from '../helpers';

export default function CategoryListItem( props ) {
	const { category } = props;
	const { id, link, name, meta } = category;
	const { dfr_image } = meta;
	const imageObj = !!dfr_image.length ? useSelect( ( select ) => {
		return select( 'core' ).getMedia( dfr_image[0] )
	}) : null
	
	const getSource = ( sizes ) => {
		return sizes.full?.source_url
	}

	const renderCategoryImage = ( imageObj ) => {
		if (
			!imageObj.media_details
			|| !imageObj.media_details?.sizes
		) {
			return
		}

		const src = getSource( imageObj.media_details.sizes );
		return (
			<img
				className='cat-img'
				src={src}
			/>
		)
	}

	return (
		<li key={ id } className={ `cat-item cat-item-${ id }` }>
			<a href='#' className='cat-link'>
				{
					!!imageObj &&
						renderCategoryImage( imageObj )
				}
				<span className='cat-span'>
					{ renderCategoryName( name ) }
				</span>
			</a>
		</li>
	);
}