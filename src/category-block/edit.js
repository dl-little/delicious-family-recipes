import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { ToggleControl, PanelBody, RadioControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';
import CategoryListItem from './components/CategoryListItem';
import { useEffect } from '@wordpress/element';
import { standardizeCats } from './helpers';
import classnames from 'classnames';
import Select from 'react-select';

import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( props ) {

	const { attributes, setAttributes } = props;
	const { sortByCount, catCount, chosenCategories, selectCategories } = attributes;

	const { records: categories } = useEntityRecords(
		'taxonomy',
		'category',
		{
			per_page: !!sortByCount ? catCount : -1,
			...( !!sortByCount ) && {
				orderby: 'count',
				order: 'desc',
			},
		}
	);

	useEffect(() => {
		if ( !!categories && !!sortByCount ) {
			const popularCats = standardizeCats( categories )
			setAttributes( {
				popularCategories: popularCats
			} );
		}
	}, [ categories, sortByCount ])

	const options = standardizeCats( categories );

	const getCategoriesList = () => {
		if ( !categories?.length ) {
			return [];
		}

		if ( !!sortByCount ) {
			return categories;
		}

		if ( !chosenCategories?.length ) {
			return [];
		}

		const selectedIds  = chosenCategories?.map( cc => cc.value );
		const filteredCats = categories?.filter( cat => selectedIds.includes( cat.id ) )
		const sortedCats = filteredCats.sort( ( a, b ) => {
			return selectedIds.indexOf( a.id ) - selectedIds.indexOf( b.id )
		} );

		return sortedCats;
	};

	const renderCategoryList = () => {
		const categoriesList = getCategoriesList();
		const numberOfCols = !!sortByCount ? catCount : categoriesList.length;
		const gridAutoCols = numberOfCols >= 4 ? 'minmax(0, 4fr)' : `minmax(0, ${numberOfCols}fr)`;

		return (
			<ul
				style={
					{
						'--dfr-item-cols': `${gridAutoCols}`,
						'--dfr-item-count': `${numberOfCols}`,
					}
				}
				className={ classnames( 'cat-list', numberOfCols > 4 ? ' circle-style' : ' square-style') }
			>
				{categoriesList.map( ( category ) =>
					<CategoryListItem category={ category } />
				)}
			</ul>
		);
	};

	const handleSelection = ( newValue ) => {
		setAttributes( {
			chosenCategories: newValue
		} );
	};

	return (
		<>
			<aside { ...useBlockProps() }>
				{ renderCategoryList() }
			</aside>
			<InspectorControls>
				<PanelBody title="Settings">
					<ToggleControl
						label={ __(
							'Enable sort by count',
							'dfr-category'
						) }
						checked={ sortByCount }
						onChange={ () => {
							setAttributes( {
								sortByCount:
									! sortByCount,
								selectCategories:
									! selectCategories
							} )
						} }
					/>
					{ !!sortByCount &&
						<RadioControl
							label={ __(
								'Number of Category Items',
								'dfr-category'
							) }
							selected={ catCount }
							options={ [
								{ label: '3', value: '3' },
								{ label: '4', value: '4' },
								{ label: '6', value: '6' },
								{ label: '8', value: '8' },
							] }
							onChange={ ( value ) => {
								setAttributes( {
									catCount: value
								} )
							} }
						/>
					}
					{ !sortByCount &&
						<Select
							isMulti={true}
							options={options}
							defaultValue={chosenCategories}
							onChange={ (newValue, actionType ) => {
								handleSelection(newValue, actionType)
							} }
						/>
					}
				</PanelBody>
			</InspectorControls>
		</>
	);
}
