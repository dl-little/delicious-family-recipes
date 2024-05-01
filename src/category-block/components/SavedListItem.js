import { renderCategoryName } from '../helpers';

export default function CategoryListItem( props ) {
	const { category } = props;
	const { value, label } = category;

	return (
		<li
			key={ value }
			data-id={ value }
			className={ `cat-item cat-item-${ value }` }
		>
			<a href={ '#' } className="cat-link">
				<img src="#" className="cat-img" />
				<span className="cat-span">
					{ renderCategoryName( label ) }
				</span>
			</a>
		</li>
	);
}
