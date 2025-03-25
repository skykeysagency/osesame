/**
 * Swaches.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
'use strict';

const RbbThemeSwatches = {
	integrateSwatchesToDefaultSlider( $galleryElement, $thumbElement ) {
		const $form = jQuery( 'form.rbb-swatches.variations_form' );
		$form.on( 'show_variation', function ( event, variation ) {
			const $imageId = variation.image_id;
			if ( $imageId ) {
				const $image = jQuery( $galleryElement )
					.find( '[data-image-id="' + $imageId + '"]' )
					.first();
				const $sliderIndex = jQuery( $image )
					.closest( '.slick-slide' )
					.data( 'slick-index' );
				if ( $sliderIndex ) {
					jQuery( $thumbElement ).slick( 'slickGoTo', $sliderIndex );
				}
			}
		} );
	},

	integrateSwatchesToScrollImage( $galleryElement, $thumbElement ) {
		const $form = jQuery( 'form.rbb-swatches.variations_form' );
		$form.on( 'show_variation', function ( event, variation ) {
			const $imageId = variation.image_id;
			if ( $imageId ) {
				const $image = jQuery( $thumbElement )
					.find( '[data-image-id="' + $imageId + '"]' )
					.first();
				$image.closest( '.item' ).trigger( 'click' );
			}
		} );
	},

	integrateSwatchesToSlider( $galleryElement ) {
		const $form = jQuery( 'form.rbb-swatches.variations_form' );
		$form.on( 'show_variation', function ( event, variation ) {
			const $imageId = variation.image_id;
			if ( $imageId ) {
				const $image = jQuery( $galleryElement )
					.find( '[data-image-id="' + $imageId + '"]' )
					.closest( '.slick-slide' )
					.not( 'slick-cloned' )
					.first();
				const $sliderIndex = $image.data( 'slick-index' );
				if ( $sliderIndex !== 'undefined' ) {
					jQuery( $galleryElement ).slick(
						'slickGoTo',
						$sliderIndex
					);
				}
			}
		} );
	},
};

export default RbbThemeSwatches;
