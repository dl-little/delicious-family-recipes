import classnames from 'classnames';
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { decodeEntities } from '@wordpress/html-entities';
import { ToggleControl, PanelBody, RadioControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data'
import Select from 'react-select'

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

	const { attributes, setAttributes, className } = props;
	const { showBoxShadow, sortByCount, catCount, chosenCategories } = attributes

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

	const options = categories?.map( cat => ({
		value: cat.id,
		label: cat.name 
	}));

	const getCategoriesList = () => {
		if ( ! categories?.length ) {
			return [];
		}

		if ( !!sortByCount || !chosenCategories?.length ) {
			return categories;
		}

		const selectedIds  = chosenCategories?.map( cc => cc.value );
		const filteredCats = categories?.filter( cat => selectedIds.includes( cat.id ) ).sort( ( a, b ) => {
			return selectedIds.indexOf( a.id ) - selectedIds.indexOf( b.id )
		} )

		return filteredCats;
	};

	const renderCategoryName = ( name ) =>
		! name ? __( '(Untitled)' ) : decodeEntities( name ).trim();

	const renderCategoryList = () => {
		const categoriesList = getCategoriesList();
		return (
			<ul id='cat-list'>
				{categoriesList.map( ( category ) =>
					renderCategoryListItem( category )
				)}
			</ul>
		);
	};
	
	const renderCategoryListItem = ( category ) => {
		const { id, link, count, name } = category;
		return (
			<li key={ id } className={ `cat-item cat-item-${ id }` }>
				<a href={ link } target="_blank" rel="noreferrer noopener">
					{ renderCategoryName( name ) }
				</a>
			</li>
		);
	};

	const handleSelection = ( newValue, actionType ) => {
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
					<ToggleControl
						label={ __(
							'Enable box shadow',
							'dfr-category'
						) }
						checked={ showBoxShadow }
						onChange={ () => {
							setAttributes( {
								showBoxShadow:
									! showBoxShadow,
							} )
						} }
					/>
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
							} )
						} }
					/>
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
