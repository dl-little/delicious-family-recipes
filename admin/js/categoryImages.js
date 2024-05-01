let frame,
	addImageLink = document.getElementById( 'dfr_insert_media_button' ),
	delImageLink = document.getElementById( 'dfr_delete_media_button' ),
	imageContainer = document.getElementById( 'dfr_image_container' ),
	imageIdInput = document.querySelector( 'input[type="hidden"].dfr_hidden' ),
	catSlug = document
		.getElementById( 'dfr_image_field' )
		.getAttribute( 'data-slug' );

addImageLink.addEventListener( 'click', function ( e ) {
	e.preventDefault();

	if ( frame ) {
		frame.open();
		return;
	}

	frame = wp.media( {
		title: 'Select or upload ' + catSlug + ' category image',
		multiple: false,
	} );

	frame.on( 'select', function () {
		const attachment = frame.state().get( 'selection' ).toJSON()[ 0 ];
		const attachmentSizeUrl = attachment.sizes.thumbnail
			? attachment.sizes.thumbnail.url
			: attachment.url;
		imageContainer.innerHTML = `<img width="150" height="150" src="${ attachmentSizeUrl }" class="attachment-thumbnail size-thumbnail" />`;
		imageIdInput.value = attachment.id;

		addImageLink.classList.add( 'dfr_hidden' );
		delImageLink.classList.remove( 'dfr_hidden' );
		imageContainer.classList.remove( 'dfr_image_not_chosen' );
		imageContainer.classList.add( 'dfr_image_chosen' );
	} );

	frame.open();
} );

delImageLink.addEventListener( 'click', function ( e ) {
	e.preventDefault();

	imageContainer.innerHTML = '';

	addImageLink.classList.remove( 'dfr_hidden' );
	delImageLink.classList.add( 'dfr_hidden' );
	imageContainer.classList.add( 'dfr_image_not_chosen' );
	imageContainer.classList.remove( 'dfr_image_chosen' );

	imageIdInput.value = '';
} );
