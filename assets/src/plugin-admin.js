import './styles/admin.scss';

/**
 * A void function.
 *
 * @param {jQuery} $ The jQuery object to be used in the function body
 */
( ( $ ) => {
	'use strict';
	$( () => {} );
	/**
	 * Updates the attributes of an image attachment.
	 *
	 * @param {number} attachmentId - The ID of the attachment to update.
	 * @param {Object} data - An object containing the properties to update on the attachment.
	 * @param {string} data.alt - The new alt text for the attachment.
	 * @param {string} data.title - The new title for the attachment.
	 * @param {string} data.caption - The new caption for the attachment.
	 */
	function updateImageAttributes( attachmentId, data ) {
		// Find the settings div that contains the input and textarea fields.
		const settingsEl = $( '.attachment-details .settings' );

		// Update the fields with the new data.
		settingsEl.find( "span[data-setting='alt'] textarea" ).val( data.alt );
		settingsEl.find( "span[data-setting='title'] input" ).val( data.title );
		settingsEl
			.find( "span[data-setting='caption'] textarea" )
			.val( data.caption );
	}

	/**
	 *
	 * @param {number} predictionId
	 */
	function pollPrediction( predictionId ) {
		$.ajax( {
			url: params.ajaxurl,
			type: 'post',
			data: {
				action: 'poll_prediction',
				prediction_id: predictionId,
				security: params.nonce,
			},
			success( response ) {
				if ( response.data && response.data.status === 'completed' ) {
					updateImageAttributes(
						attachmentId,
						response.data.prediction
					);
				} else {
					setTimeout( function () {
						pollPrediction( predictionId );
					}, params.poll_interval * 1000 );
				}
			},
			error( jqXHR, textStatus, errorThrown ) {
				alert( 'Error polling prediction: ', textStatus, errorThrown );
			},
		} );
	}

	/**
	 *
	 * @param attachmentId
	 * @param imageUrl
	 */
	function optimizeImageSEO( attachmentId, imageUrl ) {
		// Fields to optimize
		const fieldsToOptimize = [ 'alt', 'title', 'caption' ];

		$.ajax( {
			method: 'POST',
			url: params.ajaxurl,
			data: {
				action: 'optimize_image_seo',
				security: params.nonce,
				attachment_id: attachmentId,
				image_url: imageUrl,
				fields_to_optimize: fieldsToOptimize,
			},
			success( response ) {
				if ( response.success ) {
					if ( typeof response.data === 'object' ) {
						if ( 'prediction_id' in response.data ) {
							// Poll for the prediction result
							pollPrediction( response.data.prediction_id );
						} else if ( 'message' in response.data ) {
							// SEO metadata has been successfully updated
							alert( response.data.message );
						}
					} else {
						alert(
							'Error: Expected object with prediction_id or message in response data but received: ' +
								JSON.stringify( response.data )
						);
					}
				} else {
					alert( 'Error: ' + response.data );
				}
			},
			error( xhr, status, error ) {
				alert( 'Error: ' + error );
			},
		} );
	}

	if ( typeof wp !== 'undefined' && wp.media && wp.media.view ) {
		const originalAttachmentDetailsTwoColumn =
			wp.media.view.Attachment.Details.TwoColumn;

		wp.media.view.Attachment.Details.TwoColumn =
			originalAttachmentDetailsTwoColumn.extend( {
				render() {
					originalAttachmentDetailsTwoColumn.prototype.render.apply(
						this,
						arguments
					);

					const attachmentId = this.model.get( 'id' );
					const imageUrl = this.model.get( 'url' );

					const imageSeoGeneratorButton = this.$el.find(
						'#image_seo_generator_button'
					);

					if ( imageSeoGeneratorButton.length > 0 ) {
						imageSeoGeneratorButton
							.off( 'click' )
							.on( 'click', function () {
								optimizeImageSEO( attachmentId, imageUrl );
							} );
					}

					return this;
				},
			} );
	}
} )( jQuery );
